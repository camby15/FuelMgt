@props([
    'type' => 'info',
    'title' => null,
    'message' => null,
    'dismissible' => false,
    'show' => true,
    'timeout' => null,
])

@php
    $types = [
        'info' => [
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-700',
            'border' => 'border-blue-200',
            'icon' => 'ri-information-line',
            'title' => 'Information',
        ],
        'success' => [
            'bg' => 'bg-green-50',
            'text' => 'text-green-700',
            'border' => 'border-green-200',
            'icon' => 'ri-checkbox-circle-line',
            'title' => 'Success',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'text' => 'text-yellow-700',
            'border' => 'border-yellow-200',
            'icon' => 'ri-alert-line',
            'title' => 'Warning',
        ],
        'danger' => [
            'bg' => 'bg-red-50',
            'text' => 'text-red-700',
            'border' => 'border-red-200',
            'icon' => 'ri-close-circle-line',
            'title' => 'Error',
        ],
    ];
    
    $config = $types[$type] ?? $types['info'];
    $title = $title ?? $config['title'];
    
    $classes = [
        'rounded-md p-4 mb-4',
        $config['bg'],
        $config['text'],
        'border',
        $config['border'],
    ];
    
    if ($dismissible) {
        $classes[] = 'relative pr-10';
    }
    
    $class = implode(' ', $classes);
    
    // Auto-dismiss after timeout
    if ($timeout && $show) {
        $dismissScript = "setTimeout(() => { document.getElementById('alert-'.$attributes->get('id', '')).remove(); }, {$timeout});";
    }
@endphp

@if($show)
    <div 
        {{ $attributes->merge(['class' => $class]) }}
        role="alert"
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        @if($dismissible)
            x-init="setTimeout(() => { show = false }, {{ $timeout ?? 5000 }})"
        @endif
    >
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="{{ $config['icon'] }} h-5 w-5" aria-hidden="true"></i>
            </div>
            <div class="ml-3 flex-1">
                @if($title)
                    <h3 class="text-sm font-medium">
                        {{ $title }}
                    </h3>
                @endif
                <div class="mt-1 text-sm">
                    {{ $message ?? $slot }}
                </div>
            </div>
            @if($dismissible)
                <div class="ml-4">
                    <button 
                        type="button" 
                        @click="show = false"
                        class="inline-flex rounded-md {{ $config['bg'] }} {{ $config['text'] }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ explode('-', $config['bg'])[1] }}-50 focus:ring-{{ explode('-', $config['border'])[1] }}-500"
                    >
                        <span class="sr-only">Dismiss</span>
                        <i class="ri-close-line h-5 w-5" aria-hidden="true"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>
    
    @if(isset($dismissScript))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                {!! $dismissScript !!}
            });
        </script>
    @endif
@endif
