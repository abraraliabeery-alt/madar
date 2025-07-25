<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    /**
     * عرض صفحة تعديل الملف الشخصي
     */
    public function edit()
    {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * تحديث الملف الشخصي
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'whatsapp_number' => 'nullable|string',
            'telegram' => 'nullable|string',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'youtube' => 'nullable|url',
            'snapchat' => 'nullable|string',
        ]);

        $data = $request->except(['password', 'password_confirmation', 'current_password', 'avatar']);

        // تحديث كلمة المرور إذا تم توفيرها
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // معالجة الصورة الشخصية
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
