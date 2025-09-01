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
    $textareaClass = 'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-vertical ' . $class;
    $textareaClass .= $hasError ? ' border-red-500' : ' border-gray-300';
    $textareaClass .= $disabled ? ' bg-gray-100 cursor-not-allowed' : '';
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $textareaId }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
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
        <small class="text-gray-500 text-xs mt-1 block">{{ $helpText }}</small>
    @endif

    @error($name, $errorBag)
        <div class="text-red-500 text-sm mt-1">
            <i class="fas fa-exclamation-circle text-red-400 mr-1"></i>
            {{ $message }}
        </div>
    @enderror
</div>
