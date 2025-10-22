@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'fullWidth' => false,
    'as' => 'button',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-md font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200';
    
    // Variants
    $variantClasses = [
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500',
        'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-primary-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-400',
        'info' => 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-400',
        'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-primary-500',
        'link' => 'text-primary-600 hover:text-primary-800 focus:ring-primary-500',
    ][$variant] ?? $variantClasses['primary'];
    
    // Sizes
    $sizeClasses = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base',
        'xl' => 'px-6 py-3 text-base',
    ][$size] ?? $sizeClasses['md'];
    
    // Full width
    $fullWidthClass = $fullWidth ? 'w-full' : '';
    
    // Icon spacing
    $iconSizeClasses = [
        'xs' => 'h-3 w-3',
        'sm' => 'h-3.5 w-3.5',
        'md' => 'h-4 w-4',
        'lg' => 'h-5 w-5',
        'xl' => 'h-5 w-5',
    ][$size] ?? $iconSizeClasses['md'];
    
    $iconClasses = "$iconSizeClasses " . ($iconPosition === 'left' ? 'mr-2' : 'ml-2');
    $iconHtml = $icon ? '<i class="' . $icon . ' ' . $iconClasses . '"></i>' : '';
    
    $classes = "$baseClasses $variantClasses $sizeClasses $fullWidthClass";
    
    // Set the tag to use
    $tag = $href ? 'a' : $as;
    
    // Set default attributes
    $attributes = $attributes->merge([
        'class' => $classes,
        'type' => $tag === 'button' ? $type : null,
        'href' => $href,
    ]);
@endphp

<{{ $tag }} {{ $attributes }}>
    @if($icon && $iconPosition === 'left')
        {!! $iconHtml !!}
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        {!! $iconHtml !!}
    @endif
</{{ $tag }}>
