@props([
    'paginator',
    'onPageChange' => 'goToPage',
    'onPerPageChange' => 'updatePerPage',
    'perPageOptions' => [10, 25, 50, 100],
])

@php
    $paginator = $paginator ?? (object) [
        'currentPage' => 1,
        'lastPage' => 1,
        'perPage' => 10,
        'total' => 0,
        'from' => 0,
        'to' => 0,
    ];
    
    $showPagination = $paginator->lastPage > 1;
    $showPerPage = $onPerPageChange !== null;
    
    // Calculate window of pages around current page
    $window = 2;
    $windowStart = max($paginator->currentPage - $window, 1);
    $windowEnd = min($paginator->currentPage + $window, $paginator->lastPage);
    
    // Add pages at the beginning if we're near the end
    if ($windowEnd - $windowStart < $window * 2) {
        $windowStart = max($windowEnd - $window * 2, 1);
    }
    
    // Add pages at the end if we're near the beginning
    if ($windowEnd - $windowStart < $window * 2) {
        $windowEnd = min($windowStart + $window * 2, $paginator->lastPage);
    }
    
    $pages = range($windowStart, $windowEnd);
@endphp

@if($showPagination || $showPerPage)
    <div class="flex flex-col sm:flex-row items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
        @if($showPerPage)
            <div class="flex-1 flex justify-between sm:hidden">
                <button 
                    @click="{{ $onPageChange }}($event, {{ max(1, $paginator->currentPage - 1) }})"
                    :disabled="{{ $paginator->currentPage }} <= 1"
                    :class="{'opacity-50 cursor-not-allowed': {{ $paginator->currentPage }} <= 1}"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                    Previous
                </button>
                <button
                    @click="{{ $onPageChange }}($event, {{ min($paginator->lastPage, $paginator->currentPage + 1) }})"
                    :disabled="{{ $paginator->currentPage }} >= {{ $paginator->lastPage }}"
                    :class="{'opacity-50 cursor-not-allowed': {{ $paginator->currentPage }} >= {{ $paginator->lastPage }}}"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                    Next
                </button>
            </div>
            
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $paginator->from ?? 0 }}</span>
                        to
                        <span class="font-medium">{{ $paginator->to ?? 0 }}</span>
                        of
                        <span class="font-medium">{{ $paginator->total ?? 0 }}</span>
                        results
                    </p>
                </div>
                
                @if($showPagination)
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            {{-- Previous Page Link --}}
                            <button
                                @click="{{ $onPageChange }}($event, {{ max(1, $paginator->currentPage - 1) }})"
                                :disabled="{{ $paginator->currentPage }} <= 1"
                                :class="{
                                    'opacity-50 cursor-not-allowed': {{ $paginator->currentPage }} <= 1,
                                    'hover:bg-gray-50': {{ $paginator->currentPage }} > 1
                                }"
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500"
                            >
                                <span class="sr-only">Previous</span>
                                <i class="ri-arrow-left-s-line h-5 w-5"></i>
                            </button>
                            
                            {{-- Page Numbers --}}
                            @if($windowStart > 1)
                                <button
                                    @click="{{ $onPageChange }}($event, 1)"
                                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                >
                                    1
                                </button>
                                @if($windowStart > 2)
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                        ...
                                    </span>
                                @endif
                            @endif
                            
                            @foreach($pages as $page)
                                <button
                                    @click="{{ $onPageChange }}($event, {{ $page }})"
                                    :class="{
                                        'z-10 bg-primary-50 border-primary-500 text-primary-600': {{ $page }} === {{ $paginator->currentPage }},
                                        'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': {{ $page }} !== {{ $paginator->currentPage }}
                                    }"
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                >
                                    {{ $page }}
                                </button>
                            @endforeach
                            
                            @if($windowEnd < $paginator->lastPage)
                                @if($windowEnd < $paginator->lastPage - 1)
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                        ...
                                    </span>
                                @endif
                                <button
                                    @click="{{ $onPageChange }}($event, {{ $paginator->lastPage }})"
                                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                >
                                    {{ $paginator->lastPage }}
                                </button>
                            @endif
                            
                            {{-- Next Page Link --}}
                            <button
                                @click="{{ $onPageChange }}($event, {{ min($paginator->lastPage, $paginator->currentPage + 1) }})"
                                :disabled="{{ $paginator->currentPage }} >= {{ $paginator->lastPage }}"
                                :class="{
                                    'opacity-50 cursor-not-allowed': {{ $paginator->currentPage }} >= {{ $paginator->lastPage }},
                                    'hover:bg-gray-50': {{ $paginator->currentPage }} < {{ $paginator->lastPage }}
                                }"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500"
                            >
                                <span class="sr-only">Next</span>
                                <i class="ri-arrow-right-s-line h-5 w-5"></i>
                            </button>
                        </nav>
                    </div>
                @endif
            </div>
        @endif
        
        @if($showPerPage)
            <div class="mt-2 sm:mt-0 sm:ml-6">
                <div class="flex items-center">
                    <label for="perPage" class="mr-2 text-sm text-gray-700">Per page:</label>
                    <select
                        id="perPage"
                        @change="{{ $onPerPageChange }}($event)"
                        class="block w-20 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                    >
                        @foreach($perPageOptions as $option)
                            <option value="{{ $option }}" {{ $paginator->perPage == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
    </div>
@endif
