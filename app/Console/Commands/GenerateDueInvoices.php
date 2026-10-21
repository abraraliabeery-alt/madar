<?php

namespace App\Console\Commands;

use App\Models\Facility;
use App\Models\Invoice;
use Illuminate\Console\Command;

class GenerateDueInvoices extends Command
{
    protected $signature = 'invoices:generate-due';
    protected $description = 'توليد فواتير تلقائية للأقساط المستحقة من العقود النشطة';

    public function handle(): int
    {
        $generated = 0;

        Facility::whereHas('contracts', fn ($q) => $q->where('status', 'active')
            ->where('next_payment_date', '<=', now()->toDateString()))
            ->each(function (Facility $facility) use (&$generated) {
                $facility->contracts()
                    ->where('status', 'active')
                    ->where('next_payment_date', '<=', now()->toDateString())
                    ->get()
                    ->each(function ($contract) use (&$generated, $facility) {
                        $installment = $contract->getNextUnpaidInstallment();
                        if (!$installment) {
                            return;
                        }

                        $exists = Invoice::where('contract_id', $contract->id)
                            ->where('installment_number', $installment['installment_number'])
                            ->exists();
                        if ($exists) {
                            return;
                        }

                        $invoice = Invoice::create([
                            'contract_id' => $contract->id,
                            'invoice_type' => $contract->contract_type,
                            'amount' => $installment['amount'],
                            'due_date' => $installment['due_date'],
                            'installment_number' => $installment['installment_number'],
                            'installment_amount' => $installment['amount'],
                            'facility_id' => $facility->id,
                            'created_by' => $contract->created_by,
                            'remaining_amount' => $installment['amount'],
                        ]);

                        // فوترة إلكترونية تلقائية إذا كان المزوّد مفعّلاً
                        app(\App\Services\EInvoiceService::class)->submit($invoice);

                        $generated++;
                    });
            });

        $this->info("تم إنشاء {$generated} فاتورة");
        return self::SUCCESS;
    }
}
