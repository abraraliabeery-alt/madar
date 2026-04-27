<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Building;
use App\Models\Project;
use App\Models\Package;
use App\Models\Feature;
use App\Models\Attribute;
use App\Models\City;
use App\Models\Offer;
use App\Models\CategoryProductLifecycleStage;
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
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $categories = Category::with('translations')->get();
        $statuses = Status::with('translations')->active()->ordered()->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $buildings = Building::with('translations')->get();
        $projects = Project::with('translations')->get();
        $packages = Package::with('translations')->get();

        // Prepare categories options for the select dropdown
        $categoryOptions = $categories->mapWithKeys(function ($category) {
            return [$category->id => $category->getTranslatedName()];
        })->toArray();

        // Prepare translated statuses options
        $statusOptions = $statuses->mapWithKeys(function ($status) {
            return [$status->id => $status->getTranslatedName(app()->getLocale())];
        });

        // Prepare building, project, and package options using translations or fallback to ID
        $locale = app()->getLocale();
        $buildingOptions = $buildings->mapWithKeys(function ($b) use ($locale) {
            $label = optional($b->translations->firstWhere('locale', $locale))->name ?? ('#'.$b->id);
            return [$b->id => $label];
        })->toArray();

        $projectOptions = $projects->mapWithKeys(function ($p) use ($locale) {
            $label = optional($p->translations->firstWhere('locale', $locale))->name ?? ('#'.$p->id);
            return [$p->id => $label];
        })->toArray();

        $packageOptions = $packages->mapWithKeys(function ($pk) use ($locale) {
            $label = optional($pk->translations->firstWhere('locale', $locale))->name ?? ('#'.$pk->id);
            return [$pk->id => $label];
        })->toArray();

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

        return view('facility.products.create', compact(
            'categories', 'statuses', 'cities',
            'categoryOptions', 'statusOptions',
            'buildingOptions', 'projectOptions', 'packageOptions',
            'userOptions'
        ));
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
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
            'street_id' => 'nullable|exists:streets,id',
            'status_id' => 'required|exists:statuses,id',
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
            'attributes.*.value' => 'nullable',
            // Rent offer validation (conditional)
            'rent_offer.price' => 'required_if:available_for_rent,1|nullable|numeric|min:0',
            'rent_offer.period' => 'required_if:available_for_rent,1|nullable|in:rent_daily,rent_monthly,rent_yearly',
            'rent_offer.deposit' => 'nullable|numeric|min:0',
            'rent_offer.valid_from' => 'nullable|date',
            'rent_offer.valid_to' => 'nullable|date|after_or_equal:rent_offer.valid_from',
            'owner_user_id' => 'nullable|exists:users,id',
            'seller_user_id' => 'nullable|exists:users,id',
        ]);

        // Custom validation for required attributes only
        if ($request->has('attributes')) {
            $requiredAttributes = \App\Models\Attribute::where('required', true)
                ->whereIn('id', collect($request->attributes)->pluck('attribute_id'))
                ->pluck('id')
                ->toArray();

            foreach ($request->attributes as $index => $attribute) {
                $attributeId = $attribute['attribute_id'];
                $value = $attribute['value'];

                // Check if required attribute has value
                if (in_array($attributeId, $requiredAttributes)) {
                    if (empty($value)) {
                        return redirect()->back()
                            ->withErrors(["attributes.{$index}.value" => 'This attribute is required.'])
                            ->withInput();
                    }
                }
            }
        }

        $productData = $request->except(['main_image', 'features', 'attributes']);
        $productData['facility_id'] = $facility->id;

        // معالجة الصورة الرئيسية
        if ($request->hasFile('main_image')) {
            $imagePath = $request->file('main_image')->store('uploads/products/images', 'public');
            $productData['main_image'] = $imagePath;
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

        // Create rent offer if available_for_rent is enabled and rent_offer data provided
        if ($request->boolean('available_for_rent') && $request->filled('rent_offer')) {
            $ro = $request->input('rent_offer');
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
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', 'غير مصرح لك بتعديل هذا المنتج');
        }

        $request->validate([
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
            'street_id' => 'nullable|exists:streets,id',
            'status_id' => 'required|exists:statuses,id',
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
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
            'attributes' => 'array',
            'attributes.*.attribute_id' => 'exists:attributes,id',
            'attributes.*.value' => 'nullable',
            // Rent offer validation (conditional)
            'rent_offer.price' => 'required_if:available_for_rent,1|nullable|numeric|min:0',
            'rent_offer.period' => 'required_if:available_for_rent,1|nullable|in:rent_daily,rent_monthly,rent_yearly',
            'rent_offer.deposit' => 'nullable|numeric|min:0',
            'rent_offer.valid_from' => 'nullable|date',
            'rent_offer.valid_to' => 'nullable|date|after_or_equal:rent_offer.valid_from',
        ]);

        // Custom validation for required attributes only
        if ($request->has('attributes')) {
            $requiredAttributes = \App\Models\Attribute::where('required', true)
                ->whereIn('id', collect($request->attributes)->pluck('attribute_id'))
                ->pluck('id')
                ->toArray();

            foreach ($request->attributes as $index => $attribute) {
                $attributeId = $attribute['attribute_id'];
                $value = $attribute['value'];

                // Check if required attribute has value
                if (in_array($attributeId, $requiredAttributes)) {
                    if (empty($value)) {
                        return redirect()->back()
                            ->withErrors(["attributes.{$index}.value" => 'This attribute is required.'])
                            ->withInput();
                    }
                }
            }
        }

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

        // Upsert rent offer if available_for_rent is enabled
        if ($request->boolean('available_for_rent') && $request->filled('rent_offer')) {
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

    /**
     * عرض صفحة دورة حياة المنتج (من لحظة الإنشاء حتى العروض والحجوزات والعقود)
     */
    public function lifecycle(Product $product)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $product->facility_id !== $facility->id) {
            return redirect()->route('facility.products.index')
                ->with('error', 'غير مصرح لك بعرض هذا المنتج');
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
        $facility = Auth::user()->facilities()->first();

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
}
