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
        $query = Attribute::with(['category', 'translations'])->withCount('products');

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by required status
        if ($request->has('required')) {
            $query->where('required', $request->required);
        }

        // Search by name
        if ($request->has('q') && $request->q) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        $attributes = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('admin.attributes.index', compact('attributes', 'categories'));
    }

    /**
     * عرض صفحة إنشاء خاصية جديدة
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.attributes.create', compact('categories'));
    }

    /**
     * حفظ خاصية جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'required' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon_name' => 'nullable|string|max:255',
            'Symbol' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:255',
        ]);

        $attributeData = $request->except(['icon', 'icon_name', 'name', 'symbol']);

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

        // إنشاء الترجمة
        $attribute->translations()->create([
            'locale' => app()->getLocale(),
            'name' => $request->name,
            'symbol' => $request->symbol,
        ]);

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
        return view('admin.attributes.edit', compact('attribute', 'categories'));
    }

    /**
     * تحديث الخاصية
     */
    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'required' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Symbol' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:255',
        ]);

        $attributeData = $request->except(['icon', 'name', 'symbol']);

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

        // تحديث الترجمة
        $translation = $attribute->translations()->where('locale', app()->getLocale())->first();
        if ($translation) {
            $translation->update([
                'name' => $request->name,
                'symbol' => $request->symbol,
            ]);
        } else {
            $attribute->translations()->create([
                'locale' => app()->getLocale(),
                'name' => $request->name,
                'symbol' => $request->symbol,
            ]);
        }

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
