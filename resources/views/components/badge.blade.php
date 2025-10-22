@props([
    'variant' => 'gray',
    'size' => 'md',
    'pill' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'dismissible' => false,
    'onDismiss' => null,
])

@php
    // Variant styles
    $variants = [
        'gray' => 'bg-gray-100 text-gray-800',
        'red' => 'bg-red-100 text-red-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'green' => 'bg-green-100 text-green-800',
        'blue' => 'bg-blue-100 text-blue-800',
        'indigo' => 'bg-indigo-100 text-indigo-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'pink' => 'bg-pink-100 text-pink-800',
    ];
    
    // Size styles
    $sizes = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2 py-0.5 text-sm',
        'md' => 'px-2.5 py-0.5 text-sm',
        'lg' => 'px-3 py-0.5 text-base',
    ];
    
    // Icon sizes
    $iconSizes = [
        'xs' => 'h-3 w-3',
        'sm' => 'h-3.5 w-3.5',
        'md' => 'h-4 w-4',
        'lg' => 'h-5 w-5',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['gray'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $iconSizeClass = $iconSizes[$size] ?? $iconSizes['md'];
    $pillClass = $pill ? 'rounded-full' : 'rounded-md';
    
    $baseClasses = 'inline-flex items-center font-medium';
    $iconClasses = $iconSizeClass . ' ' . ($iconPosition === 'left' ? 'mr-1.5' : 'ml-1.5');
    $dismissButtonClass = 'flex-shrink-0 ml-1.5 inline-flex items-center justify-center text-current focus:outline-none';
    
    $classes = $baseClasses . ' ' . $variantClass . ' ' . $sizeClass . ' ' . $pillClass;
    
    $iconHtml = $icon ? '<i class="' . $icon . ' ' . $iconClasses . '"></i>' : '';
    $dismissButtonHtml = $dismissible ? 
        '<button type="button" class="' . $dismissButtonClass . '" ' . 
        ($onDismiss ? 'x-on:click="' . $onDismiss . '"' : '') . '>' .
        '<span class="sr-only">Remove badge</span>' .
        '<i class="ri-close-line ' . $iconSizeClass . '"></i>' .
        '</button>' : '';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon && $iconPosition === 'left')
        {!! $iconHtml !!}
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        {!! $iconHtml !!}
    @endif
    
    @if($dismissible)
        {!! $dismissButtonHtml !!}
    @endif
</span>
