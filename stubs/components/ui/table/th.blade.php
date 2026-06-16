@props([
    'sortable'  => false,
    'direction' => null,   // 'asc' | 'desc' | null
    'align'     => 'left', // left | center | right
])

@php
$alignClass = ['left' => 'text-left', 'center' => 'text-center', 'right' => 'text-right'][$align] ?? 'text-left';
$base = "px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-300 text-xs uppercase tracking-wide {$alignClass}";
@endphp

@if ($sortable)
    <th {{ $attributes->merge(['class' => $base]) }} scope="col">
        <button type="button" class="inline-flex items-center gap-1 group">
            {{ $slot }}
            <span class="flex flex-col -space-y-1.5">
                @svg('ph-caret-up', 'w-3 h-3 ' . ($direction === 'asc' ? 'vs-text-primary' : 'text-zinc-300 dark:text-zinc-600 group-hover:text-zinc-400'))
                @svg('ph-caret-down', 'w-3 h-3 ' . ($direction === 'desc' ? 'vs-text-primary' : 'text-zinc-300 dark:text-zinc-600 group-hover:text-zinc-400'))
            </span>
        </button>
    </th>
@else
    <th {{ $attributes->merge(['class' => $base]) }} scope="col">
        {{ $slot }}
    </th>
@endif
