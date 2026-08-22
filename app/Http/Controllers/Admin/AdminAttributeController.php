<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\AttributeTranslation;
use Illuminate\Support\Facades\Storage;

class AdminAttributeController extends Controller
{
    /**
     * عرض قائمة الخصائص
     */
    public function index(Request $request)
    {
        $query = Attribute::with(['category.parent', 'translations'])->withCount('products');

        // Filter by main category (sector)
        if ($request->filled('main_category_id')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('parent_id', $request->main_category_id);
            });
        }

        // Filter by subcategory
        if ($request->filled('subcategory_id')) {
            $query->where('category_id', $request->subcategory_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by required status
        if ($request->filled('required')) {
            $query->where('required', $request->required);
        }

        // Search by name
        if ($request->filled('q')) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        // Hide attributes linked to an inactive main category or inactive subcategory
        $query->where(function ($q) {
            $q->whereNull('category_id')
              ->orWhereHas('category', function ($categoryQuery) {
                  $categoryQuery->where('is_active', true)
                                ->whereHas('parent', function ($parentQuery) {
                                    $parentQuery->where('is_active', true);
                                });
              });
        });

        // Sorting
        $sort = $request->input('sort', 'created_at');
        $direction = in_array($request->input('direction', 'desc'), ['asc', 'desc'])
            ? $request->input('direction', 'desc')
            : 'desc';

        switch ($sort) {
            case 'name':
                $query->orderBy(
                    AttributeTranslation::select('name')
                        ->whereColumn('attribute_translations.attribute_id', 'attributes.id')
                        ->where('attribute_translations.locale', app()->getLocale()),
                    $direction
                );
                break;
            case 'products_count':
                $query->orderBy('products_count', $direction);
                break;
            case 'is_active':
                $query->orderBy('is_active', $direction);
                break;
            case 'required':
                $query->orderBy('required', $direction);
                break;
            case 'type':
                $query->orderBy('type', $direction);
                break;
            case 'id':
                $query->orderBy('id', $direction);
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', $direction);
                break;
        }

        $attributes = $query->paginate(15)->withQueryString();

        $mainCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children.translations'])
            ->get();

        $subCategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->with('translations')
            ->get();

        $view = ($request->ajax() || $request->input('partial'))
            ? 'admin.attributes._table'
            : 'admin.attributes.index';

        return view($view, compact('attributes', 'mainCategories', 'subCategories'));
    }

    /**
     * عرض صفحة إنشاء خاصية جديدة
     */
    public function create()
    {
        $categories = Category::all();
        $locales = config('locales.available');
        return view('admin.attributes.create', compact('categories', 'locales'));
    }

    /**
     * حفظ خاصية جديدة
     */
    public function store(Request $request)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'type' => 'required|string|max:255',
            'required' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon_name' => 'nullable|string|max:255',
            'Symbol' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.name' => 'required_with:translations|string|max:255',
            'translations.*.symbol' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'symbol' => 'nullable|string|max:255',
        ]);

        $incomingTranslations = $request->input('translations');
        if (!is_array($incomingTranslations) || !count($incomingTranslations)) {
            $incomingTranslations = [];
            if ($request->filled('name') || $request->filled('symbol')) {
                $incomingTranslations[] = [
                    'locale' => app()->getLocale(),
                    'name' => $request->input('name'),
                    'symbol' => $request->input('symbol'),
                ];
            }
        }

        $firstTranslationName = null;
        foreach ($incomingTranslations as $t) {
            if (!empty($t['name'])) {
                $firstTranslationName = $t['name'];
                break;
            }
        }

        if (!$firstTranslationName) {
            return back()
                ->withErrors(['translations' => 'يجب إدخال اسم الخاصية في ترجمة واحدة على الأقل'])
                ->withInput();
        }

        $attributeData = $request->except(['icon', 'icon_name', 'translations', 'name', 'symbol']);

        // Handle checkbox fields
        $attributeData['required'] = $request->has('required');

        // معالجة الأيقونة (صورة مرفوعة أو اسم أيقونة Font Awesome)
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('attributes/icons', 'public');
            $attributeData['icon'] = $iconPath;
        } elseif ($request->filled('icon_name')) {
            $attributeData['icon'] = $request->input('icon_name');
        }

        $attribute = Attribute::create($attributeData);

        foreach ($incomingTranslations as $translationData) {
            if (empty($translationData['locale']) || empty($translationData['name'])) {
                continue;
            }

            $attribute->translations()->create([
                'locale' => $translationData['locale'],
                'name' => $translationData['name'],
                'symbol' => $translationData['symbol'] ?? null,
            ]);
        }

        return redirect()->route('admin.attributes.index')
            ->with('success', 'تم إنشاء الخاصية بنجاح');
    }

    /**
     * عرض صفحة تعديل الخاصية
     */
    public function edit(Attribute $attribute)
    {
        $attribute->load(['category', 'translations']);
        $categories = Category::all();
        $locales = config('locales.available');
        return view('admin.attributes.edit', compact('attribute', 'categories', 'locales'));
    }

    /**
     * تحديث الخاصية
     */
    public function update(Request $request, Attribute $attribute)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'type' => 'required|string|max:255',
            'required' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Symbol' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.name' => 'required_with:translations|string|max:255',
            'translations.*.symbol' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'symbol' => 'nullable|string|max:255',
        ]);

        $incomingTranslations = $request->input('translations');
        if (!is_array($incomingTranslations) || !count($incomingTranslations)) {
            $incomingTranslations = [];
            if ($request->filled('name') || $request->filled('symbol')) {
                $incomingTranslations[] = [
                    'locale' => app()->getLocale(),
                    'name' => $request->input('name'),
                    'symbol' => $request->input('symbol'),
                ];
            }
        }

        $firstTranslationName = null;
        foreach ($incomingTranslations as $t) {
            if (!empty($t['name'])) {
                $firstTranslationName = $t['name'];
                break;
            }
        }

        if (!$firstTranslationName) {
            return back()
                ->withErrors(['translations' => 'يجب إدخال اسم الخاصية في ترجمة واحدة على الأقل'])
                ->withInput();
        }

        $attributeData = $request->except(['icon', 'translations', 'name', 'symbol']);

        // Handle checkbox fields
        $attributeData['required'] = $request->has('required');

        // معالجة الأيقونة (صورة مرفوعة أو اسم أيقونة Font Awesome)
        if ($request->hasFile('icon')) {
            // حذف الأيقونة القديمة إذا كانت صورة
            if ($attribute->icon && str_contains($attribute->icon, '/')) {
                Storage::disk('public')->delete($attribute->icon);
            }
            $iconPath = $request->file('icon')->store('attributes/icons', 'public');
            $attributeData['icon'] = $iconPath;
        } elseif ($request->filled('icon_name')) {
            $attributeData['icon'] = $request->input('icon_name');
        }

        $attribute->update($attributeData);

        $keepLocales = [];
        foreach ($incomingTranslations as $translationData) {
            if (empty($translationData['locale']) || empty($translationData['name'])) {
                continue;
            }

            $keepLocales[] = $translationData['locale'];

            $attribute->translations()->updateOrCreate(
                [
                    'locale' => $translationData['locale'],
                ],
                [
                    'name' => $translationData['name'],
                    'symbol' => $translationData['symbol'] ?? null,
                ]
            );
        }

        $attribute->translations()
            ->whereNotIn('locale', array_values(array_unique($keepLocales)))
            ->delete();

        return redirect()->route('admin.attributes.index')
            ->with('success', 'تم تحديث الخاصية بنجاح');
    }

    /**
     * حذف الخاصية
     */
    public function destroy(Attribute $attribute)
    {
        // حذف الأيقونة
        if ($attribute->icon) {
            Storage::disk('public')->delete($attribute->icon);
        }

        $attribute->delete();

        return redirect()->route('admin.attributes.index')
            ->with('success', 'تم حذف الخاصية بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل الخاصية
     */
    public function toggleRequired(Attribute $attribute)
    {
        $attribute->update(['required' => !$attribute->required]);

        $status = $attribute->required ? 'إلزامية' : 'اختيارية';
        return redirect()->back()->with('success', "تم جعل الخاصية {$status} بنجاح");
    }

    /**
     * تفعيل/تعطيل الخاصية
     */
    public function toggleStatus(Attribute $attribute)
    {
        $attribute->update(['is_active' => !$attribute->is_active]);

        $status = $attribute->is_active ? 'مفعلة' : 'معطلة';
        return redirect()->back()->with('success', "تم جعل الخاصية {$status} بنجاح");
    }

    /**
     * عرض تفاصيل الخاصية
     */
    public function show(Attribute $attribute)
    {
        $attribute->load(['category', 'translations', 'products']);
        return view('admin.attributes.show', compact('attribute'));
    }

    /**
     * إحصائيات الخصائص
     */
    public function statistics()
    {
        $totalAttributes = Attribute::count();
        $requiredAttributes = Attribute::where('required', true)->count();
        $optionalAttributes = Attribute::where('required', false)->count();
        $attributesByCategory = Attribute::with('category')
            ->get()
            ->groupBy('category.name');

        return view('admin.attributes.statistics', compact(
            'totalAttributes',
            'requiredAttributes',
            'optionalAttributes',
            'attributesByCategory'
        ));
    }
}
