<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Offer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\AccountingEntry;
use Illuminate\Support\Facades\DB;

class ContractService
{
    /**
     * إنشاء عقد جديد
     */
    public function createContract(array $data)
    {
        return DB::transaction(function () use ($data) {
            // إنشاء العقد
            $contract = Contract::create($data);
            
            // توليد رقم العقد
            $contract->generateContractNumber()->save();
            
            // حساب العمولة
            $contract->calculateCommission()->save();
            
            // إنشاء الفواتير حسب نوع العقد
            $this->createInvoicesForContract($contract);
            
            // إنشاء القيود المحاسبية
            $this->createAccountingEntriesForContract($contract);
            
            return $contract;
        });
    }

    /**
     * إنشاء الفواتير للعقد
     */
    public function createInvoicesForContract(Contract $contract)
    {
        if ($contract->contract_type === 'sale') {
            // عقد بيع - فاتورة واحدة
            $this->createSaleInvoice($contract);
        } else {
            // عقد إيجار - فواتير متعددة حسب المدة
            $this->createRentInvoices($contract);
        }
    }

    /**
     * إنشاء فاتورة البيع
     */
    private function createSaleInvoice(Contract $contract)
    {
        $invoice = Invoice::create([
            'contract_id' => $contract->id,
            'invoice_number' => $this->generateInvoiceNumber($contract, 'SALE'),
            'invoice_type' => 'sale',
            'amount' => $contract->total_amount,
            'currency' => $contract->currency,
            'due_date' => now()->addDays(30), // 30 يوم للدفع
            'remaining_amount' => $contract->total_amount,
            'status' => 'draft',
            'facility_id' => $contract->facility_id,
            'created_by' => $contract->created_by,
        ]);

        // إنشاء فاتورة العربون إذا كان موجود
        if ($contract->deposit_amount > 0) {
            Invoice::create([
                'contract_id' => $contract->id,
                'invoice_number' => $this->generateInvoiceNumber($contract, 'DEPOSIT'),
                'invoice_type' => 'deposit',
                'amount' => $contract->deposit_amount,
                'currency' => $contract->currency,
                'due_date' => now()->addDays(7), // 7 أيام للعربون
                'remaining_amount' => $contract->deposit_amount,
                'status' => 'draft',
                'facility_id' => $contract->facility_id,
                'created_by' => $contract->created_by,
            ]);
        }

        // إنشاء فاتورة العمولة
        if ($contract->commission_amount > 0) {
            Invoice::create([
                'contract_id' => $contract->id,
                'invoice_number' => $this->generateInvoiceNumber($contract, 'COMMISSION'),
                'invoice_type' => 'commission',
                'amount' => $contract->commission_amount,
                'currency' => $contract->currency,
                'due_date' => now()->addDays(30),
                'remaining_amount' => $contract->commission_amount,
                'status' => 'draft',
                'facility_id' => $contract->facility_id,
                'created_by' => $contract->created_by,
            ]);
        }
    }

    /**
     * إنشاء فواتير الإيجار
     */
    private function createRentInvoices(Contract $contract)
    {
        $startDate = $contract->start_date;
        $endDate = $contract->end_date;
        $monthlyAmount = $contract->total_amount;
        
        // حساب عدد الأشهر
        $months = $startDate->diffInMonths($endDate);
        
        for ($i = 0; $i < $months; $i++) {
            $dueDate = $startDate->copy()->addMonths($i + 1);
            
            Invoice::create([
                'contract_id' => $contract->id,
                'invoice_number' => $this->generateInvoiceNumber($contract, 'RENT', $i + 1),
                'invoice_type' => 'rent',
                'amount' => $monthlyAmount,
                'currency' => $contract->currency,
                'due_date' => $dueDate,
                'remaining_amount' => $monthlyAmount,
                'status' => 'draft',
                'facility_id' => $contract->facility_id,
                'created_by' => $contract->created_by,
            ]);
        }

        // فاتورة العربون
        if ($contract->deposit_amount > 0) {
            Invoice::create([
                'contract_id' => $contract->id,
                'invoice_number' => $this->generateInvoiceNumber($contract, 'DEPOSIT'),
                'invoice_type' => 'deposit',
                'amount' => $contract->deposit_amount,
                'currency' => $contract->currency,
                'due_date' => now()->addDays(7),
                'remaining_amount' => $contract->deposit_amount,
                'status' => 'draft',
                'facility_id' => $contract->facility_id,
                'created_by' => $contract->created_by,
            ]);
        }
    }

