<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // Get user's primary role and additional roles
        $primaryRole = $user->primary_role;
        $userRoles = $user->roles()->with('translations')->get();

        return view('profile.edit', compact('user', 'primaryRole', 'userRoles'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $primaryRole = $user->primary_role;

        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

        // Add role-specific validation rules
        if ($primaryRole === 'facility' || $user->hasRole('facility')) {
            $rules = array_merge($rules, [
                'bank_account' => ['nullable', 'string', 'max:255'],
                'bank_id' => ['nullable', 'exists:banks,id'],
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'google_maps_url' => ['nullable', 'url'],
            ]);
        }

        if ($primaryRole === 'admin' || $user->hasRole('admin')) {
            $rules = array_merge($rules, [
                'notification_email' => ['boolean'],
                'notification_sms' => ['boolean'],
                'notification_push' => ['boolean'],
                'notification_frequency' => ['nullable', 'string', 'in:daily,weekly,monthly'],
            ]);
        }

        $request->validate($rules);

        $data = $request->only([
            'name', 'email', 'phone_number', 'bank_account', 'bank_id',
            'latitude', 'longitude', 'google_maps_url', 'notification_email',
            'notification_sms', 'notification_push', 'notification_frequency'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('avatars'), $avatarName);
            $data['avatar'] = 'avatars/' . $avatarName;
        }

        // Handle social media links for all users
        $socialFields = ['facebook', 'twitter', 'instagram', 'linkedin', 'snapchat', 'tiktok', 'pinterest', 'youtube', 'whatsapp_number', 'telegram'];
        foreach ($socialFields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        $user->update($data);

        return redirect()->back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    /**
     * Show profile based on user type
     */
    public function show()
    {
        $user = Auth::user();
        $primaryRole = $user->primary_role;

        // Redirect to role-specific profile if available
        if ($primaryRole === 'admin' && route('admin.profile')) {
            return redirect()->route('admin.profile');
        } elseif ($primaryRole === 'facility' && route('facility.profile')) {
            return redirect()->route('facility.profile');
        } elseif ($primaryRole === 'client' && route('client.profile')) {
            return redirect()->route('client.profile');
        }

        // Fallback to general profile
        return view('profile.show', compact('user', 'primaryRole'));
    }

    /**
     * Show public profile - accessible by anyone
     */
    public function publicProfile($id)
    {
        $user = User::findOrFail($id);
        $primaryRole = $user->primary_role;

        // Get user's products if they have any
        $products = $user->products()->where('is_active', true)->take(6)->get();

        // Get user's facilities if they have any
        $facilities = $user->facilities()->take(6)->get();

        // Get user's role information
        $userRoles = $user->roles()->with('translations')->get();

        return view('profile.public', compact('user', 'primaryRole', 'products', 'facilities', 'userRoles'));
    }
}
