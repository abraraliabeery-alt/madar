<?php

namespace App\Services\AI;

use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductRequest;

class AdminAiAssistantService
{
    public function __construct(private StructuredAiService $ai)
    {
    }

    public function answer(string $question): array
    {
        $context = $this->context();
        $answer = $this->ai->text(<<<'PROMPT'
أنت مساعد تشغيل لمنصة عقارية سعودية. أجب اعتمادًا حصريًا على بيانات المنصة المرسلة، ولا تخترع أرقامًا أو حالات. مهمتك تلخيص الوضع واقتراح خطوات عملية قصيرة للمشغل. أنت للقراءة والاستشارة فقط: لا تدّع أنك حفظت أو أرسلت أو حذفت أو عدّلت أي شيء. إذا طلب المستخدم تنفيذ إجراء، وضح له مكان الإجراء المناسب داخل لوحة التحكم. اكتب بالعربية باختصار.
PROMPT, [
            'question' => $question,
            'platform_context' => $context,
        ], 0.2);

        return [
            'answer' => $answer,
            'context_generated_at' => now()->toIso8601String(),
            'quick_links' => [
                ['label' => 'العقارات', 'url' => route('admin.products.index')],
                ['label' => 'طلبات العملاء', 'url' => route('admin.product-requests.index')],
                ['label' => 'إعدادات PDF', 'url' => route('admin.pdf.settings.edit')],
            ],
        ];
    }

    private function context(): array
    {
        $incomplete = Product::query()
            ->with(['translations', 'category.translations'])
            ->where(function ($query) {
                $query->whereNull('main_image')
                    ->orWhereNull('city_id')
                    ->orWhereNull('category_id')
                    ->orWhereDoesntHave('translations', fn ($translation) => $translation->whereNotNull('title')->where('title', '!=', ''))
                    ->orWhereDoesntHave('offers');
            })
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn (Product $product) => [
                'id' => (int) $product->id,
                'title' => $product->getTranslatedTitle('ar') ?: 'بدون عنوان',
                'missing' => array_values(array_filter([
                    ! $product->main_image ? 'صورة الغلاف' : null,
                    ! $product->city_id ? 'المدينة' : null,
                    ! $product->category_id ? 'الفئة' : null,
                    $product->translations->isEmpty() ? 'العنوان' : null,
                    ! $product->offers()->exists() ? 'العرض والسعر' : null,
                ])),
            ]);

        return [
            'products' => [
                'total' => Product::count(),
                'active' => Product::where('is_active', true)->count(),
                'without_offers' => Product::whereDoesntHave('offers')->count(),
                'without_main_image' => Product::whereNull('main_image')->count(),
                'incomplete_samples' => $incomplete->all(),
            ],
            'customer_requests' => [
                'open' => ProductRequest::open()->count(),
                'new' => ProductRequest::where('status', ProductRequest::STATUS_NEW)->count(),
                'urgent' => ProductRequest::open()->where('priority', ProductRequest::PRIORITY_URGENT)->count(),
            ],
            'bookings' => [
                'total' => Booking::count(),
                'created_this_month' => Booking::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count(),
            ],
        ];
    }
}
