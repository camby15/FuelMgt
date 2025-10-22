@props([
    'tag' => 'button',
    'href' => null,
    'icon' => null,
    'iconClass' => '',
    'variant' => 'default',
    'danger' => false,
    'disabled' => false,
])

@php
    $tag = $href ? 'a' : $tag;
    
    $baseClasses = 'group flex items-center w-full px-4 py-2 text-sm';
    
    // Variant styles
    $variantClasses = [
        'default' => [
            'enabled' => 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
            'disabled' => 'text-gray-400 cursor-not-allowed',
        ],
        'danger' => [
            'enabled' => 'text-red-600 hover:bg-red-50',
            'disabled' => 'text-red-300 cursor-not-allowed',
        ],
    ][$danger ? 'danger' : $variant] ?? $variantClasses['default'];
    
    $state = $disabled ? 'disabled' : 'enabled';
    $classes = $baseClasses . ' ' . $variantClasses[$state];
    
    $iconClass = 'mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 ' . $iconClass;
    
    $attributes = $attributes->merge([
        'class' => $classes,
        'disabled' => $disabled ? 'disabled' : null,
        'href' => $href,
    ]);
    
    if ($tag === 'button' && !$href) {
        $attributes = $attributes->merge(['type' => 'button']);
    }
@endphp

<{{ $tag }} {{ $attributes }}>
    @if($icon)
        <i class="{{ $icon }} {{ $iconClass }}" aria-hidden="true"></i>
    @endif
    {{ $slot }}
</{{ $tag }}>
