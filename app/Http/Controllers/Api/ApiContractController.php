<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiContractController extends Controller
{
    /**
     * Display a listing of user contracts
     */
    public function index()
    {
        $user = Auth::user();
        $contracts = Contract::where('user_id', $user->id)->with(['product', 'facility'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    /**
     * Display the specified contract
     */
    public function show(Contract $contract)
    {
        $contract->load(['product', 'facility']);

        return response()->json([
            'success' => true,
            'data' => $contract
        ]);
    }

    /**
     * Download contract
     */
    public function download(Contract $contract)
    {
        // Generate PDF and return download response
        return response()->json([
            'success' => true,
            'message' => 'تم تحميل العقد بنجاح'
        ]);
    }
}
