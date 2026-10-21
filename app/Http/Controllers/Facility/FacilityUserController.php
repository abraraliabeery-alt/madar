<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Facility;
use App\Models\Product;
use App\Models\Booking;
use App\Models\UserFacilityRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FacilityUserController extends Controller
{
    /**
     * لوحة الموظف الحالي: عرض ملخص عقاراته وأدائه داخل المنشأة
     */
    public function employeeDashboard(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        // التحقق من أن المستخدم جزء من هذه المنشأة
        if (!$facility->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'غير مسموح بالوصول');
        }

        // عقارات الموظف كموظف مسؤول
        $productsQuery = $facility->products()
            ->where('seller_user_id', $user->id)
            ->with(['owner', 'category'])
            ->withCount('bookings');

        $totalProducts = (clone $productsQuery)->count();

        // عقارات تحتاج تحسين باستخدام منطق مبسط:
        // أي عقار بدون صورة رئيسية أو بدون إحداثيات موقع
        $needsAttentionQuery = (clone $productsQuery)->where(function($q) {
            $q->whereNull('main_image')
              ->orWhere('main_image', '=','')
              ->orWhereNull('latitude')
              ->orWhereNull('longitude');
        });

        $needsAttentionCount = (clone $needsAttentionQuery)->count();

        // إجمالي الحجوزات لعقارات الموظف
        // bookings_count هو alias ناتج عن withCount وليست خانة فعلية في قاعدة البيانات،
        // لذلك نجمعه على مستوى الـ Collection بدلاً من SUM في الاستعلام.
        $totalBookings = (int) (clone $productsQuery)->get()->sum('bookings_count');

        // مجموع المشاهدات لعقارات الموظف (إن وجدت)
        $totalViews = (int) (clone $productsQuery)->sum('views_count');

        // تحميل قائمة العقارات للعرض في الجدول
        $products = $productsQuery
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends($request->query());

        $stats = [
            'total_products' => $totalProducts,
            'needs_attention' => $needsAttentionCount,
            'total_bookings' => $totalBookings,
            'total_views' => $totalViews,
        ];

        return view('facility.users.employee-dashboard', compact('facility', 'user', 'stats', 'products'));
    }

    /**
     * عرض قائمة مستخدمي المنشأة
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        $query = $facility->users()->with(['roles']);

        // فلترة حسب الدور
        if ($request->has('role_id') && $request->role_id) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(15);
        $roles = $facility->roles;
        $availableRoles = Role::where('facility_id', $facility->id)->get();

        return view('facility.users.index', compact('users', 'roles', 'availableRoles', 'facility'));
    }

    /**
     * عرض صفحة إضافة مستخدم جديد
     */
    public function create()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        $roles = $facility->roles;
        $availableUsers = User::whereDoesntHave('facilities', function($query) use ($facility) {
            $query->where('facility_id', $facility->id);
        })->get();

        return view('facility.users.create', compact('roles', 'availableUsers', 'facility'));
    }

    /**
     * إضافة مستخدم موجود إلى المنشأة
     */
    public function addExistingUser(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // التحقق من أن المستخدم غير موجود في المنشأة
        if ($facility->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                ->with('error', __('المستخدم موجود بالفعل في هذه المنشأة'));
        }

        // إضافة المستخدم إلى المنشأة
        $facility->users()->attach($user->id);

        // تعيين الدور
        UserFacilityRole::create([
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('facility.users.index')
            ->with('success', __('تم إضافة المستخدم بنجاح'));
    }

    /**
     * إنشاء مستخدم جديد وإضافته للمنشأة
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'bank_account' => 'nullable|string',
            'bank_id' => 'nullable|exists:banks,id',
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'bank_account' => $request->bank_account,
            'bank_id' => $request->bank_id,
            'primary_role' => 'facility_user',
        ]);

        // إضافة المستخدم إلى المنشأة
        $facility->users()->attach($user->id);

        // تعيين الدور
        UserFacilityRole::create([
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('facility.users.index')
            ->with('success', __('تم إنشاء المستخدم وإضافته للمنشأة بنجاح'));
    }

    /**
     * عرض تفاصيل المستخدم
     */
    public function show(User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        // التحقق من أن المستخدم ينتمي للمنشأة
        if (!$facility->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'غير مسموح بالوصول لهذا المستخدم');
        }

        $user->load(['roles']);
        $userRoles = $user->roles()->where('facility_id', $facility->id)->get();

        return view('facility.users.show', compact('user', 'userRoles', 'facility'));
    }

    /**
     * عرض صفحة تعديل المستخدم
     */
    public function edit(User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        // التحقق من أن المستخدم ينتمي للمنشأة
        if (!$facility->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'غير مسموح بالوصول لهذا المستخدم');
        }

        $roles = $facility->roles;
        $userRoles = $user->roles()->where('facility_id', $facility->id)->pluck('role_id')->toArray();

        return view('facility.users.edit', compact('user', 'roles', 'userRoles', 'facility'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        // التحقق من أن المستخدم ينتمي للمنشأة
        if (!$facility->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'غير مسموح بالوصول لهذا المستخدم');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
            'bank_account' => 'nullable|string',
            'bank_id' => 'nullable|exists:banks,id',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'bank_account' => $request->bank_account,
            'bank_id' => $request->bank_id,
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // تحديث الأدوار
        UserFacilityRole::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->delete();

        foreach ($request->role_ids as $roleId) {
            UserFacilityRole::create([
                'user_id' => $user->id,
                'facility_id' => $facility->id,
                'role_id' => $roleId,
            ]);
        }

        return redirect()->route('facility.users.index')
            ->with('success', __('تم تحديث بيانات المستخدم بنجاح'));
    }

    /**
     * إزالة المستخدم من المنشأة
     */
    public function removeFromFacility(User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        // التحقق من أن المستخدم ينتمي للمنشأة
        if (!$facility->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'غير مسموح بالوصول لهذا المستخدم');
        }

        // منع إزالة مالك المنشأة
        if ($facility->owner_user_id == $user->id) {
            return redirect()->back()
                ->with('error', __('لا يمكن إزالة مالك المنشأة'));
        }

        // حذف الأدوار
        UserFacilityRole::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->delete();

        // إزالة المستخدم من المنشأة
        $facility->users()->detach($user->id);

        return redirect()->route('facility.users.index')
            ->with('success', __('تم إزالة المستخدم من المنشأة بنجاح'));
    }

    /**
     * حذف المستخدم نهائياً
     */
    public function destroy(User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        // التحقق من أن المستخدم ينتمي للمنشأة
        if (!$facility->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'غير مسموح بالوصول لهذا المستخدم');
        }

        // منع حذف مالك المنشأة
        if ($facility->owner_user_id == $user->id) {
            return redirect()->back()
                ->with('error', __('لا يمكن حذف مالك المنشأة'));
        }

        // حذف الأدوار
        UserFacilityRole::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->delete();

        // إزالة المستخدم من المنشأة
        $facility->users()->detach($user->id);

        // حذف المستخدم نهائياً
        $user->delete();

        return redirect()->route('facility.users.index')
            ->with('success', __('تم حذف المستخدم نهائياً بنجاح'));
    }

    /**
     * تعيين دور للمستخدم
     */
    public function assignRole(Request $request, User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        // التحقق من أن الدور ينتمي للمنشأة
        $role = Role::findOrFail($request->role_id);
        if ($role->facility_id != $facility->id) {
            return redirect()->back()
                ->with('error', __('هذا الدور لا ينتمي لهذه المنشأة'));
        }

        // التحقق من عدم وجود الدور مسبقاً
        if (UserFacilityRole::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->where('role_id', $request->role_id)
            ->exists()) {
            return redirect()->back()
                ->with('error', __('المستخدم لديه هذا الدور بالفعل'));
        }

        UserFacilityRole::create([
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'role_id' => $request->role_id,
        ]);

        return redirect()->back()
            ->with('success', __('تم تعيين الدور بنجاح'));
    }

    /**
     * إلغاء دور من المستخدم
     */
    public function removeRole(User $user, Role $role)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        UserFacilityRole::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->where('role_id', $role->id)
            ->delete();

        return redirect()->back()
            ->with('success', __('تم إلغاء الدور بنجاح'));
    }

    /**
     * إحصائيات المستخدمين
     */
    public function statistics()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        $totalUsers = $facility->users()->count();
        $verifiedUsers = $facility->users()
            ->where(fn ($q) => $q->whereNotNull('users.email_verified_at')->orWhereNotNull('users.phone_verified_at'))
            ->count();
        $usersWithAvatar = $facility->users()->whereNotNull('users.profile_picture')->count();

        $stats = [
            'total_users' => $totalUsers,
            'active_users' => $facility->users()->where('users.is_active', true)->count(),
            'inactive_users' => $facility->users()->where('users.is_active', false)->count(),
            'new_users_this_month' => $facility->users()->whereMonth('users.created_at', now()->month)
                ->whereYear('users.created_at', now()->year)->count(),
            'verified_users' => $verifiedUsers,
            'unverified_users' => $totalUsers - $verifiedUsers,
            'verified_percentage' => $totalUsers ? round($verifiedUsers / $totalUsers * 100, 1) : 0,
            'unverified_percentage' => $totalUsers ? round(($totalUsers - $verifiedUsers) / $totalUsers * 100, 1) : 0,
            'users_with_avatar' => $usersWithAvatar,
            'avatar_percentage' => $totalUsers ? round($usersWithAvatar / $totalUsers * 100, 1) : 0,
        ];

        $maleCount = $facility->users()->where('users.gender', 'male')->count();
        $femaleCount = $facility->users()->where('users.gender', 'female')->count();
        $genderStats = [
            'male' => $maleCount,
            'female' => $femaleCount,
            'male_percentage' => $totalUsers ? round($maleCount / $totalUsers * 100, 1) : 0,
            'female_percentage' => $totalUsers ? round($femaleCount / $totalUsers * 100, 1) : 0,
        ];

        $roleStats = DB::table('user_facility_role')
            ->join('roles', 'user_facility_role.role_id', '=', 'roles.id')
            ->where('user_facility_role.facility_id', $facility->id)
            ->selectRaw('roles.name, COUNT(*) as users_count')
            ->groupBy('roles.id', 'roles.name')
            ->get()
            ->map(fn ($r) => tap($r, fn ($x) => $x->percentage = $totalUsers ? round($x->users_count / $totalUsers * 100, 1) : 0));

        $recentUsers = $facility->users()->latest('users.created_at')->take(5)->get();

        $monthlyStats = collect(range(0, 5))->map(function ($i) use ($facility) {
            $date = now()->subMonths($i);
            $count = $facility->users()->whereYear('users.created_at', $date->year)
                ->whereMonth('users.created_at', $date->month)->count();
            return ['month_name' => $date->translatedFormat('F'), 'year' => $date->year, 'new_users' => $count];
        })->reverse()->values();
        $monthlyTotal = max($monthlyStats->sum('new_users'), 1);
        $monthlyStats->transform(fn ($m) => $m + ['percentage' => round($m['new_users'] / $monthlyTotal * 100, 1)]);

        return view('facility.users.statistics', compact('stats', 'roleStats', 'recentUsers', 'monthlyStats', 'genderStats', 'facility'));
    }

    /**
     * تصدير قائمة المستخدمين
     */
    public function export(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        $query = $facility->users()->with(['roles']);

        // تطبيق نفس الفلاتر المستخدمة في index
        if ($request->has('role_id') && $request->role_id) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->get();

        // هنا يمكن إضافة منطق التصدير (CSV, Excel, PDF)
        // للبساطة، سنعيد البيانات كـ JSON
        return response()->json($users);
    }
}
