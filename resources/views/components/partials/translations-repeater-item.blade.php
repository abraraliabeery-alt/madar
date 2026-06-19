@php
    $index = $index ?? 0;
    $item = $item ?? [];
    $fields = $fields ?? [];
    $namePrefix = $namePrefix ?? 'translations';
    $normalizedLocales = $normalizedLocales ?? [];
    $removeLabel = $removeLabel ?? 'حذف الترجمة';
    $errorBag = $errorBag ?? 'default';
    $template = $template ?? false;
@endphp

<div class="card shadow-sm" data-tr-item data-index="{{ $index }}">
    <div class="card-body">
        <div class="d-flex align-items-start justify-content-between gap-2">
            <div class="flex-grow-1">
                @if($template)
                    <div class="">
                        <label class="form-label" for="{{ $namePrefix }}[{{ $index }}][locale]">اللغة</label>
                        <select
                            class="form-select"
                            name="{{ $namePrefix }}[{{ $index }}][locale]"
                            id="{{ $namePrefix }}[{{ $index }}][locale]"
                        >
                            <option value="">اختر اللغة</option>
                            @foreach($normalizedLocales as $loc)
                                <option value="{{ $loc['code'] }}">{{ $loc['label'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback d-none" data-tr-invalid-for="locale"></div>
                    </div>
                @else
                    @php
                        $localeName = $namePrefix.'['.$index.'][locale]';
                        $localeErrorName = $namePrefix.'.'.$index.'.locale';
                        $localeHasError = $errors->has($localeErrorName, $errorBag);
                        $localeCurrent = data_get($item, 'locale') ?? old($localeName);
                    @endphp
                    <div class="">
                        <label class="form-label" for="{{ $localeName }}">اللغة</label>
                        <select
                            class="form-select{{ $localeHasError ? ' is-invalid' : '' }}"
                            name="{{ $localeName }}"
                            id="{{ $localeName }}"
                        >
                            <option value="">اختر اللغة</option>
                            @foreach($normalizedLocales as $loc)
                                <option value="{{ $loc['code'] }}" {{ (string) $localeCurrent === (string) $loc['code'] ? 'selected' : '' }}>
                                    {{ $loc['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error($localeErrorName, $errorBag)
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            </div>
            <div class="pt-4">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-tr-remove>
                    {{ $removeLabel }}
                </button>
            </div>
        </div>

        <div class="row g-3 mt-1">
            @foreach($fields as $f)
                @php
                    $type = $f['type'] ?? 'input';
                    $key = $f['key'] ?? null;
                    $label = $f['label'] ?? null;
                    $requiredFirst = (bool) ($f['requiredFirst'] ?? false);
                    $requiredAll = (bool) ($f['required'] ?? false);
                    $rows = $f['rows'] ?? 4;
                    $placeholder = $f['placeholder'] ?? null;

                    $fieldName = $key ? ($namePrefix.'['.$index.']['.$key.']') : null;
                    $errorName = $key ? ($namePrefix.'.'.$index.'.'.$key) : null;
                    $value = $key ? data_get($item, $key) : null;
                    $required = (!$template && ($requiredAll || ((int) $index === 0 && $requiredFirst)));
                @endphp

                @if($key)
                    <div class="col-12">
                        @if($template)
                            <label class="form-label" for="{{ $namePrefix }}[{{ $index }}][{{ $key }}]">{{ $label }}</label>
                            @if($type === 'textarea')
                                <textarea
                                    class="form-control"
                                    name="{{ $namePrefix }}[{{ $index }}][{{ $key }}]"
                                    id="{{ $namePrefix }}[{{ $index }}][{{ $key }}]"
                                    data-tr-required-first="{{ $requiredFirst ? '1' : '' }}"
                                    data-tr-required="{{ $requiredAll ? '1' : '' }}"
                                    rows="{{ $rows }}"
                                    placeholder="{{ $placeholder }}"
                                ></textarea>
                            @else
                                <input
                                    class="form-control"
                                    name="{{ $namePrefix }}[{{ $index }}][{{ $key }}]"
                                    id="{{ $namePrefix }}[{{ $index }}][{{ $key }}]"
                                    data-tr-required-first="{{ $requiredFirst ? '1' : '' }}"
                                    data-tr-required="{{ $requiredAll ? '1' : '' }}"
                                    placeholder="{{ $placeholder }}"
                                >
                            @endif
                            <div class="invalid-feedback d-none" data-tr-invalid-for="{{ $key }}"></div>
                        @else
                            @php
                                $hasError = $errorName ? $errors->has($errorName, $errorBag) : false;
                                $currentValue = $value ?? ($fieldName ? old($fieldName) : null);
                            @endphp
                            @if($label)
                                <label for="{{ $fieldName }}" class="form-label">
                                    {{ $label }}
                                    @if($required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                            @endif
                            @if($type === 'textarea')
                                <textarea
                                    class="form-control{{ $hasError ? ' is-invalid' : '' }}"
                                    name="{{ $fieldName }}"
                                    id="{{ $fieldName }}"
                                    rows="{{ $rows }}"
                                    placeholder="{{ $placeholder }}"
                                    @if($required) required @endif
                                >{{ $currentValue }}</textarea>
                            @else
                                <input
                                    class="form-control{{ $hasError ? ' is-invalid' : '' }}"
                                    name="{{ $fieldName }}"
                                    id="{{ $fieldName }}"
                                    value="{{ $currentValue }}"
                                    placeholder="{{ $placeholder }}"
                                    @if($required) required @endif
                                >
                            @endif
                            @if($errorName)
                                @error($errorName, $errorBag)
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            @endif
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
