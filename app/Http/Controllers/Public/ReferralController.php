<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        $referrals = $user->referrals()
            ->with('referredUser')
            ->latest()
            ->paginate(20);

        $totalReferred = $user->referredUsers()->count();
        $totalConverted = $user->referrals()->where('status', 'converted')->count();

        return view('public.referrals.index', compact(
            'user',
            'referrals',
            'totalReferred',
            'totalConverted'
        ));
    }
}
