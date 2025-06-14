@if ($paginator->hasPages())
    <div class="flex justify-between border-collapse font-semibold text-sm text-slate-700" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-2 py-1 border border-slate-200 rounded text-slate-500" aria-hidden="true">Prev</span>
        @else
            <a class="px-2 py-1 border border-slate-200 rounded" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Prev</a>
        @endif

        <ul class="list-reset inline-flex -mx-1">
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="mx-1 px-2 py-1 border border-slate-200 rounded disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="mx-1 px-2 py-1 border-blue-600 rounded text-blue-600" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="mx-1 px-2 py-1 border border-slate-200 rounded"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="px-2 py-1 border rounded" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
        @else
            <span class="px-2 py-1 border rounded text-grey" aria-hidden="true">Next</span>
        @endif
    </div>
@endif
