{{--
    Pagination — pass Laravel paginator instance.
    Usage: <x-ui.pagination :paginator="$users" />
    Bekerja dengan Livewire wire:click via $paginator->onEachSide(1) links manual.
--}}
@props(['paginator'])

@if ($paginator->hasPages())
<nav class="flex items-center justify-between gap-4 px-1" role="navigation" aria-label="Pagination">

    {{-- Mobile: simple prev/next --}}
    <div class="flex sm:hidden gap-2 w-full">
        @if ($paginator->onFirstPage())
            <span class="flex-1 text-center px-3 py-2 text-sm rounded-[var(--vs-radius)] border border-zinc-200 dark:border-zinc-700 text-zinc-400">Previous</span>
        @else
            <button wire:click="previousPage" class="flex-1 text-center px-3 py-2 text-sm rounded-[var(--vs-radius)] border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">Previous</button>
        @endif

        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" class="flex-1 text-center px-3 py-2 text-sm rounded-[var(--vs-radius)] border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">Next</button>
        @else
            <span class="flex-1 text-center px-3 py-2 text-sm rounded-[var(--vs-radius)] border border-zinc-200 dark:border-zinc-700 text-zinc-400">Next</span>
        @endif
    </div>

    {{-- Desktop --}}
    <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
        <p class="text-sm text-zinc-500">
            Showing <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ $paginator->firstItem() }}</span>
            to <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ $paginator->lastItem() }}</span>
            of <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ $paginator->total() }}</span> results
        </p>

        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-[var(--vs-radius)] text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                    @svg('ph-caret-left', 'w-4 h-4')
                </span>
            @else
                <button wire:click="previousPage" class="inline-flex items-center justify-center w-9 h-9 rounded-[var(--vs-radius)] text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition" aria-label="Previous page">
                    @svg('ph-caret-left', 'w-4 h-4')
                </button>
            @endif

            {{-- Page numbers --}}
            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-[var(--vs-radius)] vs-bg-primary text-white text-sm font-medium" aria-current="page">
                        {{ $page }}
                    </span>
                @else
                    <button wire:click="gotoPage({{ $page }})" class="inline-flex items-center justify-center w-9 h-9 rounded-[var(--vs-radius)] text-sm text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        {{ $page }}
                    </button>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" class="inline-flex items-center justify-center w-9 h-9 rounded-[var(--vs-radius)] text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition" aria-label="Next page">
                    @svg('ph-caret-right', 'w-4 h-4')
                </button>
            @else
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-[var(--vs-radius)] text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                    @svg('ph-caret-right', 'w-4 h-4')
                </span>
            @endif
        </div>
    </div>
</nav>
@endif
