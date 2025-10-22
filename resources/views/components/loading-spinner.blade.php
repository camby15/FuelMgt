@props([
    'size' => 'md',
    'color' => 'primary',
    'fullScreen' => false,
    'message' => null,
])

@php
    $sizes = [
        'xs' => 'h-4 w-4 border-2',
        'sm' => 'h-6 w-6 border-2',
        'md' => 'h-8 w-8 border-2',
        'lg' => 'h-10 w-10 border-2',
        'xl' => 'h-12 w-12 border-2',
    ];
    
    $colors = [
        'primary' => 'border-t-primary-500 border-r-primary-500 border-b-transparent border-l-transparent',
        'white' => 'border-t-white border-r-white border-b-transparent border-l-transparent',
        'gray' => 'border-t-gray-400 border-r-gray-400 border-b-transparent border-l-transparent',
        'red' => 'border-t-red-500 border-r-red-500 border-b-transparent border-l-transparent',
        'green' => 'border-t-green-500 border-r-green-500 border-b-transparent border-l-transparent',
        'blue' => 'border-t-blue-500 border-r-blue-500 border-b-transparent border-l-transparent',
        'yellow' => 'border-t-yellow-500 border-r-yellow-500 border-b-transparent border-l-transparent',
        'indigo' => 'border-t-indigo-500 border-r-indigo-500 border-b-transparent border-l-transparent',
        'purple' => 'border-t-purple-500 border-r-purple-500 border-b-transparent border-l-transparent',
        'pink' => 'border-t-pink-500 border-r-pink-500 border-b-transparent border-l-transparent',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $colorClass = $colors[$color] ?? $colors['primary'];
    $textSizes = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
    ];
    $textSizeClass = $textSizes[$size] ?? $textSizes['md'];
    $gapClass = ' ' . ($size === 'xs' ? 'gap-1' : ($size === 'sm' ? 'gap-1.5' : 'gap-2'));
    
    $spinnerClass = "animate-spin rounded-full border-solid {$sizeClass} {$colorClass}";
    $containerClass = 'flex items-center justify-center' . ($fullScreen ? ' h-screen w-screen fixed inset-0 bg-white bg-opacity-75 z-50' : '');
    $contentClass = 'flex flex-col items-center' . ($fullScreen ? ' space-y-4' : ' space-y-2');
    $messageClass = 'text-gray-600 ' . $textSizeClass;
@endphp

<div class="{{ $containerClass }}">
    <div class="{{ $contentClass }}">
        <div class="{{ $spinnerClass }}" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        @if($message)
            <p class="{{ $messageClass }}">{{ $message }}</p>
        @endif
    </div>
</div>
