<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عرض سعر {{ $quote->quote_number }}</title>
    <style>
        @page { margin: 20mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; direction: rtl; color: #1f2937; }
        .header { border-bottom: 2px solid {{ $customization['colors']['primary'] ?? '#0d9488' }}; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: {{ $customization['colors']['primary'] ?? '#0d9488' }}; }
        .meta { margin-bottom: 20px; }
        .meta p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: right; }
        th { background-color: #f3f4f6; }
        .text-left { text-align: left; }
        .totals { margin-top: 10px; width: 40%; margin-left: 0; margin-right: auto; }
        .totals td { border: none; padding: 4px; }
        .total { font-size: 14px; font-weight: bold; color: {{ $customization['colors']['primary'] ?? '#0d9488' }}; }
        .section-title { font-weight: bold; margin: 20px 0 8px; color: {{ $customization['colors']['primary'] ?? '#0d9488' }}; }
        .note { font-size: 11px; color: #6b7280; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $facility->name }}</h2>
        <p style="margin:0;">عرض سعر رقم: {{ $quote->quote_number }}</p>
    </div>

    <div class="meta">
        <p><strong>عنوان العرض:</strong> {{ $quote->title }}</p>
        <p><strong>العميل:</strong> {{ $quote->client_name ?: '—' }}</p>
        @if($quote->client_email)<p><strong>البريد:</strong> {{ $quote->client_email }}</p>@endif
        @if($quote->client_phone)<p><strong>الجوال:</strong> {{ $quote->client_phone }}</p>@endif
        <p><strong>التاريخ:</strong> {{ $quote->created_at->format('Y-m-d') }}</p>
    </div>

    <div class="section-title">بنود السعر</div>
    <table>
        <thead>
            <tr>
                <th>البند</th>
                <th>الوصف</th>
                <th>الكمية</th>
                <th>الوحدة</th>
                <th>سعر الوحدة</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description ?: '—' }}</td>
                    <td>{{ number_format($item->quantity, 2) }}</td>
                    <td>{{ $item->unit ?: '—' }}</td>
                    <td>{{ number_format($item->unit_price, 2) }} ر.س</td>
                    <td>{{ number_format($item->line_total, 2) }} ر.س</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals text-left">
        <tr>
            <td>المجموع الفرعي</td>
            <td>{{ number_format($quote->subtotal, 2) }} ر.س</td>
        </tr>
        <tr>
            <td>الخصومات</td>
            <td>{{ number_format($quote->discounts_total, 2) }} ر.س</td>
        </tr>
        <tr class="total">
            <td>الإجمالي</td>
            <td>{{ number_format($quote->total, 2) }} ر.س</td>
        </tr>
    </table>

    @if($quote->terms->count())
        <div class="section-title">الشروط والأحكام</div>
        <table>
            <thead>
                <tr>
                    <th>البند</th>
                    <th>الشرط</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->terms as $term)
                    <tr>
                        <td>{{ $term->title ?: '—' }}</td>
                        <td>{{ $term->content }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($quote->discounts->count())
        <div class="section-title">الخصومات</div>
        <table>
            <thead>
                <tr>
                    <th>الخصم</th>
                    <th>القيمة</th>
                    <th>شرط الاستحقاق</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->discounts as $discount)
                    <tr>
                        <td>{{ $discount->name }}</td>
                        <td>
                            {{ number_format($discount->method === \App\Enums\DiscountMethod::Percentage ? $discount->value : $discount->calculateAmount($quote->subtotal), 2) }}
                            {{ $discount->method === \App\Enums\DiscountMethod::Percentage ? '%' : 'ر.س' }}
                        </td>
                        <td>{{ $discount->condition ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($quote->notes)
        <div class="note">
            <strong>ملاحظات:</strong> {{ $quote->notes }}
        </div>
    @endif
</body>
</html>
