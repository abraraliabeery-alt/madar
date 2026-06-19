<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;
use App\Models\FeatureTranslation;
use Illuminate\Support\Facades\Storage;

class AdminFeatureController extends Controller
{
    /**
     * عرض قائمة المميزات
     */
    public function index()
    {
        $features = Feature::withCount('products')->with('translations')->paginate(15);
        return view('admin.features.index', compact('features'));
    }

    /**
     * عرض صفحة إنشاء مميزة جديدة
     */
    public function create()
    {
        $locales = config('locales.available');
        return view('admin.features.create', compact('locales'));
    }

    /**
     * حفظ مميزة جديدة
     */
    public function store(Request $request)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.name' => 'required_with:translations|string|max:255',
            'translations.*.description' => 'nullable|string',
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $incomingTranslations = $request->input('translations');
        if (!is_array($incomingTranslations) || !count($incomingTranslations)) {
            $incomingTranslations = [];
            if ($request->filled('name_ar') || $request->filled('description_ar')) {
                $incomingTranslations[] = [
                    'locale' => 'ar',
                    'name' => $request->input('name_ar'),
                    'description' => $request->input('description_ar'),
                ];
            }
            if ($request->filled('name_en') || $request->filled('description_en')) {
                $incomingTranslations[] = [
                    'locale' => 'en',
                    'name' => $request->input('name_en'),
                    'description' => $request->input('description_en'),
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
                ->withErrors(['translations' => 'يجب إدخال اسم المميزة في ترجمة واحدة على الأقل'])
                ->withInput();
        }

        $featureData = [
            'description' => $request->input('description_ar'),
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0,
        ];

        // معالجة الأيقونة: صورة مرفوعة أو كلاس Font Awesome من icon_name
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('features/icons', 'public');
            $featureData['icon'] = $iconPath;
        } elseif ($request->filled('icon_name')) {
            $featureData['icon'] = $request->input('icon_name');
        }

        $feature = Feature::create($featureData);

        foreach ($incomingTranslations as $translationData) {
            if (empty($translationData['locale']) || empty($translationData['name'])) {
                continue;
            }

            FeatureTranslation::create([
                'feature_id' => $feature->id,
                'locale' => $translationData['locale'],
                'name' => $translationData['name'],
                'description' => $translationData['description'] ?? null,
            ]);
        }

        return redirect()->route('admin.features.index')
            ->with('success', 'تم إنشاء المميزة بنجاح');
    }

    /**
     * عرض صفحة تعديل المميزة
     */
    public function edit(Feature $feature)
    {
        $feature->load('translations');
        $locales = config('locales.available');
        return view('admin.features.edit', compact('feature', 'locales'));
    }

    /**
     * تحديث المميزة
     */
    public function update(Request $request, Feature $feature)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.name' => 'required_with:translations|string|max:255',
            'translations.*.description' => 'nullable|string',
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $incomingTranslations = $request->input('translations');
        if (!is_array($incomingTranslations) || !count($incomingTranslations)) {
            $incomingTranslations = [];
            if ($request->filled('name_ar') || $request->filled('description_ar')) {
                $incomingTranslations[] = [
                    'locale' => 'ar',
                    'name' => $request->input('name_ar'),
                    'description' => $request->input('description_ar'),
                ];
            }
            if ($request->filled('name_en') || $request->filled('description_en')) {
                $incomingTranslations[] = [
                    'locale' => 'en',
                    'name' => $request->input('name_en'),
                    'description' => $request->input('description_en'),
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
                ->withErrors(['translations' => 'يجب إدخال اسم المميزة في ترجمة واحدة على الأقل'])
                ->withInput();
        }

        $featureData = [
            'description' => $request->input('description_ar'), // Default description
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0,
        ];

        // معالجة الأيقونة: صورة مرفوعة أو كلاس Font Awesome من icon_name
        if ($request->hasFile('icon')) {
            // حذف الأيقونة القديمة إن كانت مسار ملف
            if ($feature->icon && str_contains($feature->icon, '/')) {
                Storage::disk('public')->delete($feature->icon);
            }
            $iconPath = $request->file('icon')->store('features/icons', 'public');
            $featureData['icon'] = $iconPath;
        } elseif ($request->filled('icon_name')) {
            $featureData['icon'] = $request->input('icon_name');
        }

        $feature->update($featureData);

        $keepLocales = [];
        foreach ($incomingTranslations as $translationData) {
            if (empty($translationData['locale']) || empty($translationData['name'])) {
                continue;
            }

            $keepLocales[] = $translationData['locale'];

            FeatureTranslation::updateOrCreate(
                [
                    'feature_id' => $feature->id,
                    'locale' => $translationData['locale'],
                ],
                [
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? null,
                ]
            );
        }

        $feature->translations()
            ->whereNotIn('locale', array_values(array_unique($keepLocales)))
            ->delete();

        return redirect()->route('admin.features.index')
            ->with('success', 'تم تحديث المميزة بنجاح');
    }

    /**
     * حذف المميزة
     */
    public function destroy(Feature $feature)
    {
        // التحقق من وجود منتجات مرتبطة
        if ($feature->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف المميزة لوجود منتجات مرتبطة بها');
        }

        // حذف الأيقونة
        if ($feature->icon) {
            Storage::disk('public')->delete($feature->icon);
        }

        // Delete translations
        $feature->translations()->delete();
        
        $feature->delete();

        return redirect()->route('admin.features.index')
            ->with('success', 'تم حذف المميزة بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل المميزة
     */
    public function toggleStatus(Feature $feature)
    {
        $feature->update(['is_active' => !$feature->is_active]);

        $status = $feature->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return redirect()->back()->with('success', "تم {$status} المميزة بنجاح");
    }

    /**
     * عرض تفاصيل المميزة
     */
    public function show(Feature $feature)
    {
        $feature->load(['products', 'translations']);
        return view('admin.features.show', compact('feature'));
    }

    /**
     * ترتيب المميزات
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'features' => 'required|array',
            'features.*.id' => 'required|exists:features,id',
            'features.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->features as $featureData) {
            Feature::where('id', $featureData['id'])
                ->update(['order' => $featureData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم ترتيب المميزات بنجاح'
        ]);
    }

    /**
     * إحصائيات المميزات
     */
    public function statistics()
    {
        $stats = [
            'total_features' => Feature::count(),
            'active_features' => Feature::where('is_active', true)->count(),
            'features_with_products' => Feature::has('products')->count(),
            'top_features' => Feature::withCount('products')
                ->orderBy('products_count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.features.statistics', compact('stats'));
    }
}
