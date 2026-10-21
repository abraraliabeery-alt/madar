<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * فوترة إلكترونية — ترسل الفاتورة لمزوّد معتمد لزاتكا (Fatora.io أو أي مزوّد بنفس الواجهة)
 * المزوّد يوقّعها ويعيد UUID + QR — نحفظها على الفاتورة
 */
class EInvoiceService
{
    public function isConfigured(): bool
    {
        return !empty(config('services.fatora.api_key'));
    }

    /**
     * يرسل الفاتورة للمزوّد ويخزّن نتيجة التوثيق على الفاتورة
     */
    public function submit(Invoice $invoice): bool
    {
        if (!$this->isConfigured() || $invoice->zatca_uuid) {
            return false;
        }

        try {
            $response = Http::withToken(config('services.fatora.api_key'))
                ->post(config('services.fatora.base_url') . '/invoices', [
                    'reference' => $invoice->invoice_number ?? (string) $invoice->id,
                    'issue_date' => optional($invoice->created_at)->toDateString(),
                    'due_date' => optional($invoice->due_date)->toDateString(),
                    'total' => (float) $invoice->amount,
                    'tax' => (float) ($invoice->tax_amount ?? 0),
                    'currency' => 'SAR',
                    'customer' => [
                        'name' => $invoice->contract?->tenant?->getTranslatedName()
                            ?? $invoice->contract?->user?->name ?? 'عميل',
                        'phone' => $invoice->contract?->tenant?->phone,
                    ],
                    'items' => [[
                        'name' => 'فاتورة ' . ($invoice->invoice_type ?? 'rent') . ' — عقد ' . ($invoice->contract?->contract_number ?? ''),
                        'quantity' => 1,
                        'price' => (float) $invoice->amount,
                    ]],
                ]);

            if (!$response->successful()) {
                Log::warning('E-invoice submit failed', ['invoice' => $invoice->id, 'body' => $response->body()]);
                return false;
            }

            $invoice->update([
                'zatca_uuid' => $response->json('uuid') ?? $response->json('id'),
                'zatca_qr' => $response->json('qr_code') ?? $response->json('qr'),
                'zatca_status' => 'submitted',
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::warning('E-invoice submit error: ' . $e->getMessage());
            return false;
        }
    }
}
