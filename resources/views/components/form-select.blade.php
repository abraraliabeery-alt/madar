@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'class' => '',
    'wrapperClass' => '',
    'helpText' => null,
    'errorBag' => 'default'
])

@php
    $selectId = $name;
    $hasError = $errors->has($name, $errorBag);
    $selectClass = 'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors ' . $class;
    $selectClass .= $hasError ? ' border-red-500' : ' border-gray-300';
    $selectClass .= $disabled ? ' bg-gray-100 cursor-not-allowed' : '';
    $currentValue = $selected ?? old($name);
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $selectId }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select 
        id="{{ $selectId }}"
        name="{{ $name }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="{{ $selectClass }}"
        {{ $attributes }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ $currentValue == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

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
