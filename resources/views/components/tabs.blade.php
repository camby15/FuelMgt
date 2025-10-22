@props([
    'tabs' => [],
    'activeTab' => null,
    'variant' => 'default', // 'default', 'pills', 'underline'
    'size' => 'md', // 'sm', 'md', 'lg'
    'fullWidth' => false,
    'onTabChange' => null,
    'mobileMenuLabel' => 'Select a tab',
])

@php
    // Default to first tab if none is active
    if (!$activeTab && count($tabs) > 0) {
        $activeTab = $tabs[0]['id'] ?? null;
    }
    
    // Variant classes
    $variantClasses = [
        'default' => [
            'container' => 'border-b border-gray-200',
            'tab' => [
                'base' => 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                'inactive' => 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'active' => 'border-primary-500 text-primary-600',
            ],
        ],
        'pills' => [
            'container' => 'flex space-x-4',
            'tab' => [
                'base' => 'px-3 py-2 rounded-md text-sm font-medium',
                'inactive' => 'text-gray-500 hover:bg-gray-100',
                'active' => 'bg-primary-100 text-primary-700',
            ],
        ],
        'underline' => [
            'container' => 'border-b border-gray-200',
            'tab' => [
                'base' => 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                'inactive' => 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'active' => 'border-primary-500 text-primary-600',
            ],
        ],
    ];
    
    // Size classes
    $sizeClasses = [
        'sm' => 'text-xs px-2 py-1',
        'md' => 'text-sm px-3 py-2',
        'lg' => 'text-base px-4 py-3',
    ];
    
    $variantConfig = $variantClasses[$variant] ?? $variantClasses['default'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $containerClass = $variantConfig['container'] . ' ' . ($fullWidth ? 'flex' : 'inline-flex');
    $tabBaseClass = $variantConfig['tab']['base'] . ' ' . $sizeClass;
    
    // Generate tab items with their respective classes
    $tabItems = [];
    foreach ($tabs as $tab) {
        $isActive = $tab['id'] === $activeTab;
        $tabClass = $tabBaseClass . ' ' . ($isActive ? $variantConfig['tab']['active'] : $variantConfig['tab']['inactive']);
        $tabItems[] = [
            'id' => $tab['id'],
            'label' => $tab['label'],
            'icon' => $tab['icon'] ?? null,
            'badge' => $tab['badge'] ?? null,
            'class' => $tabClass,
            'isActive' => $isActive,
        ];
    }
@endphp

<div x-data="{ activeTab: '{{ $activeTab }}' }" class="w-full">
    <!-- Mobile menu (show as dropdown on small screens) -->
    <div class="sm:hidden mb-4">
        <label for="tabs" class="sr-only">{{ $mobileMenuLabel }}</label>
        <select 
            id="tabs" 
            class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm"
            x-model="activeTab"
            @change="{{ $onTabChange ?? '' }}"
        >
            @foreach($tabs as $tab)
                <option value="{{ $tab['id'] }}">{{ $tab['label'] }}</option>
            @endforeach
        </select>
    </div>
    
    <!-- Desktop menu (show as tabs on larger screens) -->
    <div class="hidden sm:block">
        <div class="{{ $containerClass }}" role="tablist">
            @foreach($tabItems as $tab)
                <button
                    type="button"
                    role="tab"
                    aria-selected="{{ $tab['isActive'] ? 'true' : 'false' }}"
                    @click="activeTab = '{{ $tab['id'] }}'; {{ $onTabChange ?? '' }}"
                    class="{{ $tab['class'] }} flex items-center"
                    :class="{ '{{ $variantConfig['tab']['active'] }}': activeTab === '{{ $tab['id'] }}' }"
                >
                    @if($tab['icon'])
                        <i class="{{ $tab['icon'] }} mr-2"></i>
                    @endif
                    {{ $tab['label'] }}
                    @if(isset($tab['badge']))
                        <span class="ml-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $tab['isActive'] ? 'bg-primary-100 text-primary-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $tab['badge'] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
    
    <!-- Tab panels -->
    <div class="mt-6">
        @foreach($tabs as $tab)
            <div 
                x-show="activeTab === '{{ $tab['id'] }}'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                role="tabpanel"
                tabindex="0"
            >
                {{ ${'tab' . ucfirst($tab['id'])} ?? $slot }}
            </div>
        @endforeach
    </div>
</div>
