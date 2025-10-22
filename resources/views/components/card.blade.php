@props([
    'title' => null,
    'header' => null,
    'footer' => null,
    'noPadding' => false,
    'noShadow' => false,
    'noBorder' => false,
    'noRounded' => false,
    'variant' => 'default',
])

@php
    $cardClasses = [
        'bg-white',
        'overflow-hidden',
        $noShadow ? '' : 'shadow',
        $noBorder ? '' : 'border border-gray-200',
        $noRounded ? '' : 'rounded-lg',
    ];
    
    $headerClasses = [
        'px-4 py-5 sm:px-6',
        'border-b border-gray-200',
    ];
    
    $bodyClasses = [
        $noPadding ? 'p-0' : 'p-6',
    ];
    
    $footerClasses = [
        'px-4 py-4 sm:px-6',
        'bg-gray-50',
        'border-t border-gray-200',
    ];
    
    // Variants
    if ($variant === 'primary') {
        $cardClasses[] = 'border-primary-200';
        $headerClasses[] = 'bg-primary-50 border-primary-200';
    } elseif ($variant === 'success') {
        $cardClasses[] = 'border-green-200';
        $headerClasses[] = 'bg-green-50 border-green-200';
    } elseif ($variant === 'danger') {
        $cardClasses[] = 'border-red-200';
        $headerClasses[] = 'bg-red-50 border-red-200';
    } elseif ($variant === 'warning') {
        $cardClasses[] = 'border-yellow-200';
        $headerClasses[] = 'bg-yellow-50 border-yellow-200';
    } elseif ($variant === 'info') {
        $cardClasses[] = 'border-blue-200';
        $headerClasses[] = 'bg-blue-50 border-blue-200';
    }
    
    $cardClass = implode(' ', array_filter($cardClasses));
    $headerClass = implode(' ', array_filter($headerClasses));
    $bodyClass = implode(' ', array_filter($bodyClasses));
    $footerClass = implode(' ', array_filter($footerClasses));
@endphp

<div {{ $attributes->merge(['class' => $cardClass]) }}>
    @if($title || $header)
        <div class="{{ $headerClass }}">
            @if($title && !$header)
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $title }}</h3>
            @else
                {{ $header }}
            @endif
        </div>
    @endif
    
    <div class="{{ $bodyClass }}">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="{{ $footerClass }}">
            {{ $footer }}
        </div>
    @endif
</div>
