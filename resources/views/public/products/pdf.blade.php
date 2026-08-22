<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $product->title }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            direction: rtl;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            max-width: 180mm;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2c7a7b;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2c7a7b;
            font-size: 22px;
        }
        .header .address {
            color: #666;
            font-size: 13px;
            margin-top: 5px;
        }
        .badges {
            margin-top: 8px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            color: #fff;
            font-size: 10px;
            margin-left: 4px;
        }
        .badge-featured { background: #d69e2e; }
        .badge-verified { background: #38a169; }
        .hero-image {
            width: 100%;
            max-height: 100mm;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 18px;
        }
        .section h2 {
            color: #2c7a7b;
            font-size: 16px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .price {
            font-size: 20px;
            color: #2c7a7b;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.info-table th,
        table.info-table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: right;
        }
        table.info-table th {
            background: #f7fafc;
            color: #2c7a7b;
            width: 35%;
        }
        .offer {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 8px;
        }
        .offer .price {
            font-size: 16px;
            margin-bottom: 0;
        }
        .facility {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
        }
        .attributes {
            width: 100%;
        }
        .attribute {
            display: inline-block;
            width: 30%;
            padding: 6px;
            margin: 4px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            text-align: center;
        }
        .features {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .features li {
            padding: 4px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>{{ $product->title }}</h1>
            <div class="address">{{ $product->address ?? __('products.property_card.location_unknown') }}</div>
            <div class="badges">
                @if($product->is_featured)
                    <span class="badge badge-featured">{{ __('products.property_card.featured') }}</span>
                @endif
                @if($product->is_verified)
                    <span class="badge badge-verified">{{ __('products.property_card.verified') }}</span>
                @endif
            </div>
        </div>

        @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->title }}" class="hero-image">
        @endif

        <div class="section">
            <h2>نظرة عامة</h2>
            <table class="info-table">
                <tr>
                    <th>الفئة</th>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>المنشأة</th>
                    <td>{{ $product->facility?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>المدينة</th>
                    <td>{{ $product->city?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>الحالة</th>
                    <td>
                        @foreach($product->statuses as $status)
                            {{ $status->getTranslatedName() }}{{ !$loop->last ? '، ' : '' }}
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>

        @if($product->activeOffers && $product->activeOffers->count() > 0)
            <div class="section">
                <h2>الأسعار والعروض</h2>
                @foreach($product->activeOffers as $offer)
                    <div class="offer">
                        <div class="price">{{ number_format($offer->price, 0) }} ريال</div>
                        <div>
                            @if($offer->offer_type === 'sale')
                                للبيع
                            @elseif(str_starts_with($offer->offer_type, 'rent_'))
                                {{ ['rent_daily' => 'إيجار يومي', 'rent_monthly' => 'إيجار شهري', 'rent_yearly' => 'إيجار سنوي'][$offer->offer_type] ?? $offer->offer_type }}
                            @else
                                {{ $offer->offer_type }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($product->attributes && $product->attributes->count() > 0)
            <div class="section">
                <h2>التفاصيل</h2>
                <div class="attributes">
                    @foreach($product->attributes as $attribute)
                        <div class="attribute">
                            <strong>{{ $attribute->getTranslatedName() }}</strong>
                            <div>{{ $attribute->pivot->value ?? '-' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($product->features && $product->features->count() > 0)
            <div class="section">
                <h2>المميزات</h2>
                <ul class="features">
                    @foreach($product->features as $feature)
                        <li>{{ $feature->getTranslatedName() }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($product->description)
            <div class="section">
                <h2>الوصف</h2>
                <p>{!! nl2br(e($product->description)) !!}</p>
            </div>
        @endif

        @if($product->facility)
            <div class="section">
                <h2>بيانات المنشأة</h2>
                <div class="facility">
                    <strong>{{ $product->facility->name }}</strong>
                    <p>{{ $product->facility->address ?? '' }}</p>
                </div>
            </div>
        @endif

        <div class="footer">
            {{ $product->title }} · {{ url('/products/'.$product->id) }}
        </div>
    </div>
</body>
</html>
