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
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Building;
use App\Models\Project;
use App\Models\Package;
use App\Models\Offer;

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
            $locale = app()->getLocale();
            $query->where(function($q) use ($request, $locale) {
                $q->whereHas('translations', function($translationQuery) use ($request, $locale) {
                    $translationQuery->where('locale', $locale)
                        ->where(function($tq) use ($request) {
                            $tq->where('title', 'like', "%{$request->q}%")
                               ->orWhere('description', 'like', "%{$request->q}%");
                        });
                })
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
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('admin.products.index')
                ->with('error', 'لا يوجد منشأة مرتبطة بحسابك');
        }

        $mainCategories = Category::with('translations')->whereNull('parent_id')->get();
        $subCategories = Category::with('translations')->whereNotNull('parent_id')->get();
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

        $storeRoute = route('admin.products.store');
        $indexRoute = route('admin.products.index');
        $suggestPriceRoute = route('admin.products.suggest-price');
        $layout = 'admin.layouts.app';
        $loadTailwind = true;

        return view('products.create', compact(
            'mainCategoryOptions', 'subCategoriesList', 'statuses', 'cities',
            'statusOptions',
            'userOptions', 'storeRoute', 'indexRoute', 'layout', 'loadTailwind', 'suggestPriceRoute'
        ));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('admin.products.index')
                ->with('error', 'لا يوجد منشأة مرتبطة بحسابك');
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
            $attributeId = $attribute['attribute_id'] ?? null;
            $value = $attribute['value'] ?? null;
            $isFile = $requiredAttributeModels->get($attributeId)?->type === 'file';

            // Check if required attribute has value
            if (in_array($attributeId, $requiredAttributeIds)) {
                if (($isFile && !$request->hasFile("attributes.{$index}.value")) || (!$isFile && (is_null($value) || $value === ''))) {
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
                    'notes' => 'تعيين الحالة عند الإنشاء (إدمن)',
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
            foreach ($request->attributes as $index => $attribute) {
                $attributeId = $attribute['attribute_id'] ?? null;
                $value = $attribute['value'] ?? null;
                $attrModel = \App\Models\Attribute::find($attributeId);

                if ($attrModel && $attrModel->type === 'file') {
                    if ($request->hasFile("attributes.{$index}.value")) {
                        $value = $request->file("attributes.{$index}.value")->store('uploads/attributes/files', 'public');
                    } else {
                        $value = null;
                    }
                }

                if ($value !== null && $value !== '') {
                    $product->attributes()->attach($attributeId, [
                        'value' => $value,
                    ]);
                }
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

        return redirect()->route('admin.products.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    /**
     * اقتراح سعر بناءً على متوسط عروض مشابهة
     */
    public function suggestPrice(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'city_id' => 'required|integer|exists:cities,id',
            'offer_type' => 'required|in:sale,rent_daily,rent_monthly,rent_yearly',
        ]);

        $query = Offer::where('offer_type', $data['offer_type'])
            ->whereHas('product', function ($q) use ($data) {
                $q->where('category_id', $data['category_id'])
                  ->where('city_id', $data['city_id']);
            })
            ->where('is_active', true);

        $avg = $query->avg('price');

        return response()->json([
            'price' => $avg ? ceil($avg) : null,
            'count' => $query->count(),
        ]);
    }

    /**
     * عرض صفحة تعديل المنتج
     */
    public function edit(Product $product)
    {
        $product->load(['facility', 'category', 'city', 'statuses', 'features', 'attributes.translations', 'translations']);
        $facilities = Facility::all();
        $categories = Category::all();
        $statuses = Status::all();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $locales = config('locales.available');

        return view('admin.products.edit', compact('product', 'facilities', 'categories', 'statuses', 'cities', 'locales'));
    }

    /**
     * تحديث المنتج
     */
    public function update(Request $request, Product $product)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'address' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'facility_id' => 'required|exists:facilities,id',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'status_id' => 'required|exists:statuses,id',
            'owner_user_id' => 'required|exists:users,id',
            'parking_spaces' => 'nullable|integer|min:0',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
            'attributes' => 'array',
            'attributes.*.value' => 'nullable',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.name' => 'required_with:translations|string|max:255',
            'translations.*.description' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $incomingTranslations = $request->input('translations');
        if (!is_array($incomingTranslations) || !count($incomingTranslations)) {
            $incomingTranslations = [];
            if ($request->filled('title') || $request->filled('description')) {
                $incomingTranslations[] = [
                    'locale' => app()->getLocale(),
                    'name' => $request->input('title'),
                    'description' => $request->input('description'),
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
                ->withErrors(['translations' => 'يجب إدخال اسم المنتج في ترجمة واحدة على الأقل'])
                ->withInput();
        }

        $productData = $request->except(['image', 'features', 'status_id', 'translations', 'title', 'description']);

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

        $incomingLocales = [];
        foreach ($incomingTranslations as $translationData) {
            if (empty($translationData['locale']) || empty($translationData['name'])) {
                continue;
            }

            $incomingLocales[] = $translationData['locale'];

            $product->translations()->updateOrCreate(
                [
                    'locale' => $translationData['locale'],
                ],
                [
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? null,
                ]
            );
        }

        $product->translations()->whereNotIn('locale', $incomingLocales)->delete();

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

        // تحديث الخصائص
        $existingFileValues = [];
        foreach ($product->attributes as $attr) {
            if ($attr->type === 'file' && $attr->pivot->value) {
                $existingFileValues[$attr->id] = $attr->pivot->value;
            }
        }

        $product->attributes()->detach();
        if ($request->has('attributes')) {
            foreach ($request->attributes as $index => $attributeData) {
                $attributeId = $attributeData['attribute_id'] ?? null;
                $value = $attributeData['value'] ?? null;
                $attrModel = \App\Models\Attribute::find($attributeId);

                if ($attrModel && $attrModel->type === 'file') {
                    if ($request->hasFile("attributes.{$index}.value")) {
                        if (isset($existingFileValues[$attributeId])) {
                            Storage::disk('public')->delete($existingFileValues[$attributeId]);
                        }
                        $value = $request->file("attributes.{$index}.value")->store('uploads/attributes/files', 'public');
                    } else {
                        $value = $existingFileValues[$attributeId] ?? null;
                    }
                }

                if ($value !== null && $value !== '') {
                    $product->attributes()->attach($attributeId, [
                        'value' => $value
                    ]);
                }
            }
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
        $product->load(['facility', 'category', 'statuses', 'features', 'attributes.translations', 'bookings']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * عرض الخط الزمني للمنتج (تجميع للأحداث من النظام الحالي بدون تعديل قاعدة البيانات)
     */
    public function timeline(Product $product, Request $request)
    {
        $events = [];

        // 1) أحداث المنتج الأساسية
        $events[] = [
            'type' => 'product_created',
            'title' => 'تم إنشاء المنتج',
            'description' => null,
            'date' => $product->created_at,
            'actor' => null,
            'link' => route('admin.products.show', $product),
            'source' => 'product',
        ];
        if ($product->updated_at && $product->updated_at->ne($product->created_at)) {
            $events[] = [
                'type' => 'product_updated',
                'title' => 'تم تحديث بيانات المنتج',
                'description' => null,
                'date' => $product->updated_at,
                'actor' => null,
                'link' => route('admin.products.edit', $product),
                'source' => 'product',
            ];
        }

        // 2) الحالات (statuses) عبر العلاقة متعددة الأشكال
        try {
            $statuses = $product->statuses()->with('translations')->get();
            foreach ($statuses as $status) {
                $pivot = $status->pivot ?? null;
                $events[] = [
                    'type' => 'status_changed',
                    'title' => 'تغيير حالة المنتج',
                    'description' => $status->name ?? ($status->translations->first()->name ?? null),
                    'date' => $pivot->created_at ?? $status->created_at,
                    'actor' => $pivot->user_id ?? null,
                    'link' => route('admin.products.show', $product),
                    'source' => 'status',
                ];
            }
        } catch (\Throwable $e) {
            // تجاهل في حال عدم توفر البنية
        }

        // 3) العروض Offers
        try {
            $offers = $product->offers()->get();
            foreach ($offers as $offer) {
                $events[] = [
                    'type' => 'offer_created',
                    'title' => 'تم إنشاء عرض',
                    'description' => 'السعر: ' . number_format((float)($offer->price ?? 0)),
                    'date' => $offer->created_at,
                    'actor' => $offer->user_id ?? null,
                    'link' => route('admin.offers.index'),
                    'source' => 'offer',
                ];
                if ($offer->updated_at && $offer->updated_at->ne($offer->created_at)) {
                    $events[] = [
                        'type' => 'offer_updated',
                        'title' => 'تم تحديث العرض',
                        'description' => null,
                        'date' => $offer->updated_at,
                        'actor' => $offer->user_id ?? null,
                        'link' => route('admin.offers.index'),
                        'source' => 'offer',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // تجاهل إذا لم تكن العروض مفعلة
        }

        // 4) الحجوزات Bookings
        try {
            $bookings = $product->bookings()->get();
            foreach ($bookings as $booking) {
                $events[] = [
                    'type' => 'booking_created',
                    'title' => 'تم إنشاء حجز',
                    'description' => 'رقم الحجز: ' . ($booking->id),
                    'date' => $booking->created_at,
                    'actor' => $booking->user_id ?? null,
                    'link' => route('admin.bookings.index'),
                    'source' => 'booking',
                ];
                if ($booking->updated_at && $booking->updated_at->ne($booking->created_at)) {
                    $events[] = [
                        'type' => 'booking_updated',
                        'title' => 'تم تحديث الحجز',
                        'description' => null,
                        'date' => $booking->updated_at,
                        'actor' => $booking->user_id ?? null,
                        'link' => route('admin.bookings.index'),
                        'source' => 'booking',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // تجاهل إذا لم تكن الحجوزات مفعلة
        }

        // 5) العقود Contracts
        try {
            $contracts = $product->contracts()->get();
            foreach ($contracts as $contract) {
                $events[] = [
                    'type' => 'contract_created',
                    'title' => 'تم إنشاء عقد',
                    'description' => 'رقم العقد: ' . ($contract->id),
                    'date' => $contract->created_at,
                    'actor' => $contract->user_id ?? null,
                    'link' => route('admin.contracts.index'),
                    'source' => 'contract',
                ];
                if ($contract->updated_at && $contract->updated_at->ne($contract->created_at)) {
                    $events[] = [
                        'type' => 'contract_updated',
                        'title' => 'تم تحديث العقد',
                        'description' => null,
                        'date' => $contract->updated_at,
                        'actor' => $contract->user_id ?? null,
                        'link' => route('admin.contracts.index'),
                        'source' => 'contract',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // تجاهل إذا لم تكن العقود مفعلة
        }

        // 6) التعليقات Comments على المنتج (إن وجدت)
        try {
            $comments = $product->comments()->get();
            foreach ($comments as $comment) {
                $events[] = [
                    'type' => 'comment_added',
                    'title' => 'تعليق جديد على المنتج',
                    'description' => mb_strimwidth((string)($comment->content ?? ''), 0, 120, '...'),
                    'date' => $comment->created_at,
                    'actor' => $comment->user_id ?? null,
                    'link' => route('admin.products.show', $product) . '#comments',
                    'source' => 'comment',
                ];
            }
        } catch (\Throwable $e) {
            // تجاهل إن لم تكن خاصية التعليقات متاحة
        }

        // فرز الأحداث زمنيًا (الأحدث أولاً)
        usort($events, function ($a, $b) {
            $da = $a['date'] ? strtotime((string)$a['date']) : 0;
            $db = $b['date'] ? strtotime((string)$b['date']) : 0;
            return $db <=> $da;
        });

        // فلاتر بسيطة عبر الاستعلام (type, source, from/to)
        $type = $request->get('type');
        $source = $request->get('source');
        $from = $request->get('from');
        $to = $request->get('to');
        if ($type) {
            $events = array_values(array_filter($events, fn($e) => $e['type'] === $type));
        }
        if ($source) {
            $events = array_values(array_filter($events, fn($e) => $e['source'] === $source));
        }
        if ($from) {
            $fromTs = strtotime($from . ' 00:00:00');
            $events = array_values(array_filter($events, fn($e) => $e['date'] && strtotime((string)$e['date']) >= $fromTs));
        }
        if ($to) {
            $toTs = strtotime($to . ' 23:59:59');
            $events = array_values(array_filter($events, fn($e) => $e['date'] && strtotime((string)$e['date']) <= $toTs));
        }

        // حل أسماء المنفذين (Actors)
        $actorIds = collect($events)->pluck('actor')->filter()->unique()->values();
        $actors = $actorIds->isNotEmpty() ? User::whereIn('id', $actorIds)->pluck('name', 'id') : collect();
        foreach ($events as &$ev) {
            $ev['actor_name'] = $ev['actor'] && isset($actors[$ev['actor']]) ? $actors[$ev['actor']] : null;
        }
        unset($ev);

        return view('admin.products.timeline', [
            'product' => $product,
            'events' => $events,
            'filters' => [
                'type' => $type,
                'source' => $source,
                'from' => $from,
                'to' => $to,
            ],
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
}
