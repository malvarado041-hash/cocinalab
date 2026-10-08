@if ($paginator->hasPages())
    <nav class="flex items-center gap-2" role="navigation" aria-label="Paginación">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-3 py-2 text-sm text-gray-300 bg-white border border-gray-200 rounded-lg cursor-not-allowed" aria-disabled="true">
                <i class="fas fa-arrow-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors" rel="prev" aria-label="Página anterior">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endif

        <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg">
            {{ $paginator->currentPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors" rel="next" aria-label="Página siguiente">
                <i class="fas fa-arrow-right"></i>
            </a>
        @else
            <span class="inline-flex items-center px-3 py-2 text-sm text-gray-300 bg-white border border-gray-200 rounded-lg cursor-not-allowed" aria-disabled="true">
                <i class="fas fa-arrow-right"></i>
            </span>
        @endif
    </nav>
@endif
