<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Package;
use App\Models\Feature;
use App\Models\Attribute;
use App\Models\City;
use App\Models\Offer;
use App\Models\Facility;
use App\Models\MaintenanceRequest;
use App\Models\Invoice;
use App\Models\CategoryProductLifecycleStage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class FacilityProductController extends Controller
{
    /**
     * عرض قائمة المنتجات
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }
        // حفظ واسترجاع الفلاتر من الـ session
        $filterKeys = [
            'search', 'category_id', 'status_id', 'seller_user_id', 'owner_user_id',
            'sort', 'quality', 'price_min', 'price_max',
            'featured', 'verified', 'active', 'has_image',
        ];
        $hasAnyFilterInRequest = collect($filterKeys)->contains(function ($key) use ($request) {
            return $request->filled($key);
        });

        // إذا لم تُرسل أي بارامترات في الطلب الحالي، وحُفظت فلاتر سابقاً، نعيد التوجيه مع الفلاتر المحفوظة
        if (!$hasAnyFilterInRequest && empty($request->query())) {
            $savedFilters = $request->session()->get('facility.products.filters');
            if (is_array($savedFilters) && !empty($savedFilters)) {
                return redirect()->route('facility.products.index', $savedFilters);
            }
        }

        // في حال تم إرسال فلاتر جديدة، نحفظها في الـ session
        if ($hasAnyFilterInRequest) {
            $request->session()->put('facility.products.filters', $request->only($filterKeys));
        }

        $query = $facility->products()
            ->with(['category', 'statuses', 'features', 'attributes', 'seller', 'owner'])
            ->withCount(['bookings', 'saleOffers']);

        // (تمت إزالة فلترة السعر لأن حقل price لم يعد موجوداً على المنتجات)

        // فلترة حسب الفئة
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب الحالة
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // فلترة حسب الموظف المسؤول
        if ($request->has('seller_user_id') && $request->seller_user_id) {
            $query->where('seller_user_id', $request->seller_user_id);
        }

        // فلترة حسب المالك
        if ($request->has('owner_user_id') && $request->owner_user_id) {
            $query->where('owner_user_id', $request->owner_user_id);
        }

        // فلاتر منطقية إضافية
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->boolean('verified')) {
            $query->where('is_verified', true);
        }

        if ($request->filled('active')) {
            // active=1 أو active=0
            $active = $request->get('active') === '0' ? 0 : 1;
            $query->where('is_active', $active);
        }

        if ($request->boolean('has_image')) {
            $query->whereNotNull('main_image')
                  ->where('main_image', '<>', '');
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('address', 'like', '%' . $request->search . '%')
                  ->orWhere('additional_info', 'like', '%' . $request->search . '%');
            });
        }

        // فلترة الجودة (تجريبية خلف علم)
        // تم تبسيط المنطق ليعتمد فقط على توفر الصورة والموقع، بعد إزالة حقل السعر من جدول المنتجات.
        if (config('features.facility_listings_qs') && $request->filled('quality')) {
            $quality = $request->get('quality');
            if ($quality === 'excellent') {
                // ممتاز: تتوفر صورة وموقع (إحداثيات)
                $query->whereNotNull('main_image')
                      ->where('main_image', '<>', '')
                      ->whereNotNull('latitude')
                      ->whereNotNull('longitude');
            } elseif ($quality === 'attention') {
                // يحتاج انتباه: أي عنصر أساسي (صورة أو موقع) مفقود
                $query->where(function($q){
                    $q->whereNull('main_image')
                      ->orWhere('main_image', '=','')
                      ->orWhereNull('latitude')
                      ->orWhereNull('longitude');
                });
            }
        }

        // الترتيب
        // تمت إزالة خيارات الترتيب حسب السعر لأن حقل price لم يعد موجوداً.
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'recent':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(15);
        $categories = Category::with('translations')->get();
        $statuses = Status::all();

        // قائمة الموظفين المسؤولين (seller) المرتبطين بمنتجات هذه المنشأة
        $sellerIds = $facility->products()
            ->whereNotNull('seller_user_id')
            ->pluck('seller_user_id')
            ->unique()
            ->values();
        $sellers = User::whereIn('id', $sellerIds)->get();

        // قائمة الملاك (owner) المرتبطين بمنتجات هذه المنشأة
        $ownerIds = $facility->products()
            ->whereNotNull('owner_user_id')
            ->pluck('owner_user_id')
            ->unique()
            ->values();
        $owners = User::whereIn('id', $ownerIds)->get();

        return view('facility.products.index', compact('products', 'categories', 'statuses', 'sellers', 'owners'));
    }

    /**
     * عرض صفحة إنشاء منتج جديد
     */
    public function create()
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $mainCategories = Category::with('translations')->whereNull('parent_id')->get();
        $subCategories = Category::with('translations')->whereNotNull('parent_id')->get();

        $buildingMainCategoryIds = [];
        if (Schema::hasColumn('categories', 'requires_building')) {
            $buildingMainCategoryIds = $mainCategories->filter(fn($c) => (bool) $c->requires_building)->pluck('id')->map(fn($id) => (string) $id)->values()->toArray();
        }

        $mainCategoryOptions = $mainCategories->mapWithKeys(function ($c) {
            return [$c->id => $c->getTranslatedName()];
        })->toArray();
        $subCategoriesList = $subCategories->map(function ($c) {
            return [
                'id' => $c->id,
                'parent_id' => $c->parent_id,
                'name' => $c->getTranslatedName(),
            ];
        })->values();

        $statuses = Status::with('translations')->active()->ordered()->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        // Prepare translated statuses options
        $statusOptions = $statuses->mapWithKeys(function ($status) {
            return [$status->id => $status->getTranslatedName(app()->getLocale())];
        });
        $buildingOptions = [];


        // مستخدمون يمكن تعيينهم كمالك/موظف مسؤول (مستخلصون من المنتجات الحالية للمنشأة إن وجدت)
        $userIdsFromProducts = $facility->products()
            ->pluck('owner_user_id', 'seller_user_id')
            ->flatten()
            ->filter()
            ->unique()
            ->values();
        $userOptions = $userIdsFromProducts->isNotEmpty()
            ? User::whereIn('id', $userIdsFromProducts)->pluck('name', 'id')->toArray()
            : [];

        $storeRoute = route('facility.products.store');
        $indexRoute = route('facility.products.index');
        $layout = 'facility.layouts.app';
        $loadTailwind = false;

        return view('products.create', compact(
            'mainCategoryOptions', 'subCategoriesList', 'statuses', 'cities',
            'statusOptions', 'buildingOptions', 'buildingMainCategoryIds',
            'userOptions', 'storeRoute', 'indexRoute', 'layout', 'loadTailwind'
        ));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
            'street_id' => 'nullable|exists:streets,id',
            'status_id' => 'required|exists:statuses,id',
            'package_id' => 'nullable|exists:packages,id',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'nullable|string|distinct',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_gallery' => 'nullable|array',
            'image_gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,webm',
            'video_url' => 'nullable|url',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
            'attributes' => 'array',
            'attributes.*.attribute_id' => 'exists:attributes,id',
            'attributes.*.value' => 'nullable',
            // Offer availability flags
            'available_for_sale' => 'boolean',
            'available_for_rent' => 'boolean',
            // Sale offer validation (conditional)
            'sale_offer.price' => 'exclude_unless:available_for_sale,1|required|numeric|min:0',
            // Rent offer validation (conditional)
            'rent_offer.price' => 'exclude_unless:available_for_rent,1|required|numeric|min:0',
            'rent_offer.period' => 'exclude_unless:available_for_rent,1|required|in:rent_daily,rent_monthly,rent_yearly',
            'rent_offer.deposit' => 'exclude_unless:available_for_rent,1|nullable|numeric|min:0',
            'owner_user_id' => 'nullable|exists:users,id',
            'seller_user_id' => 'nullable|exists:users,id',
        ]);

        // Custom validation for required attributes only
        if ($request->has('attributes')) {
            $requiredAttributeModels = \App\Models\Attribute::where('required', true)
                ->whereIn('id', collect($request->attributes)->pluck('attribute_id'))
                ->with('translations')
                ->get()
                ->keyBy('id');
            $requiredAttributeIds = $requiredAttributeModels->keys()->toArray();

            foreach ($request->attributes as $index => $attribute) {
                $attributeId = $attribute['attribute_id'];
                $value = $attribute['value'];

                // Check if required attribute has value
                if (in_array($attributeId, $requiredAttributeIds)) {
                    if (is_null($value) || $value === '') {
                        $attributeName = $requiredAttributeModels->get($attributeId)?->getTranslatedName() ?? 'الخاصية';
                        return redirect()->back()
                            ->withErrors(["attributes.{$index}.value" => "{$attributeName} مطلوب."])
                            ->withInput();
                    }
                }
            }
        }

        $productData = $request->except(['main_image', 'image_gallery', 'video', 'video_url', 'features', 'attributes']);
        $productData['facility_id'] = $facility->id;

        // معالجة الصورة الرئيسية
        if ($request->hasFile('main_image')) {
            $imagePath = $request->file('main_image')->store('uploads/products/images', 'public');
            $productData['main_image'] = $imagePath;
        }

        // معالجة معرض الصور
        if ($request->hasFile('image_gallery')) {
            $galleryPaths = [];
            foreach ($request->file('image_gallery') as $file) {
                $galleryPaths[] = $file->store('uploads/products/gallery', 'public');
            }
            $productData['image_gallery'] = $galleryPaths;
        }

        // معالجة الفيديو
        if ($request->hasFile('video')) {
            $productData['video'] = $request->file('video')->store('uploads/products/videos', 'public');
        } elseif ($request->filled('video_url')) {
            $productData['video'] = $request->input('video_url');
        }

        $product = Product::create($productData);

        // مزامنة سجلّ الحالات اختيارياً
        if (env('PRODUCT_STATUS_SYNC_ENABLED', true) && isset($productData['status_id'])) {
            try {
                $product->statuses()->attach($productData['status_id'], [
                    'notes' => 'تعيين الحالة عند الإنشاء (منشأة)',
                    'user_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) { /* silent */ }
        }

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

        $this->syncLegacyAttributes($request, $product);

        // Create sale offer if available for sale
        if ($request->input('available_for_sale')) {
            $so = $request->input('sale_offer', []);
            try {
                Offer::create([
                    'product_id' => $product->id,
                    'facility_id' => $facility->id,
                    'created_by' => Auth::id(),
                    'offer_type' => 'sale',
                    'price' => isset($so['price']) ? (float)$so['price'] : null,
                    'is_active' => true,
                ]);
            } catch (\Throwable $e) { /* silent create offer failure */ }
        }

        // Create rent offer if available for rent
        if ($request->input('available_for_rent')) {
            $ro = $request->input('rent_offer', []);
            try {
                Offer::create([
                    'product_id' => $product->id,
                    'facility_id' => $facility->id,
                    'created_by' => Auth::id(),
                    'offer_type' => $ro['period'] ?? 'rent_monthly',
                    'price' => isset($ro['price']) ? (float)$ro['price'] : null,
                    'deposit_amount' => isset($ro['deposit']) ? (float)$ro['deposit'] : null,
                    'is_active' => true,
                    'valid_from' => $ro['valid_from'] ?? null,
                    'valid_to' => $ro['valid_to'] ?? null,
                ]);
            } catch (\Throwable $e) { /* silent create offer failure */ }
        }

        return redirect()->route('facility.products.index')
            ->with('success', __('تم إنشاء المنتج بنجاح'));
    }

    /**
     * عرض صفحة تعديل المنتج
     */
    public function edit(Product $product)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', __('غير مصرح لك بتعديل هذا المنتج'));
        }

        $product->load(['category', 'city', 'statuses', 'features', 'attributes']);
        $categories = Category::with('translations')->get();
        $statuses = Status::all();
        $attributes = Attribute::all();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        
        // Get attribute values directly from the pivot table
        $attributeValues = \App\Models\ProductAttributeValue::where('product_id', $product->id)
            ->pluck('value', 'attribute_id')
            ->toArray();

           
        // Get attributes grouped by category for JavaScript - only those used in product_attribute_values
        $attributesByCategory = Attribute::with('translations')
            ->whereNotNull('category_id')
            ->whereIn('id', function($query) {
                $query->select('attribute_id')
                      ->from('product_attribute_values')
                      ->distinct();
            })
            ->get()
            ->groupBy('category_id')
            ->map(function ($attributes) {
                return $attributes->map(function ($attribute) {
                    return [
                        'id' => $attribute->id,
                        'name' => $attribute->getTranslatedName(app()->getLocale()),
                        'icon' => $attribute->icon,
                        'required' => $attribute->required,
                    ];
                });
            });
        // Get features grouped by category for JavaScript - only those used in product_feature
        $featuresByCategory = \App\Models\Feature::with('translations')
            ->whereNotNull('category_id')
            ->whereIn('id', function($query) {
                $query->select('feature_id')
                      ->from('product_feature')
                      ->distinct();
            })
            ->get()
            ->groupBy('category_id')
            ->map(function ($features) {
                return $features->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'name' => $feature->getTranslatedName(app()->getLocale()),
                        'icon' => $feature->icon,
                    ];
                });
            });

        // Prepare categories options for the select dropdown
        $categoryOptions = $categories->mapWithKeys(function ($category) {
            return [$category->id => $category->getTranslatedName()];
        })->toArray();

        // مستخدمون يمكن تعيينهم كمالك/موظف مسؤول (مستخلصون من منتجات هذه المنشأة أو من المنتج نفسه)
        $userIdsFromProducts = $facility->products()
            ->pluck('owner_user_id', 'seller_user_id')
            ->flatten()
            ->filter()
            ->unique()
            ->values();

        // تأكد من تضمين مالك/موظف المنتج الحالي حتى لو لم يكن في القائمة المستخلصة
        if ($product->owner_user_id) {
            $userIdsFromProducts->push($product->owner_user_id);
        }
        if ($product->seller_user_id) {
            $userIdsFromProducts->push($product->seller_user_id);
        }

        $userIdsFromProducts = $userIdsFromProducts->unique()->values();

        $userOptions = $userIdsFromProducts->isNotEmpty()
            ? User::whereIn('id', $userIdsFromProducts)->pluck('name', 'id')->toArray()
            : [];

        return view('facility.products.edit', compact(
            'product', 'categories', 'statuses', 'attributes', 'cities',
            'categoryOptions', 'attributeValues', 'attributesByCategory', 'featuresByCategory',
            'userOptions'
        ));
    }

    /**
     * تحديث بيانات المنتج
     */
    public function update(Request $request, Product $product)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', __('غير مصرح لك بتعديل هذا المنتج'));
        }

        $request->validate([
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
            'street_id' => 'nullable|exists:streets,id',
            'status_id' => 'required|exists:statuses,id',
            'package_id' => 'nullable|exists:packages,id',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'nullable|string|distinct',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_gallery' => 'nullable|array',
            'image_gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,webm',
            'video_url' => 'nullable|url',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
            'attributes' => 'array',
            'attributes.*.attribute_id' => 'exists:attributes,id',
            'attributes.*.value' => 'nullable',
            // Offer availability flags
            'available_for_sale' => 'boolean',
            'available_for_rent' => 'boolean',
            // Sale offer validation (conditional)
            'sale_offer.price' => 'exclude_unless:available_for_sale,1|required|numeric|min:0',
            // Rent offer validation (conditional)
            'rent_offer.price' => 'exclude_unless:available_for_rent,1|required|numeric|min:0',
            'rent_offer.period' => 'exclude_unless:available_for_rent,1|required|in:rent_daily,rent_monthly,rent_yearly',
            'rent_offer.deposit' => 'exclude_unless:available_for_rent,1|nullable|numeric|min:0',
        ]);

        // Custom validation for required attributes only
        if ($request->has('attributes')) {
            $requiredAttributeModels = \App\Models\Attribute::where('required', true)
                ->whereIn('id', collect($request->attributes)->pluck('attribute_id'))
                ->with('translations')
                ->get()
                ->keyBy('id');
            $requiredAttributeIds = $requiredAttributeModels->keys()->toArray();

            foreach ($request->attributes as $index => $attribute) {
                $attributeId = $attribute['attribute_id'];
                $value = $attribute['value'];

                // Check if required attribute has value
                if (in_array($attributeId, $requiredAttributeIds)) {
                    if (is_null($value) || $value === '') {
                        $attributeName = $requiredAttributeModels->get($attributeId)?->getTranslatedName() ?? 'الخاصية';
                        return redirect()->back()
                            ->withErrors(["attributes.{$index}.value" => "{$attributeName} مطلوب."])
                            ->withInput();
                    }
                }
            }
        }

        $productData = $request->except(['main_image', 'image_gallery', 'video', 'features', 'attributes']);

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

        // مزامنة سجلّ الحالات اختيارياً
        if (env('PRODUCT_STATUS_SYNC_ENABLED', true) && isset($productData['status_id'])) {
            try {
                $product->statuses()->attach($productData['status_id'], [
                    'notes' => 'تحديث الحالة (منشأة)',
                    'user_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) { /* silent */ }
        }

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

        $this->syncLegacyAttributes($request, $product);

        // Upsert rent offer if available_for_rent is enabled
        if ($request->filled('rent_offer')) {
            $ro = $request->input('rent_offer');
            $offerType = $ro['period'] ?? 'rent_monthly';
            $existing = $product->offers()->where('offer_type', $offerType)->first();
            $payload = [
                'facility_id' => $facility->id,
                'created_by' => Auth::id(),
                'price' => isset($ro['price']) ? (float)$ro['price'] : null,
                'deposit_amount' => isset($ro['deposit']) ? (float)$ro['deposit'] : null,
                'is_active' => true,
                'valid_from' => $ro['valid_from'] ?? null,
                'valid_to' => $ro['valid_to'] ?? null,
            ];
            if ($existing) {
                $existing->update($payload);
            } else {
                $product->offers()->create(array_merge($payload, [
                    'offer_type' => $offerType,
                ]));
            }
        }

        return redirect()->route('facility.products.index')
            ->with('success', __('تم تحديث بيانات المنتج بنجاح'));
    }

    /**
     * حذف المنتج
     */
    public function destroy(Product $product)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', __('غير مصرح لك بحذف هذا المنتج'));
        }

        // حذف الصورة الرئيسية
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        $product->delete();

        return redirect()->route('facility.products.index')
            ->with('success', __('تم حذف المنتج بنجاح'));
    }

    /**
     * تفعيل/إلغاء تفعيل المنتج
     */
    public function toggleStatus(Product $product)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', __('غير مصرح لك بتعديل هذا المنتج'));
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
        $facility = Auth::user()->mainFacility();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', __('غير مصرح لك بتعديل هذا المنتج'));
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
        $facility = Auth::user()->mainFacility();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', __('غير مصرح لك بعرض هذا المنتج'));
        }

        $product->load(['category', 'statuses', 'features', 'attributes.translations']);

        return view('facility.products.show', compact('product'));
    }

    /**
     * عرض صفحة دورة حياة المنتج (من لحظة الإنشاء حتى العروض والحجوزات والعقود)
     */
    public function lifecycle(Product $product)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', __('غير مصرح لك بعرض هذا المنتج'));
        }

        $product->load([
            'category',
            'statuses',
            'features',
            'attributes.translations',
            'offers',
            'contracts',
            'bookings',
        ]);

        // تقسيم العروض إلى بيع / إيجار لاستخدامها في الواجهة
        $saleOffers = $product->offers->where('offer_type', 'sale');
        $rentOffers = $product->offers->filter(function ($offer) {
            return $offer->offer_type && str_starts_with($offer->offer_type, 'rent_');
        });

        $lifecycleStages = collect();
        if ($product->category) {
            $lifecycleStages = CategoryProductLifecycleStage::with('translations')
                ->where(function ($q) use ($product) {
                    $q->whereNull('category_id')
                      ->orWhere('category_id', $product->category_id);
                })
                ->orderBy('order')
                ->get();
        }

        return view('facility.products.lifecycle', [
            'product' => $product,
            'saleOffers' => $saleOffers,
            'rentOffers' => $rentOffers,
            'contracts' => $product->contracts,
            'bookings' => $product->bookings,
            'lifecycleStages' => $lifecycleStages,
        ]);
    }

    /**
     * عرض منتجات تصنيف معيّن في صفحة خاصة (جدول + شبكة) مع فلاتر حسب خصائص التصنيف
     */
    public function categoryProducts(Request $request, Category $category)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        // تثبيت التصنيف في الفلاتر
        $request->merge([
            'category_id' => $category->id,
        ]);

        $query = $facility->products()
            ->where('category_id', $category->id)
            ->with(['category', 'statuses', 'features', 'attributes', 'seller', 'owner'])
            ->withCount(['bookings', 'saleOffers']);

        // فلاتر أساسية كما في index
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('seller_user_id')) {
            $query->where('seller_user_id', $request->seller_user_id);
        }

        if ($request->filled('owner_user_id')) {
            $query->where('owner_user_id', $request->owner_user_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('address', 'like', "%{$search}%")
                  ->orWhere('additional_info', 'like', "%{$search}%");
            });
        }

        // فلترة حسب خصائص التصنيف حسب نوع الخاصية
        // نصية: attr[attribute_id]
        $attributeTextFilters = $request->input('attr', []);
        if (is_array($attributeTextFilters)) {
            foreach ($attributeTextFilters as $attributeId => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $query->whereHas('attributes', function ($q) use ($attributeId, $value) {
                    $q->where('attributes.id', $attributeId)
                      ->where('product_attribute_values.value', 'like', "%{$value}%");
                });
            }
        }

        // رقمية: attr_min[attribute_id] / attr_max[attribute_id]
        $attributeMinFilters = $request->input('attr_min', []);
        $attributeMaxFilters = $request->input('attr_max', []);

        if (is_array($attributeMinFilters) || is_array($attributeMaxFilters)) {
            $allNumericAttributeIds = array_unique(array_merge(
                array_keys(is_array($attributeMinFilters) ? $attributeMinFilters : []),
                array_keys(is_array($attributeMaxFilters) ? $attributeMaxFilters : [])
            ));

            foreach ($allNumericAttributeIds as $attributeId) {
                $min = $attributeMinFilters[$attributeId] ?? null;
                $max = $attributeMaxFilters[$attributeId] ?? null;

                if (($min === null || $min === '') && ($max === null || $max === '')) {
                    continue;
                }

                $query->whereHas('attributes', function ($q) use ($attributeId, $min, $max) {
                    $q->where('attributes.id', $attributeId);

                    if ($min !== null && $min !== '') {
                        $q->where('product_attribute_values.value', '>=', $min);
                    }

                    if ($max !== null && $max !== '') {
                        $q->where('product_attribute_values.value', '<=', $max);
                    }
                });
            }
        }

        // بولياني: attr_bool[attribute_id] => 1/0
        $attributeBoolFilters = $request->input('attr_bool', []);
        if (is_array($attributeBoolFilters)) {
            foreach ($attributeBoolFilters as $attributeId => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $query->whereHas('attributes', function ($q) use ($attributeId, $value) {
                    $q->where('attributes.id', $attributeId)
                      ->where('product_attribute_values.value', (string) $value);
                });
            }
        }

        // خصائص هذا التصنيف لاستخدامها في الفلاتر
        $categoryAttributes = Attribute::with('translations')
            ->where(function ($q) use ($category) {
                $q->where('category_id', $category->id)
                  ->orWhereNull('category_id');
            })
            ->get();

        $products   = $query->paginate(15);
        $categories = Category::with('translations')->get();
        $statuses   = Status::all();

        return view('facility.products.index', [
            'products'           => $products,
            'categories'         => $categories,
            'statuses'           => $statuses,
            'sellers'            => collect(),
            'owners'             => collect(),
            'currentCategory'    => $category,
            'categoryAttributes' => $categoryAttributes,
        ]);
    }

    private function syncLegacyAttributes(Request $request, Product $product)
    {
        $legacyMap = [
            'bedrooms' => 'bedrooms',
            'bathrooms' => 'bathrooms',
            'area' => 'area',
            'floor_number' => 'floor_number',
            'total_floors' => 'total_floors',
            'parking_spaces' => 'parking_spaces',
            'furnished' => 'furnished',
        ];

        foreach ($legacyMap as $field => $key) {
            if (! $request->has($field)) {
                continue;
            }

            $attribute = \App\Models\Attribute::where('key', $key)
                ->where(function ($query) use ($product) {
                    $query->where('category_id', $product->category_id)
                          ->orWhereNull('category_id');
                })
                ->first();

            if ($attribute) {
                \DB::table('product_attribute_values')->updateOrInsert(
                    ['product_id' => $product->id, 'attribute_id' => $attribute->id],
                    ['value' => $request->input($field), 'updated_at' => now()]
                );
            }
        }
    }

    /**
     * الوحدات = العقارات الطرفية (ليس لها أبناء)
     */
    private function facilityUnits(Facility $facility)
    {
        $all = $facility->products()->with('translations')->get(['id', 'parent_id', 'facility_id', 'category_id', 'is_active']);
        $parentIds = $all->pluck('parent_id')->filter()->unique();
        return $all->reject(fn ($p) => $parentIds->contains($p->id))->values();
    }

    private function activeContractsFor($facility, $unitIds)
    {
        $today = now()->toDateString();
        return \App\Models\Contract::where('facility_id', $facility->id)
            ->where('status', 'active')
            ->whereIn('product_id', $unitIds)
            ->whereDate('start_date', '<=', $today)
            ->where(fn ($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today))
            ->with(['tenant', 'user'])
            ->get()
            ->keyBy('product_id');
    }

    /**
     * الوحدات الشاغرة (بدون عقد نشط)
     */
    public function vacant()
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $units = $this->facilityUnits($facility);
        $occupiedIds = $this->activeContractsFor($facility, $units->pluck('id'))->keys();

        $vacant = Product::with(['translations', 'parent.translations', 'parent.parent.translations', 'category'])
            ->whereIn('id', $units->pluck('id')->diff($occupiedIds))
            ->get();

        $offeredIds = Offer::where('facility_id', $facility->id)->where('is_active', true)
            ->pluck('product_id')->unique();

        return view('facility.products.vacant', compact('vacant', 'offeredIds'));
    }

    /**
     * خارطة الإشغال لوحدات أصل معيّن
     */
    public function occupancyMap(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $all = $facility->products()->with('translations')->get(['id', 'parent_id', 'facility_id']);
        $parentIds = $all->pluck('parent_id')->filter()->unique();
        $parents = $all->filter(fn ($p) => $parentIds->contains($p->id))->values();

        $selected = $request->filled('parent_id') ? $parents->firstWhere('id', (int) $request->parent_id) : $parents->first();

        $tiles = collect();
        if ($selected) {
            $units = $all->where('parent_id', $selected->id)->values();
            $contracts = $this->activeContractsFor($facility, $units->pluck('id'));
            $maintenanceUnitIds = MaintenanceRequest::where('facility_id', $facility->id)
                ->whereIn('product_id', $units->pluck('id'))
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->pluck('product_id')->unique();

            $arrearsContractIds = Invoice::where('facility_id', $facility->id)
                ->where('due_date', '<', now()->toDateString())
                ->where('remaining_amount', '>', 0)
                ->whereNotIn('status', ['paid', 'cancelled'])
                ->pluck('contract_id')->unique();

            $tiles = $units->map(function ($unit) use ($contracts, $maintenanceUnitIds, $arrearsContractIds) {
                $contract = $contracts->get($unit->id);
                $state = $contract ? ($arrearsContractIds->contains($contract->id) ? 'overdue' : 'occupied') : 'vacant';
                return (object) [
                    'unit' => $unit,
                    'state' => $state,
                    'contract' => $contract,
                    'maintenance' => $maintenanceUnitIds->contains($unit->id),
                ];
            });
        }

        return view('facility.products.occupancy_map', compact('parents', 'selected', 'tiles'));
    }

    /**
     * الجدول الزمني لإشغال وحدات أصل معيّن — 12 شهراً
     */
    public function timeline(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $all = $facility->products()->with('translations')->get(['id', 'parent_id', 'facility_id']);
        $parentIds = $all->pluck('parent_id')->filter()->unique();
        $parents = $all->filter(fn ($p) => $parentIds->contains($p->id))->values();

        $selected = $request->filled('parent_id') ? $parents->firstWhere('id', (int) $request->parent_id) : $parents->first();

        $windowStart = now()->startOfMonth();
        $windowEnd = now()->addMonths(11)->endOfMonth();
        $windowDays = $windowStart->diffInDays($windowEnd) ?: 1;

        $rows = collect();
        if ($selected) {
            $units = $all->where('parent_id', $selected->id)->values();
            $contracts = \App\Models\Contract::where('facility_id', $facility->id)
                ->whereIn('product_id', $units->pluck('id'))
                ->whereDate('start_date', '<=', $windowEnd)
                ->where(fn ($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $windowStart))
                ->with(['tenant', 'user'])
                ->orderBy('start_date')
                ->get()
                ->groupBy('product_id');

            $rows = $units->map(function ($unit) use ($contracts, $windowStart, $windowEnd, $windowDays) {
                $segments = collect();
                $cursor = $windowStart->copy();

                foreach ($contracts->get($unit->id, collect()) as $contract) {
                    $start = $contract->start_date->copy()->max($windowStart);
                    $end = ($contract->end_date?->copy() ?? $windowEnd->copy())->min($windowEnd);

                    if ($start->gt($cursor)) {
                        $segments->push((object) [
                            'type' => 'gap',
                            'width' => round($cursor->diffInDays($start) / $windowDays * 100, 2),
                        ]);
                    }
                    if ($end->gte($start)) {
                        $segments->push((object) [
                            'type' => 'contract',
                            'width' => round($start->diffInDays($end->copy()->addDay()) / $windowDays * 100, 2),
                            'contract' => $contract,
                            'expiring' => $contract->end_date && $contract->end_date->lte(now()->addDays(60)),
                        ]);
                        $cursor = $end->copy()->addDay()->max($cursor);
                    }
                }
                if ($cursor->lte($windowEnd)) {
                    $segments->push((object) [
                        'type' => 'gap',
                        'width' => round($cursor->diffInDays($windowEnd) / $windowDays * 100, 2),
                    ]);
                }

                return (object) ['unit' => $unit, 'segments' => $segments];
            });
        }

        return view('facility.products.timeline', compact('parents', 'selected', 'rows', 'windowStart', 'windowEnd'));
    }
}
