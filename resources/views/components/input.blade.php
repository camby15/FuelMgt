@props([
    'id' => null,
    'name' => null,
    'type' => 'text',
    'label' => null,
    'value' => '',
    'placeholder' => null,
    'required' => false,
    'error' => null,
    'help' => null,
    'wrapperClass' => '',
    'inputClass' => '',
    'labelClass' => '',
    'errorClass' => '',
    'helpClass' => '',
    'leftIcon' => null,
    'rightIcon' => null,
])

@php
    $id = $id ?? $name;
    $name = $name ?? $id;
    $error = $errors->first($name) ?? $error;
    $inputClass = 'block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm ' . $inputClass;
    $inputClass .= $error ? ' border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:outline-none focus:ring-red-500' : ' border-gray-300';
    $labelClass = 'block text-sm font-medium text-gray-700 ' . $labelClass;
    $errorClass = 'mt-1 text-sm text-red-600 ' . $errorClass;
    $helpClass = 'mt-1 text-sm text-gray-500 ' . $helpClass;
    $wrapperClass = 'space-y-1 ' . $wrapperClass;
    $hasLeftAddon = isset($leftAddon);
    $hasRightAddon = isset($rightAddon);
    $hasLeftIcon = isset($leftIcon);
    $hasRightIcon = isset($rightIcon);

    if ($hasLeftAddon || $hasLeftIcon) {
        $inputClass .= ' rounded-l-none';
    }
    if ($hasRightAddon || $hasRightIcon) {
        $inputClass .= ' rounded-r-none';
    }
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

    <div class="mt-1 flex rounded-md shadow-sm">
        @if($hasLeftAddon)
            <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">
                {{ $leftAddon }}
            </span>
        @elseif($hasLeftIcon)
            <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500">
                <i class="{{ $leftIcon }} h-4 w-4"></i>
            </span>
        @endif

        <input
            {{ $attributes->merge([
                'type' => $type,
                'id' => $id,
                'name' => $name,
                'value' => old($name, $value),
                'required' => $required,
                'placeholder' => $placeholder,
                'class' => $inputClass,
            ]) }}
        >

        @if($hasRightAddon)
            <span class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">
                {{ $rightAddon }}
            </span>
        @elseif($hasRightIcon)
            <span class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500">
                <i class="{{ $rightIcon }} h-4 w-4"></i>
            </span>
        @endif
    </div>

    @if($error)
        <p class="{{ $errorClass }}">{{ $error }}</p>
    @endif

    @if($help)
        <p class="{{ $helpClass }}">{{ $help }}</p>
    @endif
</div>
