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
        $facilities = Facility::all();
        $categories = Category::all();
        $statuses = Status::all();
        $cities = City::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('facilities', 'categories', 'statuses', 'cities'));
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
            'attributes.*.value' => 'nullable|string',
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

        // ربط الخصائص
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attributeId => $attributeData) {
                if (!empty($attributeData['value'])) {
                    $product->attributes()->attach($attributeId, [
                        'value' => $attributeData['value']
                    ]);
                }
            }
        }



        return redirect()->route('admin.products.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    /**
     * عرض صفحة تعديل المنتج
     */
    public function edit(Product $product)
    {
        $product->load(['facility', 'category', 'city', 'statuses', 'features', 'attributes.translations']);
        $facilities = Facility::all();
        $categories = Category::all();
        $statuses = Status::all();
        $cities = City::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'facilities', 'categories', 'statuses', 'cities'));
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
            'attributes.*.value' => 'nullable|string',
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

        // تحديث الخصائص
        $product->attributes()->detach();
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attributeId => $attributeData) {
                if (!empty($attributeData['value'])) {
                    $product->attributes()->attach($attributeId, [
                        'value' => $attributeData['value']
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
}
