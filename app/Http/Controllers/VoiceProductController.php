<?php

namespace App\Http\Controllers;

use App\Services\AI\ProductAiService;
use Illuminate\Http\Request;

class VoiceProductController extends Controller
{
    protected ProductAiService $ai;

    public function __construct(ProductAiService $ai)
    {
        $this->ai = $ai;
    }

    /**
     * استقبال نص عقاري وإرجاع الحقول المستخرجة من الذكاء الاصطناعي.
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:3000',
        ]);

        try {
            $data = $this->ai->extractFromVoice($request->input('text'));
            return response()->json(['data' => $data], 200);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'تعذر تحليل النص. تحقق من إعداد مزود الذكاء الاصطناعي.'], 422);
        }
    }

    public function analyzeDocument(Request $request)
    {
        $validated = $request->validate([
            'document' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        try {
            return response()->json([
                'data' => $this->ai->extractFromDocument($validated['document']),
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'تعذر قراءة الصورة. تأكد من وضوحها وإعداد مزود الذكاء الاصطناعي.'], 422);
        }
    }
}
