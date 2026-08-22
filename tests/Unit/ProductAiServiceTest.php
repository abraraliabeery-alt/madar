<?php

namespace Tests\Unit;

use App\Services\AI\ProductAiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductAiServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('ai_chat.providers.openai', [
            'api_key' => 'test-key',
            'base_url' => 'https://ai.test/v1',
            'model' => 'test-model',
        ]);
    }

    public function test_it_normalizes_extracted_property_data(): void
    {
        Http::fake([
            'https://ai.test/v1/chat/completions' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'price' => '1250000',
                            'rooms' => 4,
                            'city' => 'الرياض',
                            'confidence' => 0.91,
                            'evidence' => ['price' => 'بسعر مليون وربع'],
                            'invented_field' => 'ignored',
                        ], JSON_UNESCAPED_UNICODE),
                    ],
                ]],
            ]),
        ]);

        $result = app(ProductAiService::class)->extractFromVoice('فيلا في الرياض بسعر مليون وربع');

        $this->assertSame(1250000.0, $result['price']);
        $this->assertSame(4.0, $result['rooms']);
        $this->assertSame('الرياض', $result['city']);
        $this->assertSame(0.91, $result['confidence']);
        $this->assertArrayNotHasKey('invented_field', $result);
        $this->assertNull($result['area']);
    }

    public function test_it_limits_marketing_content_to_supported_channels(): void
    {
        Http::fake([
            'https://ai.test/v1/chat/completions' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'title' => 'فيلا للبيع',
                            'description' => 'وصف العقار',
                            'whatsapp' => 'رسالة واتساب',
                            'x' => 'منشور X',
                            'instagram' => 'منشور إنستغرام',
                            'email_subject' => 'فرصة عقارية',
                            'email_body' => 'نص البريد',
                            'unsupported' => 'ignored',
                        ], JSON_UNESCAPED_UNICODE),
                    ],
                ]],
            ]),
        ]);

        $result = app(ProductAiService::class)->generateMarketingContent(['title' => 'فيلا']);

        $this->assertSame('فيلا للبيع', $result['title']);
        $this->assertSame('رسالة واتساب', $result['whatsapp']);
        $this->assertArrayNotHasKey('unsupported', $result);
        $this->assertCount(7, $result);
    }
}
