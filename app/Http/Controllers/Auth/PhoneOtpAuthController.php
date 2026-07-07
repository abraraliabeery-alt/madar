<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UnifonicSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PhoneOtpAuthController extends Controller
{
    private function normalizePhone(string $input, string $defaultRegion = 'SA'): string
    {
        $s = trim($input);
        $s = preg_replace('/[^0-9+]/', '', $s) ?? '';
        if ($s === '') {
            return '';
        }

        if (class_exists('\\libphonenumber\\PhoneNumberUtil')) {
            try {
                $util = \libphonenumber\PhoneNumberUtil::getInstance();
                $proto = $util->parse($s, strtoupper($defaultRegion));
                if (!$util->isValidNumber($proto)) {
                    return '';
                }
                return $util->format($proto, \libphonenumber\PhoneNumberFormat::E164);
            } catch (\Throwable $e) {
                Log::warning('Phone normalization failed, falling back', [
                    'input' => $input,
                    'defaultRegion' => $defaultRegion,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $digits = preg_replace('/\D/', '', $s) ?? '';
        if ($digits === '') {
            return '';
        }

        if ($defaultRegion === 'SA') {
            if (str_starts_with($digits, '9665') && strlen($digits) === 12) {
                return '+' . $digits;
            }
            if (str_starts_with($digits, '05') && strlen($digits) === 10) {
                return '+966' . substr($digits, 1);
            }
            if (str_starts_with($digits, '5') && strlen($digits) === 9) {
                return '+966' . $digits;
            }
            if (str_starts_with($digits, '966') && strlen($digits) === 12) {
                return '+' . $digits;
            }
        }

        return '';
    }

    public function showPhoneForm()
    {
        return view('auth.phone-login');
    }

    public function passwordLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'string'],
            'login_intent' => ['nullable', 'in:client,facility,admin'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $phone = $this->normalizePhone((string) $request->input('phone_number'), 'SA');
        $password = (string) $request->input('password');

        if ($phone === '') {
            return back()->withErrors([
                'phone_number' => 'رقم الجوال غير صحيح',
            ])->withInput();
        }

        $user = User::where('phone_number', $phone)->first();

        if (!$user) {
            $user = User::create([
                'name' => $phone,
                'email' => $phone . '@example.local',
                'phone_number' => $phone,
                'password' => Hash::make($password),
            ]);

            Log::info('User created via phone+password login', [
                'phone_number' => $phone,
            ]);
        } else {
            if (!Hash::check($password, (string) $user->password)) {
                return back()->withErrors([
                    'password' => 'كلمة المرور غير صحيحة',
                ])->withInput();
            }
        }

        $user->last_login_at = Carbon::now();
        $user->save();

        Auth::login($user, true);

        $loginIntent = (string) $request->input('login_intent', 'client');

        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($loginIntent === 'facility') {
            if (method_exists($user, 'hasRole') && method_exists($user, 'assignRole') && !$user->hasRole('facility')) {
                $user->assignRole('facility');
            }

            return redirect()->route('facility.onboarding.create');
        }

        return redirect()->intended('/dashboard');
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => ['required', 'string'],
            'login_intent' => ['nullable', 'in:client,facility,admin'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $phone = $this->normalizePhone((string) $request->input('phone_number'), 'SA');

        if ($phone === '') {
            return back()->withErrors([
                'phone_number' => 'رقم الجوال غير صحيح',
            ])->withInput();
        }

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
        $request->session()->put('otp_login_intent', $request->input('login_intent', 'client'));

        return redirect()->route('phone.otp.verify.form');
    }

    public function showVerifyForm(Request $request)
    {
        $phone = $this->normalizePhone((string) $request->session()->get('otp_phone_number', ''), 'SA');
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

        $phone = $this->normalizePhone((string) $request->session()->get('otp_phone_number', ''), 'SA');
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

        $masterOtp = (string) env('OTP_MASTER_CODE', '');
        if (app()->environment('local') && $masterOtp === '') {
            $masterOtp = '111111';
        }

        $inputOtp = (string) $request->input('otp');
        if ($user->otp_code !== $inputOtp && !((app()->environment('local') && $masterOtp !== '') && $inputOtp === $masterOtp)) {
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

        $loginIntent = (string) $request->session()->get('otp_login_intent', 'client');
        $request->session()->forget('otp_phone_number');
        $request->session()->forget('otp_login_intent');

        Auth::login($user, true);

        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($loginIntent === 'facility') {
            if (method_exists($user, 'hasRole') && method_exists($user, 'assignRole') && !$user->hasRole('facility')) {
                $user->assignRole('facility');
            }

            return redirect()->route('facility.onboarding.create');
        }

        return redirect()->intended('/dashboard');
    }
}