    /**
     * إنشاء القيود المحاسبية للعقد
     */
    public function createAccountingEntriesForContract(Contract $contract)
    {
        // قيد الإيراد
        AccountingEntry::createRevenueEntry(
            $contract->total_amount,
            "إيراد عقد {$contract->contract_number}",
            $contract->id,
            $contract->facility_id,
            'contract',
            $contract->id
        );

        // قيد الذمم المدينة
        AccountingEntry::createReceivableEntry(
            $contract->total_amount,
            "ذمم مدينة - عقد {$contract->contract_number}",
            $contract->id,
            $contract->facility_id,
            'contract',
            $contract->id
        );

        // قيد العمولة
        if ($contract->commission_amount > 0) {
            AccountingEntry::createCommissionEntry(
                $contract->commission_amount,
                "عمولة عقد {$contract->contract_number}",
                $contract->id,
                $contract->facility_id,
                'contract',
                $contract->id
            );
        }

        // قيد الالتزام (العربون)
        if ($contract->deposit_amount > 0) {
            AccountingEntry::createLiabilityEntry(
                $contract->deposit_amount,
                "عربون عقد {$contract->contract_number}",
                $contract->id,
                $contract->facility_id,
                'contract',
                $contract->id
            );
        }
    }

    /**
     * تسجيل دفعة
     */
    public function recordPayment(array $data)
    {
        return DB::transaction(function () use ($data) {
            // إنشاء الدفعة
            $payment = Payment::create($data);
            
            // تأكيد الدفعة
            $payment->confirm();
            
            // إنشاء القيود المحاسبية للدفعة
            $this->createAccountingEntriesForPayment($payment);
            
            return $payment;
        });
    }

    /**
     * إنشاء القيود المحاسبية للدفعة
     */
    public function createAccountingEntriesForPayment(Payment $payment)
    {
        // قيد النقد/البنك (مدين)
        AccountingEntry::create([
            'entry_type' => 'debit',
            'account_type' => 'revenue',
            'amount' => $payment->amount,
            'description' => "دفعة {$payment->reference_number} - عقد {$payment->contract->contract_number}",
            'contract_id' => $payment->contract_id,
            'facility_id' => $payment->facility_id,
            'created_by' => $payment->created_by,
            'entry_date' => $payment->payment_date,
            'reference_type' => 'payment',
            'reference_id' => $payment->id,
        ]);

        // قيد الذمم المدينة (دائن)
        AccountingEntry::create([
            'entry_type' => 'credit',
            'account_type' => 'receivable',
            'amount' => $payment->amount,
            'description' => "تسوية ذمم - دفعة {$payment->reference_number}",
            'contract_id' => $payment->contract_id,
            'facility_id' => $payment->facility_id,
            'created_by' => $payment->created_by,
            'entry_date' => $payment->payment_date,
            'reference_type' => 'payment',
            'reference_id' => $payment->id,
        ]);
    }

    /**
     * توليد رقم الفاتورة
     */
    private function generateInvoiceNumber(Contract $contract, string $type, int $sequence = null)
    {
        $prefix = $type;
        $date = date('Ymd');
        $contractId = str_pad($contract->id, 4, '0', STR_PAD_LEFT);
        $sequence = $sequence ? str_pad($sequence, 2, '0', STR_PAD_LEFT) : '01';
        
        return "{$prefix}-{$date}-{$contractId}-{$sequence}";
    }

    /**
     * تحديث حالة العقد
     */
    public function updateContractStatus(Contract $contract, string $status)
    {
        $contract->status = $status;
        $contract->save();
        
        return $contract;
    }

    /**
     * إلغاء العقد
     */
    public function cancelContract(Contract $contract, string $reason = null)
    {
        return DB::transaction(function () use ($contract, $reason) {
            // تحديث حالة العقد
            $contract->status = 'cancelled';
            $contract->save();
            
            // إلغاء الفواتير غير المدفوعة
            $contract->invoices()
                ->where('status', '!=', 'paid')
                ->update(['status' => 'cancelled']);
            
            // إنشاء قيود الإلغاء
            $this->createCancellationEntries($contract, $reason);
            
            return $contract;
        });
    }

    /**
     * إنشاء قيود الإلغاء
     */
    private function createCancellationEntries(Contract $contract, string $reason = null)
    {
        $description = "إلغاء عقد {$contract->contract_number}";
        if ($reason) {
            $description .= " - {$reason}";
        }

        // قيد عكسي للإيراد
        AccountingEntry::create([
            'entry_type' => 'debit',
            'account_type' => 'revenue',
            'amount' => $contract->total_amount,
            'description' => $description,
            'contract_id' => $contract->id,
            'facility_id' => $contract->facility_id,
            'created_by' => auth()->id(),
            'entry_date' => now()->toDateString(),
            'reference_type' => 'contract',
            'reference_id' => $contract->id,
        ]);
    }
}
