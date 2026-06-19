@props([
    'name',
    'label' => null,
    'options' => [],
    'optionValue' => 'id',
    'optionLabel' => 'name',
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
    $selectClass = 'form-select ' . $class;
    $selectClass .= $hasError ? ' is-invalid' : '';
    $currentValue = $selected ?? old($name);
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $selectId }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
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
        
        @foreach($options as $option)
            @php
                if (is_array($option)) {
                    $value = $option[$optionValue] ?? null;
                    $label = $option[$optionLabel] ?? null;
                } elseif (is_object($option)) {
                    $value = data_get($option, $optionValue);
                    $label = data_get($option, $optionLabel);
                } else {
                    $value = $option;
                    $label = $option;
                }
            @endphp
            <option value="{{ $value }}" {{ $currentValue == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @if($helpText)
        <small class="form-text text-muted">{{ $helpText }}</small>
    @endif

    @error($name, $errorBag)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
