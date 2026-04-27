<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LoanRequest;
use App\Models\LoanOffer;
use App\Models\LoanClaim;
use App\Models\Product;
use App\Models\User;

class ApiLoanController extends Controller
{
    // إنشاء طلب تمويل عقاري من العميل
    public function storeRequest(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'notes' => 'nullable|string',
        ]);

        $userId = Auth::id();

        $loan = LoanRequest::create([
            'user_id' => $userId,
            'product_id' => $data['product_id'] ?? null,
            'status' => 'new',
            'sla_due_at' => now()->addHours(24), // SLA 24 ساعة
            'notes' => $data['notes'] ?? null,
        ]);

        // تحديث الحالة إلى dispatched مباشرة لبثه على الموظفين
        $loan->update(['status' => 'dispatched']);

        return response()->json(['loan_request' => $loan], 201);
    }

    // مطالبة/حجز الطلب (Claim) لمدة 30 دقيقة
    public function claim(Request $request, LoanRequest $loanRequest)
    {
        // تحقق صلاحية دور الموظف البنكي يمكن وضع Middleware لاحقًا
        $user = Auth::user();

        // إن كان هناك Claim نشط غير منتهي، نرفض
        $activeClaim = $loanRequest->claims()
            ->where('status', 'active')
            ->where(function($q){
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();
        if ($activeClaim) {
            return response()->json(['message' => 'الطلب محجوز حاليًا'], 409);
        }

        $claim = $loanRequest->claims()->create([
            'bank_user_id' => $user->id,
            'expires_at' => now()->addMinutes(30),
            'status' => 'active',
        ]);

        // دخول مرحلة التنافس
        if ($loanRequest->status === 'dispatched') {
            $loanRequest->update(['status' => 'competing']);
        }

        return response()->json(['claim' => $claim]);
    }

    // تحرير/إلغاء الـClaim
    public function release(Request $request, LoanRequest $loanRequest)
    {
        $user = Auth::user();
        $claim = $loanRequest->claims()
            ->where('bank_user_id', $user->id)
            ->where('status', 'active')
            ->first();
        if (!$claim) {
            return response()->json(['message' => 'لا يوجد Claim نشط'], 404);
        }
        $claim->update([
            'status' => 'released',
            'released_at' => now(),
        ]);
        return response()->json(['claim' => $claim]);
    }

    // إنشاء/تحديث عرض من موظف بنك
    public function submitOffer(Request $request, LoanRequest $loanRequest)
    {
        $data = $request->validate([
            'amount' => 'nullable|numeric',
            'profit_rate' => 'nullable|numeric',
            'term_months' => 'nullable|integer',
            'monthly_payment' => 'nullable|numeric',
            'fees' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);
        $user = Auth::user();

        $offer = $loanRequest->offers()->create(array_merge($data, [
            'bank_user_id' => $user->id,
            'status' => 'submitted',
        ]));

        // تغيير حالة الطلب إلى عروض مستلمة
        if (!in_array($loanRequest->status, ['offers_received','selected','advising','completed'])) {
            $loanRequest->update(['status' => 'offers_received']);
        }

        return response()->json(['offer' => $offer], 201);
    }

    // اختيار عرض من العميل
    public function chooseOffer(Request $request, LoanRequest $loanRequest, LoanOffer $offer)
    {
        $userId = Auth::id();
        if ($loanRequest->user_id !== $userId) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }
        if ($offer->loan_request_id !== $loanRequest->id) {
            return response()->json(['message' => 'العرض لا ينتمي لهذا الطلب'], 422);
        }

        $loanRequest->update([
            'chosen_offer_id' => $offer->id,
            'status' => 'selected',
        ]);

        return response()->json(['loan_request' => $loanRequest->fresh('chosenOffer')]);
    }

    // تعيين مستشار بعد اختيار العرض
    public function assignAdvisor(Request $request, LoanRequest $loanRequest)
    {
        $data = $request->validate([
            'advisor_user_id' => 'required|exists:users,id',
        ]);

        $loanRequest->update([
            'assigned_advisor_id' => $data['advisor_user_id'],
            'status' => 'advising',
        ]);

        return response()->json(['loan_request' => $loanRequest->fresh('advisor')]);
    }
}
