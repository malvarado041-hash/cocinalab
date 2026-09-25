@if($paginator->hasPages())
<nav aria-label="{{ $label ?? 'Pagination' }}" {{ $attributes }}>
    <ul class="flex items-center space-x-1">
        {{-- Previous --}}
        @if($paginator->onFirstPage())
            <li>
                <span class="px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 rounded-lg cursor-not-allowed" aria-disabled="true">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors"
                   rel="prev"
                   {{ $attributes->whereStartsWith('wire:') }}>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </li>
        @endif

        {{-- Pages --}}
        @foreach($elements ?? $paginator->getUrlRange(1, $paginator->lastPage()) as $element)
            @if(is_array($element))
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())
                        <li>
                            <span class="px-4 py-2 text-sm font-semibold text-white bg-orange-600 border border-orange-600 rounded-lg" aria-current="page">{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @else
                <li>
                    <span class="px-4 py-2 text-sm text-gray-400">{{ $element }}</span>
                </li>
            @endif
        @endforeach

        {{-- Next --}}
        @if($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors"
                   rel="next"
                   {{ $attributes->whereStartsWith('wire:') }}>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </li>
        @else
            <li>
                <span class="px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 rounded-lg cursor-not-allowed" aria-disabled="true">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </span>
            </li>
        @endif
    </ul>

    {{-- Results info --}}
    @if($showInfo ?? true)
        <div class="mt-4 text-sm text-gray-600">
            {{ $paginator->count() }} de {{ $paginator->total() }} resultados
        </div>
    @endif
</nav>
@endif