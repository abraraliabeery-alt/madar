@props([
    'name',
    'label' => null,
    'accept' => null,
    'multiple' => false,
    'required' => false,
    'disabled' => false,
    'class' => '',
    'wrapperClass' => '',
    'helpText' => null,
    'errorBag' => 'default'
])

@php
    $fileId = $name;
    $hasError = $errors->has($name, $errorBag);
    $fileClass = 'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors ' . $class;
    $fileClass .= $hasError ? ' border-red-500' : ' border-gray-300';
    $fileClass .= $disabled ? ' bg-gray-100 cursor-not-allowed' : '';
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $fileId }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input 
        type="file"
        id="{{ $fileId }}"
        name="{{ $name }}"
        @if($accept) accept="{{ $accept }}" @endif
        @if($multiple) multiple @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="{{ $fileClass }}"
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
