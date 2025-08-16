<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Facility;
use App\Models\Category;
use App\Models\Status;
use App\Models\Feature;
use App\Models\Attribute;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * عرض قائمة المنتجات
     */
    public function index(Request $request)
    {
        $query = Product::with(['facility', 'category', 'owner']);

        // Filter by status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'verified':
                    $query->where('is_verified', true);
                    break;
            }
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by facility
        if ($request->has('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Search by keyword
        if ($request->has('q') && $request->q) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%")
                  ->orWhere('address', 'like', "%{$request->q}%");
            });
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();
        $facilities = Facility::all();

        return view('admin.products.index', compact('products', 'categories', 'facilities'));
    }

    /**
     * عرض صفحة إنشاء منتج جديد
     */
    public function create()
    {
        $facilities = Facility::all();
        $categories = Category::all();
        $statuses = Status::all();
        $features = Feature::all();
        $attributes = Attribute::all();

        return view('admin.products.create', compact('facilities', 'categories', 'statuses', 'features', 'attributes'));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'facility_id' => 'required|exists:facilities,id',
            'category_id' => 'required|exists:categories,id',
            'status_id' => 'required|exists:statuses,id',
            'owner_user_id' => 'required|exists:users,id',
            'rooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'floor' => 'nullable|string',
            'floors_count' => 'nullable|integer|min:1',
            'parking_spaces' => 'nullable|integer|min:0',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
        ]);

        $productData = $request->except(['image', 'features', 'status_id']);

        // Handle checkbox fields - set to false if not present
        $productData['is_active'] = $request->has('is_active');
        $productData['is_featured'] = $request->has('is_featured');
        $productData['is_verified'] = $request->has('is_verified');

        // معالجة الصورة الرئيسية
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/products/images', 'public');
            $productData['image'] = $imagePath;
        }

        $product = Product::create($productData);

        // ربط الحالة
        if ($request->has('status_id')) {
            $product->statuses()->attach($request->status_id, [
                'notes' => 'تم تعيين الحالة عند إنشاء المنتج',
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ربط المميزات
        if ($request->has('features')) {
            $product->features()->attach($request->features);
        }



        return redirect()->route('admin.products.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    /**
     * عرض صفحة تعديل المنتج
     */
    public function edit(Product $product)
    {
        $product->load(['facility', 'category', 'statuses', 'features']);
        $facilities = Facility::all();
        $categories = Category::all();
        $statuses = Status::all();
        $features = Feature::all();

        return view('admin.products.edit', compact('product', 'facilities', 'categories', 'statuses', 'features'));
    }

    /**
     * تحديث المنتج
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'facility_id' => 'required|exists:facilities,id',
            'category_id' => 'required|exists:categories,id',
            'status_id' => 'required|exists:statuses,id',
            'owner_user_id' => 'required|exists:users,id',
            'rooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'floor' => 'nullable|string',
            'floors_count' => 'nullable|integer|min:1',
            'parking_spaces' => 'nullable|integer|min:0',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
        ]);

        $productData = $request->except(['image', 'features', 'status_id']);

        // Handle checkbox fields - set to false if not present
        $productData['is_active'] = $request->has('is_active');
        $productData['is_featured'] = $request->has('is_featured');
        $productData['is_verified'] = $request->has('is_verified');

        // معالجة الصورة الرئيسية
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('uploads/products/images', 'public');
            $productData['image'] = $imagePath;
        }

        $product->update($productData);

        // تحديث الحالة
        if ($request->has('status_id')) {
            // حذف الحالة القديمة وإضافة الحالة الجديدة
            $product->statuses()->detach();
            $product->statuses()->attach($request->status_id, [
                'notes' => 'تم تحديث الحالة',
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // تحديث المميزات
        if ($request->has('features')) {
            $product->features()->sync($request->features);
        } else {
            $product->features()->detach();
        }



        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * حذف المنتج
     */
    public function destroy(Product $product)
    {
        // حذف الصورة الرئيسية
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل المنتج
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return redirect()->back()->with('success', "تم {$status} المنتج بنجاح");
    }

    /**
     * التحقق من المنتج
     */
    public function toggleVerification(Product $product)
    {
        $product->update(['is_verified' => !$product->is_verified]);

        $status = $product->is_verified ? 'التحقق من' : 'إلغاء التحقق من';
        return redirect()->back()->with('success', "تم {$status} المنتج بنجاح");
    }

    /**
     * إضافة/إزالة من المميزات
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        $status = $product->is_featured ? 'إضافة' : 'إزالة من';
        return redirect()->back()->with('success', "تم {$status} المميزات بنجاح");
    }

    /**
     * عرض تفاصيل المنتج
     */
    public function show(Product $product)
    {
        $product->load(['facility', 'category', 'statuses', 'features', 'attributes', 'bookings']);
        return view('admin.products.show', compact('product'));
    }
}
