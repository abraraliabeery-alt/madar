<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacilityInvoiceController extends Controller
{
    /**
     * عرض قائمة الفواتير
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $query = $facility->invoices()->with(['contract', 'contract.product', 'contract.user']);

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('invoice_type', $request->type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('contract', function($q2) use ($search) {
                      $q2->where('contract_number', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('facility.invoices.index', compact('invoices'));
    }

    /**
     * عرض نموذج إنشاء فاتورة
     */
    public function create()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $contracts = $facility->contracts()
            ->where('status', 'active')
            ->with(['product', 'user', 'offer'])
            ->get();

        $invoiceTypes = [
            'rent' => 'إيجار',
            'sale' => 'بيع',
            'deposit' => 'عربون',
            'commission' => 'عمولة',
            'refund' => 'استرداد',
        ];

        return view('facility.invoices.create', compact('contracts', 'invoiceTypes'));
    }

    /**
     * حفظ فاتورة جديدة
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'invoice_type' => 'required|in:rent,sale,deposit,commission,refund',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'payment_terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'installment_number' => 'nullable|integer|min:1',
            'installment_amount' => 'nullable|numeric|min:0',
            'late_fee_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:1',
            'payment_terms_days' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['facility_id'] = $facility->id;
            $data['created_by'] = Auth::id();
            $data['remaining_amount'] = $request->amount;

            // حساب الضريبة
            if ($request->tax_rate) {
                $data['tax_amount'] = $request->amount * $request->tax_rate;
            }

            // حساب المبلغ الصافي
            $data['net_amount'] = $request->amount + ($data['tax_amount'] ?? 0) + ($request->late_fee_amount ?? 0) - ($request->discount_amount ?? 0);

            $invoice = Invoice::create($data);

            // إنشاء الحسابات المزدوجة
            $invoice->createAccountingEntries();

            DB::commit();

            return redirect()->route('facility.invoices.index')
                ->with('success', 'تم إنشاء الفاتورة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * عرض تفاصيل الفاتورة
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        
        $invoice->load(['contract', 'contract.product', 'contract.user', 'payments']);
        
        return view('facility.invoices.show', compact('invoice'));
    }

    /**
     * عرض نموذج تعديل الفاتورة
     */
    public function edit(Invoice $invoice)
    {
        $this->authorize('update', $invoice);
        
        $facility = Auth::user()->facilities()->first();
        $contracts = $facility->contracts()
            ->where('status', 'active')
            ->with(['product', 'user', 'offer'])
            ->get();

        $invoiceTypes = [
            'rent' => 'إيجار',
            'sale' => 'بيع',
            'deposit' => 'عربون',
            'commission' => 'عمولة',
            'refund' => 'استرداد',
        ];
        
        return view('facility.invoices.edit', compact('invoice', 'contracts', 'invoiceTypes'));
    }

    /**
     * تحديث الفاتورة
     */
    public function update(Request $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'invoice_type' => 'required|in:rent,sale,deposit,commission,refund',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'payment_terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'installment_number' => 'nullable|integer|min:1',
            'installment_amount' => 'nullable|numeric|min:0',
            'late_fee_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:1',
            'payment_terms_days' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // حساب الضريبة
            if ($request->tax_rate) {
                $data['tax_amount'] = $request->amount * $request->tax_rate;
            }

            // حساب المبلغ الصافي
            $data['net_amount'] = $request->amount + ($data['tax_amount'] ?? 0) + ($request->late_fee_amount ?? 0) - ($request->discount_amount ?? 0);

            $invoice->update($data);

            DB::commit();

            return redirect()->route('facility.invoices.index')
                ->with('success', 'تم تحديث الفاتورة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الفاتورة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * حذف الفاتورة
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);
        
        $invoice->delete();

        return redirect()->route('facility.invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح');
    }

    /**
     * إرسال تذكير دفع
     */
    public function sendReminder(Invoice $invoice)
    {
        $this->authorize('update', $invoice);
        
        if ($invoice->sendPaymentReminder()) {
            return redirect()->back()->with('success', 'تم إرسال التذكير بنجاح');
        }

        return redirect()->back()->with('error', 'لا يمكن إرسال التذكير في الوقت الحالي');
    }

    /**
     * إنشاء فواتير تلقائية للعقود
     */
    public function generateInvoices()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $contracts = $facility->contracts()
            ->where('status', 'active')
            ->where('next_payment_date', '<=', now()->toDateString())
            ->get();

        $generated = 0;
        foreach ($contracts as $contract) {
            $nextInstallment = $contract->getNextUnpaidInstallment();
            if ($nextInstallment) {
                Invoice::create([
                    'contract_id' => $contract->id,
                    'invoice_type' => $contract->contract_type,
                    'amount' => $nextInstallment['amount'],
                    'due_date' => $nextInstallment['due_date'],
                    'installment_number' => $nextInstallment['installment_number'],
                    'installment_amount' => $nextInstallment['amount'],
                    'facility_id' => $facility->id,
                    'created_by' => Auth::id(),
                    'remaining_amount' => $nextInstallment['amount'],
                ]);
                $generated++;
            }
        }

        return redirect()->back()
            ->with('success', "تم إنشاء {$generated} فاتورة تلقائياً");
    }

    /**
     * إحصائيات الفواتير
     */
    public function statistics()
    {
        $facility = Auth::user()->facilities()->first();
        
        $stats = [
            'total_invoices' => $facility->invoices()->count(),
            'paid_invoices' => $facility->invoices()->where('status', 'paid')->count(),
            'pending_invoices' => $facility->invoices()->whereIn('status', ['draft', 'sent'])->count(),
            'overdue_invoices' => $facility->invoices()->where('status', 'overdue')->count(),
            'total_amount' => $facility->invoices()->sum('amount'),
            'paid_amount' => $facility->invoices()->sum('paid_amount'),
            'remaining_amount' => $facility->invoices()->sum('remaining_amount'),
        ];

        return view('facility.invoices.statistics', compact('stats'));
    }

    /**
     * تصدير الفواتير
     */
    public function export(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        $filters = $request->only(['type', 'status']);
        $filters['facility_id'] = $facility->id;
        
        $invoices = $facility->invoices()
            ->with(['contract', 'contract.product'])
            ->when($request->type, function($q) use ($request) {
                $q->where('invoice_type', $request->type);
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->get();

        // إنشاء ملف CSV
        $filename = 'invoices_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($invoices) {
            $file = fopen('php://output', 'w');
            
            // رؤوس الأعمدة
            fputcsv($file, [
                'Invoice Number', 'Contract Number', 'Product', 'Type', 'Amount', 
                'Paid Amount', 'Remaining', 'Status', 'Due Date', 'Created At'
            ]);

            // البيانات
            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->invoice_number,
                    $invoice->contract->contract_number ?? '',
                    $invoice->contract->product->getTranslatedTitle() ?? '',
                    $invoice->invoice_type,
                    $invoice->amount,
                    $invoice->paid_amount,
                    $invoice->remaining_amount,
                    $invoice->status,
                    $invoice->due_date->format('Y-m-d'),
                    $invoice->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}