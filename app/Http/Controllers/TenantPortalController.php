<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Services\MoyasarService;

class TenantPortalController extends Controller
{
    /**
     * صفحة العقد للمستأجر — تفتح برابط موقّع بدون تسجيل دخول
     */
    public function show(Contract $contract, MoyasarService $moyasar)
    {
        $contract->load(['product.translations', 'facility', 'invoices', 'payments', 'tenant.translations', 'user']);

        $maintenanceRequests = MaintenanceRequest::where('facility_id', $contract->facility_id)
            ->where('product_id', $contract->product_id)
            ->latest()
            ->take(20)
            ->get();

        return view('tenant_portal.contract', [
            'contract' => $contract,
            'maintenanceRequests' => $maintenanceRequests,
            'moyasarEnabled' => $moyasar->isConfigured(),
        ]);
    }

    /**
     * إنشاء طلب صيانة من البوابة
     */
    public function storeMaintenance(Request $request, Contract $contract)
    {
        $data = $request->validate([
            'type' => 'required|in:' . implode(',', MaintenanceRequest::TYPES),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        MaintenanceRequest::create($data + [
            'facility_id' => $contract->facility_id,
            'product_id' => $contract->product_id,
            'tenant_id' => $contract->tenant_id,
            'status' => 'open',
            'priority' => 'medium',
        ]);

        return back()->with('success', __('تم استلام طلب الصيانة بنجاح'));
    }

    /**
     * توجيه المستأجر لبوابة الدفع لسداد فاتورة
     */
    public function pay(Contract $contract, Invoice $invoice, MoyasarService $moyasar)
    {
        abort_unless($invoice->contract_id === $contract->id, 404);
        abort_unless($moyasar->isConfigured(), 403);

        if ($invoice->remaining_amount <= 0) {
            return redirect()->to(URL::signedRoute('tenant-portal.contract', ['contract' => $contract->id]));
        }

        $link = $moyasar->createPaymentLink($invoice, route('tenant-portal.payment.callback'));
        abort_unless($link && !empty($link['url']), 502);

        $invoice->update(['gateway_invoice_id' => $link['id'] ?? null]);

        return redirect()->away($link['url']);
    }

    /**
     * عودة بوابة الدفع — التحقق يتم من مزوّد الدفع نفسه
     */
    public function paymentCallback(Request $request, MoyasarService $moyasar)
    {
        $gatewayId = $request->query('id') ?? $request->query('invoice_id');
        $invoice = Invoice::where('gateway_invoice_id', $gatewayId)->firstOrFail();

        $remote = $moyasar->fetchInvoice($gatewayId);
        $paid = in_array($remote['status'] ?? null, ['paid', 'settled'], true)
            || in_array($request->query('status'), ['paid', 'settled'], true);

        if ($paid && $invoice->remaining_amount > 0) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'contract_id' => $invoice->contract_id,
                'facility_id' => $invoice->facility_id,
                'payment_method' => 'online',
                'amount' => $invoice->remaining_amount,
                'payment_date' => now(),
                'status' => 'confirmed',
                'payment_gateway' => 'moyasar',
                'transaction_id' => $gatewayId,
            ]);
            $invoice->update(['remaining_amount' => 0, 'status' => 'paid']);
        }

        return redirect()->to(URL::signedRoute('tenant-portal.contract', ['contract' => $invoice->contract_id]))
            ->with($paid ? 'success' : 'error', $paid ? __('تم استلام الدفعة بنجاح') : __('لم تكتمل عملية الدفع'));
    }
}
