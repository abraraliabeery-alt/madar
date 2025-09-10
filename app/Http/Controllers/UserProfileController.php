<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bank;
use App\Models\Role;
use App\Models\Facility;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * عرض الملف الشخصي
     */
    public function show(User $user)
    {
        $user->load(['roles', 'facilities', 'bank', 'translations']);
        return view('user.profile.show', compact('user'));
    }

    /**
     * عرض صفحة تعديل الملف الشخصي
     */
    public function edit(User $user)
    {
        $user->load(['roles', 'facilities', 'bank', 'translations']);
        $banks = Bank::all();
        $roles = Role::all();
        $facilities = Facility::all();

        return view('user.profile.edit', compact('user', 'banks', 'roles', 'facilities'));
    }

    /**
     * تحديث الملف الشخصي
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|unique:users,phone_number,' . $user->id,
            'bank_id' => 'nullable|exists:banks,id',
            'bank_account' => 'nullable|string',
            'is_multilanguage_enabled' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'notification_push' => 'boolean',
            'notification_frequency' => 'nullable|in:immediate,hourly,daily,weekly',
        ]);

        $userData = $request->except(['avatar']);

        // معالجة الصورة الشخصية
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }

        $user->update($userData);

        return redirect()->back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * تغيير كلمة المرور
     */
    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    /**
     * تحديث الإعدادات
     */
    public function updateSettings(Request $request, User $user)
    {
        $request->validate([
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'notification_push' => 'boolean',
            'notification_frequency' => 'nullable|in:immediate,hourly,daily,weekly',
            'is_multilanguage_enabled' => 'boolean',
        ]);

        $user->update($request->only([
            'notification_email',
            'notification_sms',
            'notification_push',
            'notification_frequency',
            'is_multilanguage_enabled'
        ]));

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }

    /**
     * تحديث الموقع
     */
    public function updateLocation(Request $request, User $user)
    {
        $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'google_maps_url' => 'nullable|url',
        ]);

        $user->update($request->only(['latitude', 'longitude', 'google_maps_url']));

        return redirect()->back()->with('success', 'تم تحديث الموقع بنجاح');
    }

    /**
     * تحديث روابط التواصل الاجتماعي
     */
    public function updateSocialLinks(Request $request, User $user)
    {
        $request->validate([
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
        ]);

        $user->update($request->only([
            'facebook', 'twitter', 'instagram', 'linkedin',
            'snapchat', 'tiktok', 'pinterest', 'youtube',
            'whatsapp_number', 'telegram'
        ]));

        return redirect()->back()->with('success', 'تم تحديث روابط التواصل الاجتماعي بنجاح');
    }

    /**
     * حذف الحساب
     */
    public function destroy(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string',
            'confirmation' => 'required|in:DELETE',
        ]);

        // التحقق من كلمة المرور
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'كلمة المرور غير صحيحة']);
        }

        // حذف الصورة الشخصية
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('home')->with('success', 'تم حذف الحساب بنجاح');
    }

    /**
     * تصدير البيانات الشخصية
     */
    public function export(User $user)
    {
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            'roles' => $user->roles->pluck('name')->toArray(),
            'facilities' => $user->facilities->pluck('name')->toArray(),
            'bank' => $user->bank ? $user->bank->name : null,
            'bank_account' => $user->bank_account,
            'social_links' => [
                'facebook' => $user->facebook,
                'twitter' => $user->twitter,
                'instagram' => $user->instagram,
                'linkedin' => $user->linkedin,
                'snapchat' => $user->snapchat,
                'tiktok' => $user->tiktok,
                'pinterest' => $user->pinterest,
                'youtube' => $user->youtube,
                'whatsapp_number' => $user->whatsapp_number,
                'telegram' => $user->telegram,
            ],
            'location' => [
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'google_maps_url' => $user->google_maps_url,
            ],
            'notifications' => [
                'email' => $user->notification_email,
                'sms' => $user->notification_sms,
                'push' => $user->notification_push,
                'frequency' => $user->notification_frequency,
            ],
        ];

        $filename = 'user_data_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * عرض إحصائيات المستخدم
     */
    public function statistics(User $user)
    {
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'total_contracts' => $user->contracts()->count(),
            'total_products' => $user->products()->count(),
            'total_favorites' => $user->favoriteProducts()->count() + $user->favoriteFacilities()->count(),
            'total_appointments' => $user->appointments()->count(),
            'total_comments' => $user->comments()->count(),
            'recent_activity' => $this->getRecentActivity($user),
            'monthly_stats' => $this->getMonthlyStats($user),
            'role_stats' => $this->getRoleStats($user),
        ];

        return view('user.profile.statistics', compact('user', 'stats'));
    }

    /**
     * الحصول على النشاط الأخير
     */
    private function getRecentActivity(User $user)
    {
        $activities = collect();

        // الحجوزات الأخيرة
        $bookings = $user->bookings()->with('product')->latest()->take(5)->get();
        foreach ($bookings as $booking) {
            $activities->push([
                'type' => 'booking',
                'description' => 'تم إنشاء حجز جديد: ' . ($booking->product->name ?? 'غير محدد'),
                'created_at' => $booking->created_at,
                'icon' => 'calendar-check',
                'color' => 'blue'
            ]);
        }

        // العقود الأخيرة
        $contracts = $user->contracts()->with('product')->latest()->take(5)->get();
        foreach ($contracts as $contract) {
            $activities->push([
                'type' => 'contract',
                'description' => 'تم إنشاء عقد جديد: ' . ($contract->product->name ?? 'غير محدد'),
                'created_at' => $contract->created_at,
                'icon' => 'file-contract',
                'color' => 'green'
            ]);
        }

        // المواعيد الأخيرة
        $appointments = $user->appointments()->with('facility')->latest()->take(5)->get();
        foreach ($appointments as $appointment) {
            $activities->push([
                'type' => 'appointment',
                'description' => 'تم إنشاء موعد جديد: ' . ($appointment->facility->name ?? 'غير محدد'),
                'created_at' => $appointment->created_at,
                'icon' => 'clock',
                'color' => 'purple'
            ]);
        }

        return $activities->sortByDesc('created_at')->take(10);
    }

    /**
     * الحصول على الإحصائيات الشهرية
     */
    private function getMonthlyStats(User $user)
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'month' => $date->format('Y-m'),
                'month_name' => $date->format('M Y'),
                'bookings' => $user->bookings()->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'contracts' => $user->contracts()->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'appointments' => $user->appointments()->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
            ]);
        }

        return $months;
    }

    /**
     * الحصول على إحصائيات الأدوار
     */
    private function getRoleStats(User $user)
    {
        return $user->roles()->withCount(['users'])->get()->map(function ($role) {
            return [
                'name' => $role->name,
                'display_name' => $role->getTranslatedName(),
                'users_count' => $role->users_count,
                'is_active' => $role->is_active,
            ];
        });
    }
}
