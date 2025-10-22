@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option',
    'required' => false,
    'error' => null,
    'help' => null,
    'wrapperClass' => '',
    'selectClass' => '',
    'labelClass' => '',
    'errorClass' => '',
    'helpClass' => '',
])

@php
    $id = $id ?? $name;
    $name = $name ?? $id;
    $error = $errors->first($name) ?? $error;
    $selectClass = 'block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm ' . $selectClass;
    $selectClass .= $error ? ' border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : ' border-gray-300';
    $labelClass = 'block text-sm font-medium text-gray-700 ' . $labelClass;
    $errorClass = 'mt-1 text-sm text-red-600 ' . $errorClass;
    $helpClass = 'mt-1 text-sm text-gray-500 ' . $helpClass;
    $wrapperClass = 'space-y-1 ' . $wrapperClass;
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $id }}" class="{{ $labelClass }}">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select
            {{ $attributes->merge([
                'id' => $id,
                'name' => $name,
                'class' => $selectClass,
                'required' => $required,
            ]) }}
        >
            @if($placeholder)
                <option value="" disabled {{ old($name, $selected) === null ? 'selected' : '' }}>
                    {{ $placeholder }}
                </option>
            @endif

            @foreach($options as $value => $optionLabel)
                <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <i class="ri-arrow-down-s-line h-4 w-4"></i>
        </div>
    </div>

    @if($error)
        <p class="{{ $errorClass }}">{{ $error }}</p>
    @endif

    @if($help)
        <p class="{{ $helpClass }}">{{ $help }}</p>
    @endif
</div>
