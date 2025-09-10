<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Status;
use App\Models\Feature;
use App\Models\Attribute;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacilityProductController extends Controller
{
    /**
     * عرض قائمة المنتجات
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $query = $facility->products()->with(['category', 'statuses', 'features', 'attributes']);

        // فلترة حسب الفئة
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب الحالة
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->paginate(15);
        $categories = Category::with('translations')->get();
        $statuses = Status::all();

        return view('facility.products.index', compact('products', 'categories', 'statuses'));
    }

    /**
     * عرض صفحة إنشاء منتج جديد
     */
    public function create()
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $categories = Category::with('translations')->get();
        $statuses = Status::all();
        $cities = City::where('is_active', true)->orderBy('name')->get();

        // Prepare categories options for the select dropdown
        $categoryOptions = $categories->mapWithKeys(function ($category) {
            return [$category->id => $category->getTranslatedName()];
        })->toArray();

        return view('facility.products.create', compact('categories', 'statuses', 'cities', 'categoryOptions'));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'status_id' => 'required|exists:statuses,id',
            'owner_user_id' => 'required|exists:users,id',
            'building_id' => 'nullable|exists:buildings,id',
            'project_id' => 'nullable|exists:projects,id',
            'package_id' => 'nullable|exists:packages,id',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'floor_number' => 'nullable|integer',
            'total_floors' => 'nullable|integer|min:1',
            'parking_spaces' => 'nullable|integer|min:0',
            'furnished' => 'boolean',
            'available_for_rent' => 'boolean',
            'available_for_sale' => 'boolean',
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
            'attributes' => 'array',
            'attributes.*.attribute_id' => 'exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ]);

        $productData = $request->except(['main_image', 'features', 'attributes']);
        $productData['facility_id'] = $facility->id;

        // معالجة الصورة الرئيسية
        if ($request->hasFile('main_image')) {
            $imagePath = $request->file('main_image')->store('uploads/products/images', 'public');
            $productData['main_image'] = $imagePath;
        }

        $product = Product::create($productData);

        // ربط المميزات
        if ($request->has('features')) {
            $product->features()->attach($request->features);
        }

        // ربط الخصائص
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attribute) {
                $product->attributes()->create([
                    'attribute_id' => $attribute['attribute_id'],
                    'value' => $attribute['value'],
                ]);
            }
        }

        return redirect()->route('facility.products.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    /**
     * عرض صفحة تعديل المنتج
     */
    public function edit(Product $product)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', 'غير مصرح لك بتعديل هذا المنتج');
        }

                    $product->load(['category', 'city', 'statuses', 'features', 'attributes.translations']);
        $categories = Category::with('translations')->get();
        $statuses = Status::all();
        $attributes = Attribute::all();
        $cities = City::where('is_active', true)->orderBy('name')->get();

        // Prepare categories options for the select dropdown
        $categoryOptions = $categories->mapWithKeys(function ($category) {
            return [$category->id => $category->getTranslatedName()];
        })->toArray();

        return view('facility.products.edit', compact('product', 'categories', 'statuses', 'attributes', 'cities', 'categoryOptions'));
    }

    /**
     * تحديث بيانات المنتج
     */
    public function update(Request $request, Product $product)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', 'غير مصرح لك بتعديل هذا المنتج');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'status_id' => 'required|exists:statuses,id',
            'owner_user_id' => 'required|exists:users,id',
            'building_id' => 'nullable|exists:buildings,id',
            'project_id' => 'nullable|exists:projects,id',
            'package_id' => 'nullable|exists:packages,id',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'floor_number' => 'nullable|integer',
            'total_floors' => 'nullable|integer|min:1',
            'parking_spaces' => 'nullable|integer|min:0',
            'furnished' => 'boolean',
            'available_for_rent' => 'boolean',
            'available_for_sale' => 'boolean',
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
            'attributes' => 'array',
            'attributes.*.attribute_id' => 'exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ]);

        $productData = $request->except(['main_image', 'features', 'attributes']);

        // معالجة الصورة الرئيسية
        if ($request->hasFile('main_image')) {
            // حذف الصورة القديمة
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $imagePath = $request->file('main_image')->store('uploads/products/images', 'public');
            $productData['main_image'] = $imagePath;
        }

        $product->update($productData);

        // تحديث المميزات
        if ($request->has('features')) {
            $product->features()->sync($request->features);
        } else {
            $product->features()->detach();
        }

        // تحديث الخصائص
        if ($request->has('attributes')) {
            $product->attributes()->delete();
            foreach ($request->attributes as $attribute) {
                $product->attributes()->create([
                    'attribute_id' => $attribute['attribute_id'],
                    'value' => $attribute['value'],
                ]);
            }
        }

        return redirect()->route('facility.products.index')
            ->with('success', 'تم تحديث بيانات المنتج بنجاح');
    }

    /**
     * حذف المنتج
     */
    public function destroy(Product $product)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', 'غير مصرح لك بحذف هذا المنتج');
        }

        // حذف الصورة الرئيسية
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        $product->delete();

        return redirect()->route('facility.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل المنتج
     */
    public function toggleStatus(Product $product)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', 'غير مصرح لك بتعديل هذا المنتج');
        }

        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return redirect()->back()->with('success', "تم {$status} المنتج بنجاح");
    }

    /**
     * إضافة/إزالة من المميزات
     */
    public function toggleFeatured(Product $product)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', 'غير مصرح لك بتعديل هذا المنتج');
        }

        $product->update(['is_featured' => !$product->is_featured]);

        $status = $product->is_featured ? 'إضافة' : 'إزالة من';
        return redirect()->back()->with('success', "تم {$status} المميزات بنجاح");
    }

    /**
     * عرض تفاصيل المنتج
     */
    public function show(Product $product)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', 'غير مصرح لك بعرض هذا المنتج');
        }

        $product->load(['category', 'statuses', 'features', 'attributes.translations']);

        return view('facility.products.show', compact('product'));
    }
}
