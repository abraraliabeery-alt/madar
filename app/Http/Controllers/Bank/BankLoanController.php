<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoanRequest;
use App\Models\LoanOffer;
use App\Notifications\NewLoanOfferNotification;

class BankLoanController extends Controller
{
    /**
     * عرض طلبات التمويل المرتبطة بموظف البنك الحالي (أو المتاحة للعروض)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = LoanRequest::with(['user', 'product', 'offers' => function ($q) use ($user) {
            $q->where('bank_user_id', $user->id);
        }])->latest();

        // يمكن لاحقًا تضييق النتائج حسب البنك أو الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $requests = $query->paginate(20)->appends($request->query());

        return view('bank.loans.requests', compact('requests'));
    }

    /**
     * إنشاء عرض تمويل جديد من موظف البنك لطلب محدد
     */
    public function storeOffer(Request $request, LoanRequest $loanRequest)
    {
        $user = Auth::user();

        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'profit_rate' => 'required|numeric|min:0',
            'term_months' => 'required|integer|min:1',
            'fees' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $amount = $data['amount'];
        $profitRate = $data['profit_rate'];
        $termMonths = $data['term_months'];

        // حساب تقريبي للقسط الشهري (بسيط فقط كمؤشر، يمكن تعديله لاحقًا)
        $totalProfit = $amount * ($profitRate / 100) * ($termMonths / 12);
        $totalAmount = $amount + $totalProfit;
        $monthlyPayment = $termMonths > 0 ? $totalAmount / $termMonths : null;

        $offer = LoanOffer::create([
            'loan_request_id' => $loanRequest->id,
            'bank_user_id' => $user->id,
            'amount' => $amount,
            'profit_rate' => $profitRate,
            'term_months' => $termMonths,
            'monthly_payment' => $monthlyPayment,
            'fees' => $data['fees'] ?? 0,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        if ($loanRequest->user) {
            $loanRequest->user->notify(new NewLoanOfferNotification($loanRequest, $offer));
        }

        return redirect()->route('bank.loans.requests')
            ->with('success', 'تم إرسال عرض التمويل بنجاح');
    }
}
