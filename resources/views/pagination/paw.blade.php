@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last    = $paginator->lastPage();

        
        $start = max(1, min($current - 2, $last - 4));
        $end   = min($last, $start + 4);

        $btn      = 'flex h-9 min-w-9 items-center justify-center rounded-lg px-2 text-sm font-medium transition';
        $idle     = 'border border-white/80 bg-white/70 text-slate-600 hover:bg-white hover:text-teal-700';
        $active   = 'bg-gradient-to-r from-[#0e7c86] to-[#2aa6a0] text-white shadow';
        $disabled = 'cursor-not-allowed border border-white/60 bg-white/40 text-slate-300';
    @endphp

    <nav class="flex flex-col items-center justify-between gap-3 sm:flex-row" aria-label="Pagination">

        <p class="text-sm text-slate-500">
            Showing <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}</span>
            to <span class="font-semibold text-slate-700">{{ $paginator->lastItem() }}</span>
            of <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span> tasks
        </p>

        <ul class="flex items-center gap-1">

            {{-- Previous ‹ --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span class="{{ $btn }} {{ $disabled }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" title="Previous" class="{{ $btn }} {{ $idle }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                @endif
            </li>

            {{-- Page numbers (max 5) --}}
            @for ($page = $start; $page <= $end; $page++)
                <li>
                    @if ($page === $current)
                        <span aria-current="page" class="{{ $btn }} {{ $active }}">{{ $page }}</span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="{{ $btn }} {{ $idle }}">{{ $page }}</a>
                    @endif
                </li>
            @endfor

            {{-- Next › --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" title="Next" class="{{ $btn }} {{ $idle }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="{{ $btn }} {{ $disabled }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif