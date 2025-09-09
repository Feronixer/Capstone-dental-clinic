@props(['column', 'label', 'center' => false])

@php
    $isSorted = request('sort') === $column;
    $direction = request('direction') ?? 'asc';
    $nextDirection = $isSorted && $direction === 'asc' ? 'desc' : 'asc';
@endphp

<th class="align-middle {{ $center ? 'text-center' : '' }}">
    <form class="sortable-form d-flex align-items-center justify-content-center gap-2 text-decoration-none"
          data-sort="{{ $column }}"
          data-direction="{{ $nextDirection }}">
        <button type="submit"
                class="btn btn-link p-0 m-0 text-black text-decoration-none d-flex align-items-center gap-2 {{ $isSorted ? 'active' : '' }}">
            {{ $label }}
            @if (! $isSorted)
                <i class="bi bi-arrow-down-up text-secondary"></i>
            @else
                @if ($direction === 'asc')
                    <i class="bi bi-arrow-up text-primary"></i>
                @else
                    <i class="bi bi-arrow-down text-primary"></i>
                @endif
            @endif
        </button>
    </form>
</th>
