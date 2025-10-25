<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\AccountingEntry;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialReportService
{
    /**
     * تقرير الإيرادات
     */
    public function getRevenueReport(int $facilityId = null, Carbon $startDate = null, Carbon $endDate = null)
    {
        $query = AccountingEntry::where('account_type', 'revenue');
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        if ($startDate) {
            $query->where('entry_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('entry_date', '<=', $endDate);
        }
        
        $revenues = $query->get();
        
        return [
            'total_revenue' => $revenues->sum('amount'),
            'by_type' => $revenues->groupBy('reference_type')->map(function ($group) {
                return $group->sum('amount');
            }),
            'by_month' => $revenues->groupBy(function ($item) {
                return Carbon::parse($item->entry_date)->format('Y-m');
            })->map(function ($group) {
                return $group->sum('amount');
            }),
            'entries' => $revenues,
        ];
    }

    /**
     * تقرير الذمم المدينة
     */
    public function getReceivablesReport(int $facilityId = null)
    {
        $query = AccountingEntry::where('account_type', 'receivable');
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        $receivables = $query->get();
        
        return [
            'total_receivables' => $receivables->sum('amount'),
            'by_contract' => $receivables->groupBy('contract_id')->map(function ($group) {
                return [
                    'contract_id' => $group->first()->contract_id,
                    'amount' => $group->sum('amount'),
                    'contract' => $group->first()->contract,
                ];
            }),
            'entries' => $receivables,
        ];
    }

    /**
     * تقرير العمولات
     */
    public function getCommissionReport(int $facilityId = null, Carbon $startDate = null, Carbon $endDate = null)
    {
        $query = AccountingEntry::where('account_type', 'commission');
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        if ($startDate) {
            $query->where('entry_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('entry_date', '<=', $endDate);
        }
        
        $commissions = $query->get();
        
        return [
            'total_commission' => $commissions->sum('amount'),
            'by_contract' => $commissions->groupBy('contract_id')->map(function ($group) {
                return [
                    'contract_id' => $group->first()->contract_id,
                    'amount' => $group->sum('amount'),
                    'contract' => $group->first()->contract,
                ];
            }),
            'entries' => $commissions,
        ];
    }

    /**
     * تقرير المدفوعات
     */
    public function getPaymentsReport(int $facilityId = null, Carbon $startDate = null, Carbon $endDate = null)
    {
        $query = Payment::confirmed();
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        if ($startDate) {
            $query->where('payment_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('payment_date', '<=', $endDate);
        }
        
        $payments = $query->with(['contract', 'invoice'])->get();
        
        return [
            'total_payments' => $payments->sum('amount'),
            'by_method' => $payments->groupBy('payment_method')->map(function ($group) {
                return $group->sum('amount');
            }),
            'by_month' => $payments->groupBy(function ($item) {
                return Carbon::parse($item->payment_date)->format('Y-m');
            })->map(function ($group) {
                return $group->sum('amount');
            }),
            'payments' => $payments,
        ];
    }

    /**
     * تقرير الفواتير
     */
    public function getInvoicesReport(int $facilityId = null, Carbon $startDate = null, Carbon $endDate = null)
    {
        $query = Invoice::query();
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        $invoices = $query->with(['contract'])->get();
        
        return [
            'total_invoices' => $invoices->count(),
            'total_amount' => $invoices->sum('amount'),
            'paid_amount' => $invoices->sum('paid_amount'),
            'remaining_amount' => $invoices->sum('remaining_amount'),
            'by_status' => $invoices->groupBy('status')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'amount' => $group->sum('amount'),
                ];
            }),
            'by_type' => $invoices->groupBy('invoice_type')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'amount' => $group->sum('amount'),
                ];
            }),
            'overdue_invoices' => $invoices->filter(function ($invoice) {
                return $invoice->isOverdue();
            }),
            'invoices' => $invoices,
        ];
    }

    /**
     * تقرير شامل للمنشأة
     */
    public function getFacilityFinancialSummary(int $facilityId, Carbon $startDate = null, Carbon $endDate = null)
    {
        $startDate = $startDate ?: now()->startOfMonth();
        $endDate = $endDate ?: now()->endOfMonth();
        
        $revenueReport = $this->getRevenueReport($facilityId, $startDate, $endDate);
        $paymentsReport = $this->getPaymentsReport($facilityId, $startDate, $endDate);
        $invoicesReport = $this->getInvoicesReport($facilityId, $startDate, $endDate);
        $commissionReport = $this->getCommissionReport($facilityId, $startDate, $endDate);
        
        return [
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
            'revenue' => $revenueReport,
            'payments' => $paymentsReport,
            'invoices' => $invoicesReport,
            'commissions' => $commissionReport,
            'summary' => [
                'total_revenue' => $revenueReport['total_revenue'],
                'total_payments' => $paymentsReport['total_payments'],
                'total_commissions' => $commissionReport['total_commission'],
                'net_income' => $revenueReport['total_revenue'] - $commissionReport['total_commission'],
                'collection_rate' => $revenueReport['total_revenue'] > 0 
                    ? ($paymentsReport['total_payments'] / $revenueReport['total_revenue']) * 100 
                    : 0,
            ],
        ];
    }

    /**
     * تقرير العقود
     */
    public function getContractsReport(int $facilityId = null, Carbon $startDate = null, Carbon $endDate = null)
    {
        $query = Contract::query();
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        $contracts = $query->with(['product', 'user', 'owner', 'invoices', 'payments'])->get();
        
        return [
            'total_contracts' => $contracts->count(),
            'total_value' => $contracts->sum('total_amount'),
            'by_type' => $contracts->groupBy('contract_type')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'value' => $group->sum('total_amount'),
                ];
            }),
            'by_status' => $contracts->groupBy('status')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'value' => $group->sum('total_amount'),
                ];
            }),
            'fully_paid' => $contracts->filter(function ($contract) {
                return $contract->isFullyPaid();
            })->count(),
            'partially_paid' => $contracts->filter(function ($contract) {
                return !$contract->isFullyPaid() && $contract->getTotalPaidAmount() > 0;
            })->count(),
            'unpaid' => $contracts->filter(function ($contract) {
                return $contract->getTotalPaidAmount() == 0;
            })->count(),
            'contracts' => $contracts,
        ];
    }

    /**
     * تقرير العميل
     */
    public function getCustomerReport(int $customerId, int $facilityId = null)
    {
        $query = Contract::where('user_id', $customerId);
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        $contracts = $query->with(['product', 'invoices', 'payments'])->get();
        
        $totalValue = $contracts->sum('total_amount');
        $totalPaid = $contracts->sum(function ($contract) {
            return $contract->getTotalPaidAmount();
        });
        
        return [
            'customer_id' => $customerId,
            'total_contracts' => $contracts->count(),
            'total_value' => $totalValue,
            'total_paid' => $totalPaid,
            'remaining_amount' => $totalValue - $totalPaid,
            'payment_rate' => $totalValue > 0 ? ($totalPaid / $totalValue) * 100 : 0,
            'contracts' => $contracts,
        ];
    }

    /**
     * تقرير المالك
     */
    public function getOwnerReport(int $ownerId, int $facilityId = null)
    {
        $query = Contract::where('owner_id', $ownerId);
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        $contracts = $query->with(['product', 'user', 'invoices', 'payments'])->get();
        
        $totalValue = $contracts->sum('total_amount');
        $totalCommission = $contracts->sum('commission_amount');
        $netAmount = $totalValue - $totalCommission;
        
        return [
            'owner_id' => $ownerId,
            'total_contracts' => $contracts->count(),
            'total_value' => $totalValue,
            'total_commission' => $totalCommission,
            'net_amount' => $netAmount,
            'commission_rate' => $totalValue > 0 ? ($totalCommission / $totalValue) * 100 : 0,
            'contracts' => $contracts,
        ];
    }
}
