@props([
    'locales' => [],
    'namePrefix' => 'translations',
    'fields' => [],
    'items' => null,
    'addLabel' => 'إضافة ترجمة',
    'removeLabel' => 'حذف الترجمة',
    'minItems' => 1,
    'maxItems' => null,
    'errorBag' => 'default',
    'wrapperClass' => ''
])

@php
    $rid = $attributes->get('id', 'tr_' . uniqid());

    $normalizedLocales = [];
    foreach ($locales as $k => $v) {
        if (is_array($v)) {
            $normalizedLocales[] = ['code' => $k, 'label' => ($v['native'] ?? $v['native_name'] ?? $v['name'] ?? strtoupper($k))];
        } else {
            $normalizedLocales[] = ['code' => is_string($k) ? $k : (string) $v, 'label' => is_string($v) ? $v : strtoupper((string) $v)];
        }
    }

    $itemsOld = old($namePrefix);
    $initialItems = is_array($items) ? $items : [];
    $items = is_array($itemsOld) && count($itemsOld) ? $itemsOld : (count($initialItems) ? $initialItems : [[]]);

    if (!is_null($maxItems)) {
        $items = array_slice($items, 0, (int) $maxItems);
    }

    $fields = is_array($fields) ? $fields : [];
@endphp

<div
    class="{{ $wrapperClass }}"
    data-tr-repeater="{{ $rid }}"
    data-tr-name-prefix="{{ $namePrefix }}"
    data-tr-min="{{ (int) $minItems }}"
    data-tr-max="{{ is_null($maxItems) ? '' : (int) $maxItems }}"
    data-tr-duplicate-msg="{{ __('هذه اللغة مستخدمة بالفعل، اختر لغة أخرى') }}"
>
    <div class="vstack gap-3" data-tr-items>
        @foreach($items as $i => $it)
            @include('components.partials.translations-repeater-item', [
                'index' => $i,
                'item' => $it,
                'fields' => $fields,
                'namePrefix' => $namePrefix,
                'normalizedLocales' => $normalizedLocales,
                'removeLabel' => $removeLabel,
                'errorBag' => $errorBag,
                'template' => false,
            ])
        @endforeach
    </div>

    <div class="mt-3">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-tr-add>
            {{ $addLabel }}
        </button>
    </div>
</div>

<template id="{{ $rid }}_tpl">
    @include('components.partials.translations-repeater-item', [
        'index' => '__INDEX__',
        'item' => [],
        'fields' => $fields,
        'namePrefix' => $namePrefix,
        'normalizedLocales' => $normalizedLocales,
        'removeLabel' => $removeLabel,
        'errorBag' => $errorBag,
        'template' => true,
    ])
</template>

@push('scripts')
    @once
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/js/app.js'])
        @else
            <script src="{{ asset('js/components/translations-repeater.js') }}"></script>
        @endif
    @endonce
@endpush





