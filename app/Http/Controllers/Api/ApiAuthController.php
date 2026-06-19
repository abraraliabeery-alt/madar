<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Services\UnifonicSmsService;

class ApiAuthController extends Controller
{
    /**
     * تسجيل الدخول
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات تسجيل الدخول غير صحيحة'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير مفعل'
            ], 403);
        }

        // إنشاء token
        $deviceName = $request->device_name ?? $request->ip();
        $token = $user->createToken($deviceName)->plainTextToken;

        $user->load(['roles', 'facilities']);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * طلب رمز تحقق عبر رقم الجوال (OTP)
     */
    public function requestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors(),
            ], 422);
        }

        $phone = $request->phone_number;

        // إيجاد أو إنشاء مستخدم مبدئي بناءً على رقم الجوال فقط
        $user = User::where('phone_number', $phone)->first();

        if (!$user) {
            $user = User::create([
                'name' => $phone,
                'email' => $phone . '@example.local',
                'phone_number' => $phone,
                'password' => Hash::make(str()->random(16)),
            ]);
        }

        // توليد كود OTP بسيط من 6 أرقام وتخزينه مع وقت انتهاء (مثلاً 5 دقائق)
        $otp = (string) random_int(100000, 999999);

        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        $smsSent = false;
        $smsError = null;
        $sms = new UnifonicSmsService();
        if ($sms->isConfigured()) {
            $message = "رمز التحقق الخاص بك هو: {$otp}";
            $sendResult = $sms->send($phone, $message);
            $smsSent = (bool) ($sendResult['ok'] ?? false);
            if (!$smsSent) {
                $smsError = $sendResult;
            }
        }

        if ($sms->isConfigured() && !$smsSent) {
            return response()->json([
                'success' => false,
                'message' => 'تعذر إرسال رمز التحقق، حاول لاحقاً',
                'data' => [
                    'phone_number' => $phone,
                ],
                'sms_error' => app()->environment('local') ? $smsError : null,
            ], 502);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز التحقق بنجاح',
            'data' => [
                'phone_number' => $phone,
                // في بيئة التطوير يمكن إظهار الكود للاختبار، وفي الإنتاج لا يُعاد
                'otp' => app()->environment('local') ? $otp : null,
            ],
        ]);
    }

    /**
     * التحقق من رمز OTP وتسجيل الدخول أو إنشاء مستخدم جديد تلقائياً
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'otp' => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors(),
            ], 422);
        }

        $phone = $request->phone_number;
        $user = User::where('phone_number', $phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'رقم الجوال غير معروف، الرجاء طلب الكود أولاً',
            ], 404);
        }

        // التحقق من الكود المخزن ووقت الانتهاء
        if (!$user->otp_code || !$user->otp_expires_at) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد رمز تحقق فعال، الرجاء طلب كود جديد',
            ], 400);
        }

        $masterOtp = (string) env('OTP_MASTER_CODE', '');
        if (app()->environment('local') && $masterOtp === '') {
            $masterOtp = '111111';
        }

        if ($user->otp_code !== $request->otp && !((app()->environment('local') && $masterOtp !== '') && $request->otp === $masterOtp)) {
            return response()->json([
                'success' => false,
                'message' => 'رمز التحقق غير صحيح',
            ], 400);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            // إلغاء الكود المنتهي
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية رمز التحقق، الرجاء طلب كود جديد',
            ], 400);
        }

        // مسح الكود بعد استخدامه واعتبار الهاتف موثّقاً
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->phone_verified_at = Carbon::now();
        $user->last_login_at = Carbon::now();
        $user->save();

        $deviceName = $request->device_name ?? $request->ip();
        $token = $user->createToken($deviceName)->plainTextToken;

        $user->load(['roles', 'facilities']);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول برقم الجوال بنجاح',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * تسجيل مستخدم جديد
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|unique:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $userData = $request->except(['password', 'password_confirmation', 'device_name']);
        $userData['password'] = Hash::make($request->password);

        $user = User::create($userData);

        // ربط المستخدم بالدور إذا تم تحديده
        if ($request->role_id) {
            $user->roles()->attach($request->role_id);
        }

        // إنشاء token
        $deviceName = $request->device_name ?? $request->ip();
        $token = $user->createToken($deviceName)->plainTextToken;

        $user->load(['roles']);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحساب بنجاح',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    }

    /**
     * تسجيل الخروج من جميع الأجهزة
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج من جميع الأجهزة بنجاح'
        ]);
    }

    /**
     * تحديث كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور الحالية غير صحيحة'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث كلمة المرور بنجاح'
        ]);
    }

    /**
     * عرض الملف الشخصي
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        $user->load(['roles', 'facilities', 'bank']);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * تحديث الملف الشخصي
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:users,phone_number,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bank_id' => 'nullable|exists:banks,id',
            'bank_account' => 'nullable|string',
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

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->all());
        $user->load(['roles', 'facilities', 'bank']);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'data' => $user
        ]);
    }

    /**
     * تحديث الصورة الشخصية
     */
    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // حذف الصورة القديمة
        if ($user->avatar) {
            \Storage::disk('public')->delete($user->avatar);
        }

        // حفظ الصورة الجديدة
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $avatarPath]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصورة الشخصية بنجاح',
            'data' => [
                'avatar_url' => \Storage::disk('public')->url($avatarPath)
            ]
        ]);
    }

    /**
     * إرسال رمز إعادة تعيين كلمة المرور
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // هنا يمكن إرسال رمز إعادة تعيين كلمة المرور عبر البريد الإلكتروني أو SMS
        // سيتم تنفيذ هذا لاحقاً

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز إعادة تعيين كلمة المرور'
        ]);
    }

    /**
     * إعادة تعيين كلمة المرور
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // هنا يمكن التحقق من الرمز وإعادة تعيين كلمة المرور
        // سيتم تنفيذ هذا لاحقاً

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح'
        ]);
    }
}
