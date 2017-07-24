@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous ON First Page --}}
        <a class="page-link fa fa-step-backward" href="{{ $paginator->url(1) }}"></a>    
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-link disabled fa fa-angle-left"></span>
        @else
            <a class="page-link fa fa-angle-left" href="{{ $paginator->previousPageUrl() }}" rel="prev"></a>
        @endif
        <span class="page-link active">{{ $paginator->currentPage()}} of {{ $paginator->lastPage()}}</span>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="page-link fa fa-angle-right" href="{{ $paginator->nextPageUrl() }}" rel="next"></a>
        @else
            <span class="page-link disabled fa fa-angle-right"></span>
        @endif

        {{-- Previous ON Last Page --}}
        <a class="page-link fa fa-step-forward" href="{{ $paginator->url($paginator->lastPage()) }}"></a>
    </ul>
@endif
