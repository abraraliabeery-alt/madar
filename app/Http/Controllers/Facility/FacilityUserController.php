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
                ->with('error', 'يجب إنشاء منشأة أولاً');
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
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $query = $facility->users()->with(['roles', 'bank']);

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
                ->with('error', 'يجب إنشاء منشأة أولاً');
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
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // التحقق من أن المستخدم غير موجود في المنشأة
        if ($facility->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                ->with('error', 'المستخدم موجود بالفعل في هذه المنشأة');
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
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    /**
     * إنشاء مستخدم جديد وإضافته للمنشأة
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
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
            ->with('success', 'تم إنشاء المستخدم وإضافته للمنشأة بنجاح');
    }

    /**
     * عرض تفاصيل المستخدم
     */
    public function show(User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        // التحقق من أن المستخدم ينتمي للمنشأة
        if (!$facility->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'غير مسموح بالوصول لهذا المستخدم');
        }

        $user->load(['roles', 'bank']);
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
                ->with('error', 'يجب إنشاء منشأة أولاً');
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
                ->with('error', 'يجب إنشاء منشأة أولاً');
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
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    /**
     * إزالة المستخدم من المنشأة
     */
    public function removeFromFacility(User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        // التحقق من أن المستخدم ينتمي للمنشأة
        if (!$facility->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'غير مسموح بالوصول لهذا المستخدم');
        }

        // منع إزالة مالك المنشأة
        if ($facility->owner_user_id == $user->id) {
            return redirect()->back()
                ->with('error', 'لا يمكن إزالة مالك المنشأة');
        }

        // حذف الأدوار
        UserFacilityRole::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->delete();

        // إزالة المستخدم من المنشأة
        $facility->users()->detach($user->id);

        return redirect()->route('facility.users.index')
            ->with('success', 'تم إزالة المستخدم من المنشأة بنجاح');
    }

    /**
     * حذف المستخدم نهائياً
     */
    public function destroy(User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        // التحقق من أن المستخدم ينتمي للمنشأة
        if (!$facility->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'غير مسموح بالوصول لهذا المستخدم');
        }

        // منع حذف مالك المنشأة
        if ($facility->owner_user_id == $user->id) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف مالك المنشأة');
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
            ->with('success', 'تم حذف المستخدم نهائياً بنجاح');
    }

    /**
     * تعيين دور للمستخدم
     */
    public function assignRole(Request $request, User $user)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        // التحقق من أن الدور ينتمي للمنشأة
        $role = Role::findOrFail($request->role_id);
        if ($role->facility_id != $facility->id) {
            return redirect()->back()
                ->with('error', 'هذا الدور لا ينتمي لهذه المنشأة');
        }

        // التحقق من عدم وجود الدور مسبقاً
        if (UserFacilityRole::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->where('role_id', $request->role_id)
            ->exists()) {
            return redirect()->back()
                ->with('error', 'المستخدم لديه هذا الدور بالفعل');
        }

        UserFacilityRole::create([
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'role_id' => $request->role_id,
        ]);

        return redirect()->back()
            ->with('success', 'تم تعيين الدور بنجاح');
    }

    /**
     * إلغاء دور من المستخدم
     */
    public function removeRole(User $user, Role $role)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        UserFacilityRole::where('user_id', $user->id)
            ->where('facility_id', $facility->id)
            ->where('role_id', $role->id)
            ->delete();

        return redirect()->back()
            ->with('success', 'تم إلغاء الدور بنجاح');
    }

    /**
     * إحصائيات المستخدمين
     */
    public function statistics()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $stats = [
            'total_users' => $facility->users()->count(),
            'users_by_role' => $facility->users()
                ->join('user_facility_role', 'users.id', '=', 'user_facility_role.user_id')
                ->join('roles', 'user_facility_role.role_id', '=', 'roles.id')
                ->selectRaw('roles.name, COUNT(*) as count')
                ->groupBy('roles.id', 'roles.name')
                ->get(),
            'recent_users' => $facility->users()
                ->with(['roles'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('facility.users.statistics', compact('stats', 'facility'));
    }

    /**
     * تصدير قائمة المستخدمين
     */
    public function export(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $query = $facility->users()->with(['roles', 'bank']);

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
