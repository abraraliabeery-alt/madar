<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Product;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    /**
     * عرض لوحة تحكم المنشأة
     */
    public function dashboard()
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $stats = [
            'total_products' => $facility->products()->count(),
            'total_bookings' => $facility->bookings()->count(),
            'total_contracts' => $facility->contracts()->count(),
            'total_tasks' => $facility->tasks()->count(),
            'total_employees' => $facility->users()->count(),
            'recent_bookings' => $facility->bookings()->with(['user', 'product'])->latest()->take(5)->get(),
            'recent_tasks' => $facility->tasks()->with(['assignedTo'])->latest()->take(5)->get(),
            'pending_bookings' => $facility->bookings()->where('status_id', 1)->count(), // pending status
            'completed_tasks' => $facility->tasks()->where('status_id', 3)->count(), // completed status
        ];

        return view('facility.dashboard', compact('facility', 'stats'));
    }

    /**
     * عرض صفحة إنشاء منشأة
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->facilities()->exists()) {
            return redirect()->route('facility.dashboard')
                ->with('error', 'لديك منشأة بالفعل');
        }

        return view('facility.create');
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
            'category_id' => 'required|exists:categories,id',
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
        ]);

        $facilityData = $request->except(['logo', 'cover_image']);
        $facilityData['owner_user_id'] = Auth::id();
        $facilityData['status_id'] = 1; // pending status

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

        // ربط المستخدم بالمنشأة
        $facility->users()->attach(Auth::id());

        return redirect()->route('facility.dashboard')
            ->with('success', 'تم إنشاء المنشأة بنجاح');
    }

    /**
     * عرض صفحة تعديل المنشأة
     */
    public function edit()
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        return view('facility.edit', compact('facility'));
    }

    /**
     * تحديث بيانات المنشأة
     */
    public function update(Request $request)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
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

        return redirect()->route('facility.dashboard')
            ->with('success', 'تم تحديث بيانات المنشأة بنجاح');
    }

    /**
     * عرض إحصائيات المنشأة
     */
    public function statistics()
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $monthlyStats = [
            'products' => $facility->products()->whereMonth('created_at', now()->month)->count(),
            'bookings' => $facility->bookings()->whereMonth('created_at', now()->month)->count(),
            'contracts' => $facility->contracts()->whereMonth('created_at', now()->month)->count(),
            'revenue' => $facility->bookings()->whereMonth('created_at', now()->month)->sum('total_amount'),
        ];

        return view('facility.statistics', compact('facility', 'monthlyStats'));
    }

    /**
     * عرض إعدادات المنشأة
     */
    public function settings()
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        return view('facility.settings', compact('facility'));
    }

    /**
     * حفظ إعدادات المنشأة
     */
    public function updateSettings(Request $request)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'notification_email' => 'nullable|email',
            'auto_approve_bookings' => 'boolean',
            'booking_advance_days' => 'nullable|integer|min:1',
            'max_booking_duration' => 'nullable|integer|min:1',
        ]);

        $facility->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
