<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;
use Illuminate\Support\Facades\Storage;

class AdminFeatureController extends Controller
{
    /**
     * عرض قائمة المميزات
     */
    public function index()
    {
        $features = Feature::withCount('products')->paginate(15);
        return view('admin.features.index', compact('features'));
    }

    /**
     * عرض صفحة إنشاء مميزة جديدة
     */
    public function create()
    {
        return view('admin.features.create');
    }

    /**
     * حفظ مميزة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $featureData = $request->except(['icon']);

        // معالجة الأيقونة
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('features/icons', 'public');
            $featureData['icon'] = $iconPath;
        }

        $feature = Feature::create($featureData);

        return redirect()->route('admin.features.index')
            ->with('success', 'تم إنشاء المميزة بنجاح');
    }

    /**
     * عرض صفحة تعديل المميزة
     */
    public function edit(Feature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    /**
     * تحديث المميزة
     */
    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $featureData = $request->except(['icon']);

        // معالجة الأيقونة
        if ($request->hasFile('icon')) {
            // حذف الأيقونة القديمة
            if ($feature->icon) {
                Storage::disk('public')->delete($feature->icon);
            }
            $iconPath = $request->file('icon')->store('features/icons', 'public');
            $featureData['icon'] = $iconPath;
        }

        $feature->update($featureData);

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
        $feature->load(['products']);
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
            'features.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->features as $featureData) {
            Feature::where('id', $featureData['id'])
                ->update(['sort_order' => $featureData['sort_order']]);
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
