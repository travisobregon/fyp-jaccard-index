@if ($paginator->hasPages())
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <a class="ui left labeled disabled icon button">
            <i class="left chevron icon"></i>
            @lang('pagination.previous')
        </a>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="ui left labeled icon button" rel="prev">
            <i class="left chevron icon"></i>
            @lang('pagination.previous')
        </a>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="ui right labeled icon button" rel="next">
            <i class="right chevron icon"></i>
            @lang('pagination.next')
        </a>
    @else
        <a class="ui right labeled disabled icon button">
            <i class="right chevron icon"></i>
            @lang('pagination.next')
        </a>
    @endif
@endif
