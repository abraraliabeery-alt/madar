@if(!$pdfOnlySlideKey || $pdfOnlySlideKey === 'details')
    @if(count($summaryItems) || ($topAttrs && $topAttrs->count()))
    <div class="slide">
        <h2>{{ $slideTitle ?? __('pdf.slides.summary.title') }}</h2>
        <div class="section-lead">{{ __('pdf.slides.summary.lead') }}</div>

        @if(count($summaryItems))
            <table class="kpi-grid" style="margin-top:0;">
                @foreach(collect($summaryItems)->chunk(3) as $row)
                    <tr>
                        @foreach($row as $item)
                            <td>
                                <div class="kpi">
                                    <div class="kpi-label">{{ $item['label'] }}</div>
                                    <div class="kpi-value">{{ $item['value'] }}</div>
                                </div>
                            </td>
                        @endforeach
                        @for($i = $row->count(); $i < 3; $i++)
                            <td></td>
                        @endfor
                    </tr>
                @endforeach
            </table>
        @endif

        @if($topAttrs && $topAttrs->count())
            <div style="margin-top:6mm;">
                <div class="card-title">{{ __('pdf.slides.summary.top_specs') }}</div>
                <table class="grid-2">
                    @foreach($topAttrs->chunk(2) as $chunk)
                        <tr>
                            @foreach($chunk as $attribute)
                                <td>
                                    <div class="card" style="padding:5mm;">
                                        <div class="meta-row">
                                            <div class="meta-content" style="padding-inline-start:0;">
                                                <div class="meta-label">{{ $attribute->getTranslatedName() ?: '-' }}</div>
                                                <div class="meta-value">{{ (string) ($attribute->pivot->value ?? '-') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                            @if($chunk->count() < 2)
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    </div>
    @endif

    @if($product->description)
        <div class="slide">
            <h2>{{ __('pdf.slides.description.title') }}</h2>
            <div class="profile-description">{!! nl2br(e($product->description)) !!}</div>
        </div>
    @endif

    @if(!empty($galleryImageDataUris))
        @foreach(collect($galleryImageDataUris)->chunk(4) as $group)
        <div class="slide">
            <h2>{{ __('pdf.slides.gallery.title') }}</h2>
            @if($loop->first)
                <div class="section-lead">{{ __('pdf.slides.gallery.lead') }}</div>
            @endif

            <table class="map-grid">
                @foreach($group->chunk(2) as $chunk)
                    <tr>
                        @foreach($chunk as $imgUri)
                            <td>
                                <div class="card" style="padding:3mm;">
                                    @php
                                        $isImg = is_string($imgUri) && str_starts_with($imgUri, 'data:image/');
                                    @endphp
                                    @if($isImg)
                                        <img class="map-image" src="{{ $imgUri }}" alt="{{ $product->title }}" />
                                    @else
                                        <div class="img-placeholder">{{ __('pdf.slides.gallery.image_unavailable') }}</div>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                        @if($chunk->count() < 2)
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
        @endforeach
    @endif

    @php
        $attributeOrder = is_array(($pdf['attribute_order'] ?? null)) ? ($pdf['attribute_order'] ?? []) : [];
        $attributeGroups = is_array(($pdf['attribute_groups'] ?? null)) ? ($pdf['attribute_groups'] ?? []) : [];
        $productAttributes = $product->attributes ?? collect();

        // Filter to attributes linked to the product's category or its parent (or global)
        $categoryIds = collect([(int) ($product->category_id)]);
        if ($product->category?->parent_id) {
            $categoryIds->push((int) $product->category->parent_id);
        }

        if ($productAttributes && $productAttributes->count()) {
            $productAttributes = $productAttributes
                ->filter(fn ($a) => is_null($a->category_id) || $categoryIds->contains((int) $a->category_id))
                ->values();

            if (!empty($attributeOrder)) {
                $orderMap = array_flip(array_map('intval', $attributeOrder));
                $productAttributes = $productAttributes
                    ->sortBy(function ($a) use ($orderMap) {
                        return $orderMap[$a->id] ?? 100000 + (int) $a->id;
                    })
                    ->values();
            }
        }

        $groupedSlides = [];
        if (!empty($attributeGroups) && $productAttributes && $productAttributes->count()) {
            foreach ($attributeGroups as $group) {
                $ids = array_map('intval', is_array(($group['attributes'] ?? null)) ? $group['attributes'] : []);
                $orderMap = array_flip($ids);
                $attrs = $productAttributes
                    ->filter(fn ($a) => in_array((int) $a->id, $ids, true))
                    ->sortBy(fn ($a) => $orderMap[(int) $a->id] ?? 100000 + (int) $a->id)
                    ->values();
                if ($attrs->count()) {
                    $groupedSlides[] = [
                        'name'       => $group['name'] ?? '',
                        'attributes' => $attrs,
                    ];
                }
            }
        }
    @endphp

    @if(!empty($groupedSlides))
        @foreach($groupedSlides as $group)
            @foreach($group['attributes']->chunk(2) as $chunk)
            <div class="slide">
                @if($loop->first)
                    <h2>{{ $group['name'] ?: __('pdf.slides.details.title') }}</h2>
                    <div class="section-lead">{{ __('pdf.slides.details.lead') }}</div>
                @endif

                <table class="attr-grid" style="margin: auto 0;">
                    @foreach($chunk->chunk(2) as $row)
                        <tr>
                            @foreach($row as $attribute)
                                @php
                                    $key = $attribute->key ?? null;
                                    $iconKey = $key ? ($attrIconByKey[$key] ?? null) : null;
                                    $icon = $iconKey ? ($svgIcons[$iconKey] ?? null) : null;
                                    $name = $attribute->getTranslatedName() ?: '-';
                                    $symbol = $attribute->getTranslatedSymbol() ?: null;
                                    $value = $attribute->pivot->value ?? '-';
                                @endphp
                                <td>
                                    <div class="card">
                                        <div class="attr-row">
                                            <div class="attr-left">
                                                <span class="icon">{!! $icon ?? $svgIcons['grid'] !!}</span>
                                            </div>
                                            <div class="attr-right">
                                                <div class="attr-label">{{ $name }}</div>
                                                <div class="attr-value">{{ $value }}</div>
                                                @if($symbol)
                                                    <div class="attr-sub">{{ $symbol }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                            @if($row->count() < 2)
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
            @endforeach
        @endforeach
    @elseif($productAttributes && $productAttributes->count())
        @foreach($productAttributes->chunk(2) as $chunk)
        <div class="slide">
            <h2>{{ __('pdf.slides.details.title') }}</h2>
            @if($loop->first)
                <div class="section-lead">{{ __('pdf.slides.details.lead') }}</div>
            @endif

            <table class="attr-grid" style="margin: auto 0;">
                @foreach($chunk->chunk(2) as $row)
                    <tr>
                        @foreach($row as $attribute)
                            @php
                                $key = $attribute->key ?? null;
                                $iconKey = $key ? ($attrIconByKey[$key] ?? null) : null;
                                $icon = $iconKey ? ($svgIcons[$iconKey] ?? null) : null;
                                $name = $attribute->getTranslatedName() ?: '-';
                                $symbol = $attribute->getTranslatedSymbol() ?: null;
                                $value = $attribute->pivot->value ?? '-';
                            @endphp
                            <td>
                                <div class="card">
                                    <div class="attr-row">
                                        <div class="attr-left">
                                            <span class="icon">{!! $icon ?? $svgIcons['grid'] !!}</span>
                                        </div>
                                        <div class="attr-right">
                                            <div class="attr-label">{{ $name }}</div>
                                            <div class="attr-value">{{ $value }}</div>
                                            @if($symbol)
                                                <div class="attr-sub">{{ $symbol }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @endforeach
                        @if($row->count() < 2)
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
        @endforeach
    @endif

    @php
        $yesNo = fn ($flag) => $flag ? __('pdf.common.yes') : __('pdf.common.no');

        $extraRaw = [
            [__('pdf.fields.price'), $product->getFormattedPrice()],
            [__('pdf.fields.available_from'), $product->available_from ? $product->available_from->format('Y-m-d') : null],
            [__('pdf.fields.available_to'), $product->available_to ? $product->available_to->format('Y-m-d') : null],
            [__('pdf.fields.contact_phone'), $product->contact_phone],
            [__('pdf.fields.contact_email'), $product->contact_email],
            [__('pdf.fields.views'), $product->views_count !== null ? $product->views_count : null],
            [__('pdf.fields.rating'), $product->rating ? number_format($product->rating, 1) . ' / 5' : null],
            [__('pdf.fields.featured'), $product->is_featured !== null ? $yesNo($product->is_featured) : null],
            [__('pdf.fields.verified'), $product->is_verified !== null ? $yesNo($product->is_verified) : null],
            [__('pdf.fields.city'), \App\Helpers\LanguageHelper::getCityName($product->city) ?: $product->city?->name],
            [__('pdf.fields.neighborhood'), $product->neighborhood?->name],
            [__('pdf.fields.street'), $product->street?->name],
            [__('pdf.fields.building'), $product->building?->name],
            [__('pdf.fields.project'), $product->project?->name],
            [__('pdf.fields.package'), $product->package?->name],
            [__('pdf.fields.owner'), $product->owner?->name],
            [__('pdf.fields.seller'), $product->seller?->name],
        ];
        $extraRows = array_values(array_filter($extraRaw, fn ($r) => filled($r[1])));
    @endphp

    @if(count($extraRows))
    <div class="slide additional-info">
        <h2>{{ __('pdf.slides.additional.title') }}</h2>
        <div class="section-lead">{{ __('pdf.slides.additional.lead') }}</div>

        <table class="info-grid">
            @foreach(collect($extraRows)->chunk(3) as $chunk)
                <tr>
                    @foreach($chunk as $row)
                        <td>
                            <strong>{{ $row[0] }}</strong>
                            <div>{{ $row[1] ?? '-' }}</div>
                        </td>
                    @endforeach
                    @for($i = $chunk->count(); $i < 3; $i++)
                        <td></td>
                    @endfor
                </tr>
            @endforeach
        </table>
    </div>
    @endif
@endif
