@props([
    'headers' => [],
    'noBorder' => false,
    'hover' => true,
    'compact' => false,
    'responsive' => true,
])

@php
    $tableClasses = [
        'min-w-full',
        'divide-y divide-gray-200',
        $noBorder ? '' : 'border border-gray-200',
        $compact ? 'text-sm' : 'text-base',
    ];
    
    $theadClasses = [
        'bg-gray-50',
    ];
    
    $thClasses = [
        'px-4 py-3',
        'text-left text-xs font-medium text-gray-500 uppercase tracking-wider',
        'whitespace-nowrap',
    ];
    
    $tdClasses = [
        'px-4 py-3',
        'whitespace-nowrap',
        $compact ? 'py-2' : 'py-4',
    ];
    
    $trClasses = [
        $hover ? 'hover:bg-gray-50' : '',
        'transition-colors duration-150',
    ];
    
    $tableClass = implode(' ', array_filter($tableClasses));
    $theadClass = implode(' ', array_filter($theadClasses));
    $thClass = implode(' ', array_filter($thClasses));
    $tdClass = implode(' ', array_filter($tdClasses));
    $trClass = implode(' ', array_filter($trClasses));
@endphp

@if($responsive)
    <div class="overflow-x-auto">
        <div class="align-middle inline-block min-w-full">
@endif

            <table {{ $attributes->merge(['class' => $tableClass]) }}>
                @if(count($headers) > 0)
                    <thead class="{{ $theadClass }}">
                        <tr>
                            @foreach($headers as $header)
                                <th scope="col" class="{{ $thClass }} {{ $header['class'] ?? '' }}">
                                    {{ $header['label'] ?? $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                @endif
                
                <tbody class="bg-white divide-y divide-gray-200">
                    {{ $slot }}
                </tbody>
                
                @if(isset($foot))
                    <tfoot class="bg-gray-50">
                        {{ $foot }}
                    </tfoot>
                @endif
            </table>

@if($responsive)
        </div>
    </div>
@endif
