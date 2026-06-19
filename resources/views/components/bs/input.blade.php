@props([
    'type' => 'text',
    'name',
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autocomplete' => null,
    'step' => null,
    'class' => '',
    'wrapperClass' => '',
    'helpText' => null,
    'errorBag' => 'default'
])

@php
    $inputId = $name;
    $hasError = $errors->has($name, $errorBag);
    $inputClass = 'form-control ' . $class;
    $inputClass .= $hasError ? ' is-invalid' : '';
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $inputId }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input 
        type="{{ $type }}"
        id="{{ $inputId }}"
        name="{{ $name }}"
        value="{{ $value ?? old($name) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($step) step="{{ $step }}" @endif
        class="{{ $inputClass }}"
        {{ $attributes }}
    >

    @if($helpText)
        <small class="form-text text-muted">{{ $helpText }}</small>
    @endif

    @error($name, $errorBag)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
