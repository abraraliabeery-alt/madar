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
    'class' => '',
    'wrapperClass' => '',
    'helpText' => null,
    'errorBag' => 'default'
])

@php
    $inputId = $name;
    $hasError = $errors->has($name, $errorBag);
    $inputClass = 'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors ' . $class;
    $inputClass .= $hasError ? ' border-red-500' : ' border-gray-300';
    $inputClass .= $disabled ? ' bg-gray-100 cursor-not-allowed' : '';
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
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
        class="{{ $inputClass }}"
        {{ $attributes }}
    >

    @if($helpText)
        <small class="text-gray-500 text-xs mt-1 block">{{ $helpText }}</small>
    @endif

    @error($name, $errorBag)
        <div class="text-red-500 text-sm mt-1">
            <i class="fas fa-exclamation-circle text-red-400 mr-1"></i>
            {{ $message }}
        </div>
    @enderror
</div>
