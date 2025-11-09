{{-- Pagination --}}
<div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
    {{-- Showing Data --}}
    <div>
        Showing <strong>{{ $users->firstItem() }}</strong>
        to <strong>{{ $users->lastItem() }}</strong>
        of <strong>{{ $users->total() }}</strong> entries
    </div>

    <nav>
        <ul class="pagination mb-0">
            {{-- First Page --}}
            <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $users->appends(request()->except('page'))->url(1) }}" aria-label="First">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            {{-- Page Numbers --}}
            @php
                $start = max($users->currentPage() - 2, 1);
                $end = min($users->currentPage() + 2, $users->lastPage());
            @endphp

            {{-- Show first page and dots if needed --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $users->appends(request()->except('page'))->url(1) }}">1</a>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            {{-- Page range --}}
            @for ($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $users->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Show last page and dots if needed --}}
            @if ($end < $users->lastPage())
                @if ($end < $users->lastPage() - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $users->appends(request()->except('page'))->url($users->lastPage()) }}">{{ $users->lastPage() }}</a>
                </li>
            @endif

            {{-- Last Page --}}
            <li class="page-item {{ $users->currentPage() == $users->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $users->appends(request()->except('page'))->url($users->lastPage()) }}" aria-label="Last">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

