<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Facility;
use App\Models\Bank;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'facilities', 'bank']);

        // فلترة حسب الدور
        if ($request->has('role_id') && $request->role_id) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        // فلترة حسب المنشأة
        if ($request->has('facility_id') && $request->facility_id) {
            $query->whereHas('facilities', function ($q) use ($request) {
                $q->where('facilities.id', $request->facility_id);
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
        $roles = Role::all();
        $facilities = Facility::all();

        return view('admin.users.index', compact('users', 'roles', 'facilities'));
    }

    /**
     * عرض صفحة إنشاء مستخدم جديد
     */
    public function create()
    {
        $roles = Role::all();
        $facilities = Facility::all();
        $banks = Bank::all();

        return view('admin.users.create', compact('roles', 'facilities', 'banks'));
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|unique:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'facility_id' => 'nullable|exists:facilities,id',
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
            'customer_banks' => 'sometimes|array',
            'customer_banks.*' => 'exists:banks,id',
        ]);

        // Enforce bank selection if role is bank_employee
        $role = Role::findOrFail($request->role_id);
        if ($role && $role->name === 'bank_employee') {
            $request->validate([
                'bank_id' => 'required|exists:banks,id',
            ]);
        }

        $userData = $request->except(['password', 'password_confirmation', 'avatar']);
        $userData['password'] = Hash::make($request->password);

        // معالجة الصورة الشخصية
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }

        $user = User::create($userData);

        // ربط المستخدم بالدور
        $user->roles()->attach($request->role_id);

        // If role is not bank_employee, ensure bank_id is null
        if ($role->name !== 'bank_employee' && $user->bank_id) {
            $user->bank_id = null;
            $user->save();
        }

        // ربط المستخدم بالمنشأة إذا تم تحديدها
        if ($request->facility_id) {
            $user->facilities()->attach($request->facility_id);
        }

        // ربط المستخدم كبنك عميل (متعدد)
        if ($request->has('customer_banks')) {
            $user->customerBanks()->sync($request->customer_banks);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * عرض صفحة تعديل المستخدم
     */
    public function edit(User $user)
    {
        $user->load(['roles', 'facilities', 'bank', 'customerBanks']);
        $roles = Role::all();
        $facilities = Facility::all();
        $banks = Bank::all();

        return view('admin.users.edit', compact('user', 'roles', 'facilities', 'banks'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'facility_id' => 'nullable|exists:facilities,id',
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
        ]);

        // Enforce bank selection if role is bank_employee
        $role = Role::findOrFail($request->role_id);
        if ($role && $role->name === 'bank_employee') {
            $request->validate([
                'bank_id' => 'required|exists:banks,id',
            ]);
        }

        $userData = $request->except(['password', 'password_confirmation', 'avatar']);

        // تحديث كلمة المرور إذا تم توفيرها
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

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

        // تحديث الدور
        $user->roles()->sync([$request->role_id]);

        // If role is not bank_employee, nullify bank_id
        if ($role->name !== 'bank_employee') {
            if ($user->bank_id) {
                $user->bank_id = null;
                $user->save();
            }
        }

        // تحديث المنشأة
        if ($request->facility_id) {
            $user->facilities()->sync([$request->facility_id]);
        } else {
            $user->facilities()->detach();
        }

        // تحديث بنوك العميل
        if ($request->has('customer_banks')) {
            $user->customerBanks()->sync($request->customer_banks);
        } else {
            $user->customerBanks()->sync([]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    /**
     * حذف المستخدم
     */
    public function destroy(User $user)
    {
        // حذف الصورة الشخصية
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل المستخدم
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return redirect()->back()->with('success', "تم {$status} المستخدم بنجاح");
    }

    /**
     * عرض تفاصيل المستخدم
     */
    public function show(User $user)
    {
        $user->load(['roles', 'facilities', 'bank', 'bookings', 'products']);

        return view('admin.users.show', compact('user'));
    }
}
