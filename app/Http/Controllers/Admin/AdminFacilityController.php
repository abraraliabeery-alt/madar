<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\User;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminFacilityController extends Controller
{
    /**
     * عرض قائمة المنشآت
     */
    public function index(Request $request)
    {
                    $query = Facility::with(['owner', 'facilityCategory']);

        // فلترة حسب الفئة
        if ($request->has('category_id') && $request->category_id) {
            $query->where('facility_category_id', $request->category_id);
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        $facilities = $query->paginate(15);
        $categories = Category::all();
        $statuses = Status::all();

        return view('admin.facilities.index', compact('facilities', 'categories', 'statuses'));
    }

    /**
     * عرض صفحة إنشاء منشأة جديدة
     */
    public function create()
    {
        $owners = User::whereHas('roles', function ($q) {
            $q->where('name', 'facility_owner');
        })->get();
        $facilityCategories = \App\Models\FacilityCategory::all();
        $statuses = Status::all();

        return view('admin.facilities.create', compact('owners', 'facilityCategories', 'statuses'));
    }

    /**
     * حفظ منشأة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'owner_user_id' => 'required|exists:users,id',
            'facility_category_id' => 'required|exists:facility_categories,id',
            'status_id' => 'required|exists:statuses,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'snapchat' => 'nullable|string',
            'tiktok' => 'nullable|string',
            'pinterest' => 'nullable|url',
            'youtube' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'telegram' => 'nullable|string',
            'working_hours' => 'nullable|string',
            'is_verified' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $facilityData = $request->except(['logo', 'cover_image']);

        // معالجة الشعار
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('facilities/logos', 'public');
            $facilityData['logo'] = $logoPath;
        }

        // معالجة صورة الغلاف
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('facilities/covers', 'public');
            $facilityData['cover_image'] = $coverPath;
        }

        $facility = Facility::create($facilityData);

        return redirect()->route('admin.facilities.index')
            ->with('success', 'تم إنشاء المنشأة بنجاح');
    }

    /**
     * عرض صفحة تعديل المنشأة
     */
    public function edit(Facility $facility)
    {
        $facility->load(['owner', 'facilityCategory', 'statuses']);
        $owners = User::whereHas('roles', function ($q) {
            $q->where('name', 'facility_owner');
        })->get();
        $facilityCategories = \App\Models\FacilityCategory::all();
        $statuses = Status::all();

        return view('admin.facilities.edit', compact('facility', 'owners', 'facilityCategories', 'statuses'));
    }

    /**
     * تحديث بيانات المنشأة
     */
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'owner_user_id' => 'required|exists:users,id',
            'facility_category_id' => 'required|exists:facility_categories,id',
            'status_id' => 'required|exists:statuses,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'snapchat' => 'nullable|string',
            'tiktok' => 'nullable|string',
            'pinterest' => 'nullable|url',
            'youtube' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'telegram' => 'nullable|string',
            'working_hours' => 'nullable|string',
            'is_verified' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $facilityData = $request->except(['logo', 'cover_image']);

        // معالجة الشعار
        if ($request->hasFile('logo')) {
            // حذف الشعار القديم
            if ($facility->logo) {
                Storage::disk('public')->delete($facility->logo);
            }
            $logoPath = $request->file('logo')->store('facilities/logos', 'public');
            $facilityData['logo'] = $logoPath;
        }

        // معالجة صورة الغلاف
        if ($request->hasFile('cover_image')) {
            // حذف صورة الغلاف القديمة
            if ($facility->cover_image) {
                Storage::disk('public')->delete($facility->cover_image);
            }
            $coverPath = $request->file('cover_image')->store('facilities/covers', 'public');
            $facilityData['cover_image'] = $coverPath;
        }

        $facility->update($facilityData);

        return redirect()->route('admin.facilities.index')
            ->with('success', 'تم تحديث بيانات المنشأة بنجاح');
    }

    /**
     * حذف المنشأة
     */
    public function destroy(Facility $facility)
    {
        // حذف الصور
        if ($facility->logo) {
            Storage::disk('public')->delete($facility->logo);
        }
        if ($facility->cover_image) {
            Storage::disk('public')->delete($facility->cover_image);
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('success', 'تم حذف المنشأة بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل المنشأة
     */
    public function toggleStatus(Facility $facility)
    {
        $facility->update(['is_active' => !$facility->is_active]);

        $status = $facility->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return redirect()->back()->with('success', "تم {$status} المنشأة بنجاح");
    }

    /**
     * التحقق من المنشأة
     */
    public function toggleVerification(Facility $facility)
    {
        $facility->update(['is_verified' => !$facility->is_verified]);

        $status = $facility->is_verified ? 'التحقق من' : 'إلغاء التحقق من';
        return redirect()->back()->with('success', "تم {$status} المنشأة بنجاح");
    }

    /**
     * إضافة/إزالة من المميزات
     */
    public function toggleFeatured(Facility $facility)
    {
        $facility->update(['is_featured' => !$facility->is_featured]);

        $status = $facility->is_featured ? 'إضافة' : 'إزالة من';
        return redirect()->back()->with('success', "تم {$status} المميزات بنجاح");
    }

    /**
     * عرض تفاصيل المنشأة
     */
        public function show(Facility $facility)
    {
        $facility->load(['owner', 'category', 'statuses', 'products', 'users', 'bookings']);

        return view('admin.facilities.show', compact('facility'));
    }
}
