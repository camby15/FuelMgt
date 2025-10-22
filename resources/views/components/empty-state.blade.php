@props([
    'icon' => 'ri-information-line',
    'title' => 'No items found',
    'description' => 'Get started by creating a new item.',
    'buttonText' => null,
    'buttonIcon' => 'ri-add-line',
    'buttonAction' => null,
    'buttonVariant' => 'primary',
    'buttonSize' => 'md',
])

<div class="text-center py-12">
    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 text-gray-400">
        <i class="{{ $icon }} h-6 w-6"></i>
    </div>
    <h3 class="mt-2 text-lg font-medium text-gray-900">{{ $title }}</h3>
    <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    
    @if($buttonText && $buttonAction)
        <div class="mt-6">
            <x-button 
                :variant="$buttonVariant" 
                :size="$buttonSize"
                :icon="$buttonIcon"
                @click="{{ $buttonAction }}"
            >
                {{ $buttonText }}
            </x-button>
        </div>
    @elseif($slot->isNotEmpty())
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</div>
