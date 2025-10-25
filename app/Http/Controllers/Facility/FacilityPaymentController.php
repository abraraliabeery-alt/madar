<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacilityPaymentController extends Controller
{
    /**
     * عرض قائمة المدفوعات
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $query = $facility->payments()->with(['invoice', 'contract', 'contract.product']);

        // فلترة حسب الطريقة
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('payment_reference', 'like', "%{$search}%")
                  ->orWhereHas('invoice', function($q2) use ($search) {
                      $q2->where('invoice_number', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('facility.payments.index', compact('payments'));
    }

    /**
     * عرض نموذج إنشاء دفعة
     */
    public function create()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $invoices = $facility->invoices()
            ->whereIn('status', ['sent', 'overdue'])
            ->with(['contract', 'contract.product'])
            ->get();

        $contracts = $facility->contracts()
            ->where('status', 'active')
            ->with(['product', 'user'])
            ->get();

        $paymentMethods = [
            'cash' => 'نقداً',
            'bank_transfer' => 'تحويل بنكي',
            'credit_card' => 'بطاقة ائتمان',
            'check' => 'شيك',
            'online' => 'دفع إلكتروني',
        ];

        return view('facility.payments.create', compact('invoices', 'contracts', 'paymentMethods'));
    }

    /**
     * حفظ دفعة جديدة
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'invoice_id' => 'nullable|exists:invoices,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check,online',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'check_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_reference' => 'nullable|string|max:255',
            'bank_reference' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'processing_fee' => 'nullable|numeric|min:0',
            'payment_gateway' => 'nullable|string|max:255',
            'installment_number' => 'nullable|integer|min:1',
            'late_fee_paid' => 'nullable|numeric|min:0',
            'discount_applied' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['facility_id'] = $facility->id;
            $data['created_by'] = Auth::id();
            $data['status'] = 'pending';

            $payment = Payment::create($data);

            // إنشاء الحسابات المزدوجة
            $payment->createAccountingEntries();

            DB::commit();

            return redirect()->route('facility.payments.index')
                ->with('success', 'تم إنشاء الدفعة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الدفعة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * عرض تفاصيل الدفعة
     */
    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        
        $payment->load(['invoice', 'contract', 'contract.product', 'contract.user']);
        
        return view('facility.payments.show', compact('payment'));
    }

    /**
     * عرض نموذج تعديل الدفعة
     */
    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);
        
        $facility = Auth::user()->facilities()->first();
        $invoices = $facility->invoices()
            ->whereIn('status', ['sent', 'overdue'])
            ->with(['contract', 'contract.product'])
            ->get();

        $contracts = $facility->contracts()
            ->where('status', 'active')
            ->with(['product', 'user'])
            ->get();

        $paymentMethods = [
            'cash' => 'نقداً',
            'bank_transfer' => 'تحويل بنكي',
            'credit_card' => 'بطاقة ائتمان',
            'check' => 'شيك',
            'online' => 'دفع إلكتروني',
        ];
        
        return view('facility.payments.edit', compact('payment', 'invoices', 'contracts', 'paymentMethods'));
    }

    /**
     * تحديث الدفعة
     */
    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $request->validate([
            'invoice_id' => 'nullable|exists:invoices,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check,online',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'check_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_reference' => 'nullable|string|max:255',
            'bank_reference' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'processing_fee' => 'nullable|numeric|min:0',
            'payment_gateway' => 'nullable|string|max:255',
            'installment_number' => 'nullable|integer|min:1',
            'late_fee_paid' => 'nullable|numeric|min:0',
            'discount_applied' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $payment->update($request->all());

            DB::commit();

            return redirect()->route('facility.payments.index')
                ->with('success', 'تم تحديث الدفعة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الدفعة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * حذف الدفعة
     */
    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        
        $payment->delete();

        return redirect()->route('facility.payments.index')
            ->with('success', 'تم حذف الدفعة بنجاح');
    }

    /**
     * تأكيد الدفعة
     */
    public function confirm(Payment $payment)
    {
        $this->authorize('update', $payment);
        
        DB::beginTransaction();
        try {
            $payment->confirm();

            // تحديث حالة العقد
            $payment->updateContractPaymentStatus();

            DB::commit();

            return redirect()->back()->with('success', 'تم تأكيد الدفعة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'حدث خطأ أثناء تأكيد الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * إلغاء الدفعة
     */
    public function fail(Payment $payment)
    {
        $this->authorize('update', $payment);
        
        $payment->fail();

        return redirect()->back()->with('success', 'تم إلغاء الدفعة');
    }

    /**
     * استرداد الدفعة
     */
    public function refund(Payment $payment)
    {
        $this->authorize('update', $payment);
        
        DB::beginTransaction();
        try {
            $payment->refund();

            // عكس الحسابات المزدوجة
            $payment->reverseAccountingEntries();

            DB::commit();

            return redirect()->back()->with('success', 'تم استرداد الدفعة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'حدث خطأ أثناء استرداد الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * إحصائيات المدفوعات
     */
    public function statistics()
    {
        $facility = Auth::user()->facilities()->first();
        
        $stats = [
            'total_payments' => $facility->payments()->count(),
            'confirmed_payments' => $facility->payments()->where('status', 'confirmed')->count(),
            'pending_payments' => $facility->payments()->where('status', 'pending')->count(),
            'failed_payments' => $facility->payments()->where('status', 'failed')->count(),
            'refunded_payments' => $facility->payments()->where('status', 'refunded')->count(),
            'total_amount' => $facility->payments()->where('status', 'confirmed')->sum('amount'),
            'total_processing_fees' => $facility->payments()->where('status', 'confirmed')->sum('processing_fee'),
        ];

        return view('facility.payments.statistics', compact('stats'));
    }

    /**
     * تصدير المدفوعات
     */
    public function export(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        $filters = $request->only(['method', 'status']);
        $filters['facility_id'] = $facility->id;
        
        $payments = $facility->payments()
            ->with(['invoice', 'contract', 'contract.product'])
            ->when($request->method, function($q) use ($request) {
                $q->where('payment_method', $request->method);
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->get();

        // إنشاء ملف CSV
        $filename = 'payments_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // رؤوس الأعمدة
            fputcsv($file, [
                'Reference', 'Invoice Number', 'Contract Number', 'Product', 'Method', 
                'Amount', 'Status', 'Date', 'Created At'
            ]);

            // البيانات
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->reference_number,
                    $payment->invoice->invoice_number ?? '',
                    $payment->contract->contract_number ?? '',
                    $payment->contract->product->getTranslatedTitle() ?? '',
                    $payment->getPaymentMethodDisplayName(),
                    $payment->amount,
                    $payment->status,
                    $payment->payment_date->format('Y-m-d'),
                    $payment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}