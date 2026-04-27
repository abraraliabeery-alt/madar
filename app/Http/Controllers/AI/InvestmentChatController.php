<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Services\AI\ChatGatewayService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class InvestmentChatController extends Controller
{
    public function __construct(private readonly ChatGatewayService $chatGateway)
    {
    }

    public function handle(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message'  => 'required|string|max:4000',
            'provider' => 'nullable|string',
        ]);

        $userMessage = $data['message'];
        $provider = $data['provider'] ?? null;

        try {
            $reply = $this->chatGateway->chat($userMessage, $provider);

            return response()->json([
                'reply' => $reply,
            ]);
        } catch (RuntimeException $e) {
            $msg = $e->getMessage();

            if (str_contains($msg, 'configuration is missing')) {
                Log::warning('AI investment chat configuration missing', [
                    'provider' => $provider,
                    'exception' => $e,
                ]);

                return response()->json([
                    'error' => 'AI_CONFIG_MISSING',
                    'message' => 'خدمة الذكاء الاصطناعي غير مفعّلة حالياً بسبب نقص الإعدادات. تأكد من إعداد مفاتيح المزود في ملف .env ثم أعد المحاولة.',
                ], 503);
            }

            Log::error('AI investment chat runtime error: '.$msg, [
                'provider' => $provider,
                'exception' => $e,
            ]);

            return response()->json([
                'error' => 'AI_RUNTIME_ERROR',
                'message' => 'تعذر معالجة طلبك حالياً. يرجى المحاولة مرة أخرى.',
            ], 503);
        } catch (RequestException $e) {
            $status = $e->response?->status();
            $providerLabel = $provider ?: (string) config('ai_chat.default');

            Log::error('AI investment chat provider request failed', [
                'provider' => $providerLabel,
                'status' => $status,
                'exception' => $e,
                'response_body' => $e->response?->body(),
            ]);

            if ($status === 401 || $status === 403) {
                return response()->json([
                    'error' => 'AI_AUTH_FAILED',
                    'message' => 'فشل التحقق من مزود الذكاء الاصطناعي. تأكد من صحة مفتاح API وإعدادات المزود ثم أعد المحاولة.',
                ], 503);
            }

            if ($status === 429) {
                return response()->json([
                    'error' => 'AI_RATE_LIMITED',
                    'message' => 'تم تجاوز حد الطلبات لدى مزود الذكاء الاصطناعي. انتظر قليلاً ثم أعد المحاولة.',
                ], 429);
            }

            return response()->json([
                'error' => 'AI_UNAVAILABLE',
                'message' => 'خدمة الذكاء الاصطناعي غير متاحة حالياً. يرجى المحاولة لاحقاً.',
            ], 503);
        } catch (\Throwable $e) {
            Log::error('AI investment chat failed: '.$e->getMessage(), [
                'provider' => $provider,
                'exception' => $e,
            ]);

            return response()->json([
                'error' => 'AI_UNAVAILABLE',
                'message' => 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.',
            ], 503);
        }
    }
}
