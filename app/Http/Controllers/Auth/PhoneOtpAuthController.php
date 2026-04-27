<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UnifonicSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PhoneOtpAuthController extends Controller
{
    public function showPhoneForm()
    {
        return view('auth.phone-login');
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $phone = (string) $request->input('phone_number');

        $user = User::where('phone_number', $phone)->first();

        if (!$user) {
            $user = User::create([
                'name' => $phone,
                'email' => $phone . '@example.local',
                'phone_number' => $phone,
                'password' => Hash::make(str()->random(16)),
            ]);
        }

        $otp = (string) random_int(100000, 999999);

        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        $sms = new UnifonicSmsService();
        if ($sms->isConfigured()) {
            $message = "رمز التحقق الخاص بك هو: {$otp}";
            $sendResult = $sms->send($phone, $message);
            if (!(bool) ($sendResult['ok'] ?? false)) {
                return back()->withErrors([
                    'phone_number' => 'تعذر إرسال رمز التحقق، حاول لاحقاً',
                ])->withInput();
            }
        }

        $request->session()->put('otp_phone_number', $phone);

        return redirect()->route('phone.otp.verify.form');
    }

    public function showVerifyForm(Request $request)
    {
        $phone = (string) $request->session()->get('otp_phone_number', '');
        if ($phone === '') {
            return redirect()->route('phone.otp.login.form');
        }

        return view('auth.phone-verify', [
            'phone_number' => $phone,
        ]);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $phone = (string) $request->session()->get('otp_phone_number', '');
        if ($phone === '') {
            return redirect()->route('phone.otp.login.form');
        }

        $user = User::where('phone_number', $phone)->first();
        if (!$user) {
            return redirect()->route('phone.otp.login.form')->withErrors([
                'phone_number' => 'رقم الجوال غير معروف، الرجاء طلب الكود أولاً',
            ]);
        }

        if (!$user->otp_code || !$user->otp_expires_at) {
            return back()->withErrors([
                'otp' => 'لا يوجد رمز تحقق فعال، الرجاء طلب كود جديد',
            ]);
        }

        if ($user->otp_code !== $request->input('otp')) {
            return back()->withErrors([
                'otp' => 'رمز التحقق غير صحيح',
            ]);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            return back()->withErrors([
                'otp' => 'انتهت صلاحية رمز التحقق، الرجاء طلب كود جديد',
            ]);
        }

        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->phone_verified_at = Carbon::now();
        $user->last_login_at = Carbon::now();
        $user->save();

        $request->session()->forget('otp_phone_number');

        Auth::login($user, true);

        return redirect()->intended('/dashboard');
    }
}
