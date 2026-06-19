@props([
    'name',
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'rows' => 4,
    'class' => '',
    'wrapperClass' => '',
    'helpText' => null,
    'errorBag' => 'default'
])

@php
    $textareaId = $name;
    $hasError = $errors->has($name, $errorBag);
    $textareaClass = 'form-control ' . $class;
    $textareaClass .= $hasError ? ' is-invalid' : '';
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $textareaId }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <textarea 
        id="{{ $textareaId }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        class="{{ $textareaClass }}"
        {{ $attributes }}
    >{{ $value ?? old($name) }}</textarea>

    @if($helpText)
        <small class="form-text text-muted">{{ $helpText }}</small>
    @endif

    @error($name, $errorBag)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
