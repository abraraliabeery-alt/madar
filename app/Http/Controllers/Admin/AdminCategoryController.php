<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    /**
     * عرض قائمة الفئات
     */
    public function index()
    {
        $categories = Category::withCount('products')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * عرض صفحة إنشاء فئة جديدة
     */
    public function create()
    {
        $categories = Category::where('parent_id', null)->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * حفظ فئة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $categoryData = $request->except(['icon', 'image']);

        // معالجة الأيقونة
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('categories/icons', 'public');
            $categoryData['icon'] = $iconPath;
        }

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories/images', 'public');
            $categoryData['image'] = $imagePath;
        }

        $category = Category::create($categoryData);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إنشاء الفئة بنجاح');
    }

    /**
     * عرض صفحة تعديل الفئة
     */
    public function edit(Category $category)
    {
        $categories = Category::where('parent_id', null)
            ->where('id', '!=', $category->id)
            ->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * تحديث الفئة
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $categoryData = $request->except(['icon', 'image']);

        // معالجة الأيقونة
        if ($request->hasFile('icon')) {
            // حذف الأيقونة القديمة
            if ($category->icon) {
                Storage::disk('public')->delete($category->icon);
            }
            $iconPath = $request->file('icon')->store('categories/icons', 'public');
            $categoryData['icon'] = $iconPath;
        }

        // معالجة الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('categories/images', 'public');
            $categoryData['image'] = $imagePath;
        }

        $category->update($categoryData);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * حذف الفئة
     */
    public function destroy(Category $category)
    {
        // التحقق من وجود منتجات مرتبطة
        if ($category->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الفئة لوجود منتجات مرتبطة بها');
        }

        // التحقق من وجود فئات فرعية
        if ($category->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الفئة لوجود فئات فرعية مرتبطة بها');
        }

        // حذف الأيقونة والصورة
        if ($category->icon) {
            Storage::disk('public')->delete($category->icon);
        }
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل الفئة
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return redirect()->back()->with('success', "تم {$status} الفئة بنجاح");
    }

    /**
     * إضافة/إزالة من المميزات
     */
    public function toggleFeatured(Category $category)
    {
        $category->update(['is_featured' => !$category->is_featured]);

        $status = $category->is_featured ? 'إضافة' : 'إزالة من';
        return redirect()->back()->with('success', "تم {$status} المميزات بنجاح");
    }

    /**
     * عرض تفاصيل الفئة
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products']);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * ترتيب الفئات
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->categories as $categoryData) {
            Category::where('id', $categoryData['id'])
                ->update(['sort_order' => $categoryData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم ترتيب الفئات بنجاح'
        ]);
    }

    /**
     * إحصائيات الفئات
     */
    public function statistics()
    {
        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'featured_categories' => Category::where('is_featured', true)->count(),
            'parent_categories' => Category::where('parent_id', null)->count(),
            'sub_categories' => Category::where('parent_id', '!=', null)->count(),
            'categories_with_products' => Category::has('products')->count(),
            'top_categories' => Category::withCount('products')
                ->orderBy('products_count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.categories.statistics', compact('stats'));
    }
}
