<div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
    {{-- Showing Data --}}
    <div>
        Showing <strong>{{ $paginator->firstItem() }}</strong>
        to <strong>{{ $paginator->lastItem() }}</strong>
        of <strong>{{ $paginator->total() }}</strong> entries
    </div>
    <nav>
        <ul class="pagination mb-0">
            {{-- First Page --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->appends(request()->except('page'))->url(1) }}">
                    &laquo;
                </a>
            </li>

            {{-- Dynamic Page Numbers --}}
            @php
                $start = max($paginator->currentPage() - 2, 1);
                $end = min($paginator->currentPage() + 2, $paginator->lastPage());
            @endphp

            {{-- First + dots --}}
            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            {{-- Page range --}}
            @for ($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $i == $paginator->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Last + dots --}}
            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
            @endif

            {{-- Last Page --}}
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">
                    &raquo;
                </a>
            </li>
        </ul>
    </nav>
</div>
