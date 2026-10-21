<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;

/**
 * تكامل Moyasar — إنشاء رابط دفع للفاتورة والتحقق من حالة السداد
 */
class MoyasarService
{
    public function isConfigured(): bool
    {
        return !empty(config('services.moyasar.api_key'));
    }

    /**
     * ينشئ فاتورة دفع مستضافة عند Moyasar ويعيد رابطها
     * يعيد ['url' => ..., 'id' => ...] أو null عند الفشل
     */
    public function createPaymentLink(Invoice $invoice, string $callbackUrl): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $response = Http::withBasicAuth(config('services.moyasar.api_key'), '')
            ->post(config('services.moyasar.base_url') . '/invoices', [
                'amount' => (int) round(($invoice->remaining_amount ?? $invoice->amount) * 100),
                'currency' => 'SAR',
                'description' => 'فاتورة ' . ($invoice->invoice_number ?? $invoice->id),
                'callback_url' => $callbackUrl,
            ]);

        if (!$response->successful()) {
            return null;
        }

        return [
            'url' => $response->json('url'),
            'id' => $response->json('id'),
        ];
    }

    /**
     * يجلب حالة فاتورة الدفع من Moyasar — يعيد بيانات الفاتورة أو null
     */
    public function fetchInvoice(string $gatewayInvoiceId): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $response = Http::withBasicAuth(config('services.moyasar.api_key'), '')
            ->get(config('services.moyasar.base_url') . '/invoices/' . $gatewayInvoiceId);

        return $response->successful() ? $response->json() : null;
    }
}
