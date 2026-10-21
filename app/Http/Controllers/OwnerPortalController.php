<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\OwnerPayout;

class OwnerPortalController extends Controller
{
    /**
     * كشف حساب المالك — يفتح برابط موقّع بدون تسجيل دخول
     */
    public function show(Owner $owner)
    {
        $owner->load(['facility', 'products.translations']);

        $contracts = Contract::where('owner_id', $owner->id)
            ->with(['product.translations', 'tenant.translations', 'user'])
            ->latest()
            ->get();

        $payouts = OwnerPayout::where('owner_id', $owner->id)
            ->latest('paid_at')
            ->get();

        $collected = Payment::whereIn('contract_id', $contracts->pluck('id'))
            ->where('status', 'confirmed')->sum('amount');
        $paid = $payouts->sum('amount');
        $ownerShare = $collected * (1 - ($owner->commission_rate ?? 0) / 100);

        $totals = [
            'properties' => $owner->products->count(),
            'collected' => $collected,
            'paid' => $paid,
            'due' => $ownerShare - $paid,
        ];

        return view('owner_portal.show', compact('owner', 'contracts', 'payouts', 'totals'));
    }
}
