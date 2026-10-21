<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AI\TenderExtractionService;

class TenderComplianceController extends Controller
{
    public function __invoke(Request $request, TenderExtractionService $extraction)
    {
        $tenderText = (string) $request->input('tender_text', '');
        $result = null;
        $error = null;

        if ($request->isMethod('post')) {
            $data = $request->validate(['tender_text' => 'required|string|min:20|max:100000']);
            $tenderText = $data['tender_text'];

            try {
                $result = $extraction->analyze($tenderText);
            } catch (\Throwable $e) {
                $error = __('تعذّر تحليل المناقصة، حاول لاحقاً');
            }
        }

        return view('public.tender_compliance', [
            'tenderText' => $tenderText,
            'result' => $result,
            'error' => $error,
        ]);
    }
}
