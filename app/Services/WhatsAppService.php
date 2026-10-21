<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * إرسال رسائل واتساب عبر Ultramsg — بدون Meta Business المعقّد
 */
class WhatsAppService
{
    public function isConfigured(): bool
    {
        return !empty(config('services.ultramsg.instance_id'))
            && !empty(config('services.ultramsg.token'));
    }

    /**
     * يرسل رسالة واتساب لرقم جوال — يعيد true عند النجاح
     */
    public function send(string $phone, string $message): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $phone = $this->normalizePhone($phone);
        if (!$phone) {
            return false;
        }

        try {
            $response = Http::asForm()->post(
                config('services.ultramsg.base_url') . '/' . config('services.ultramsg.instance_id') . '/messages/chat',
                [
                    'token' => config('services.ultramsg.token'),
                    'to' => $phone,
                    'body' => $message,
                ]
            );

            return $response->successful() && ($response->json('sent') === 'true' || $response->json('sent') === true);
        } catch (\Throwable $e) {
            Log::warning('WhatsApp send failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * يوحّد الرقم بصيغة دولية — أرقام سعودية محلية تتحول لـ 966
     */
    protected function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', $phone);
        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '966' . substr($digits, 1);
        } elseif (strlen($digits) === 9 && str_starts_with($digits, '5')) {
            $digits = '966' . $digits;
        }

        return $digits;
    }
}
