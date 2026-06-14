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
            <span class="flex flex-col -space-y-1">
                <svg class="w-3 h-3 {{ $direction === 'asc' ? 'vs-text-primary' : 'text-zinc-300 dark:text-zinc-600 group-hover:text-zinc-400' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3z" clip-rule="evenodd"/>
                </svg>
                <svg class="w-3 h-3 {{ $direction === 'desc' ? 'vs-text-primary' : 'text-zinc-300 dark:text-zinc-600 group-hover:text-zinc-400' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.55-.24l-3.25-3.5a.75.75 0 111.1-1.02l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5A.75.75 0 0110 17z" clip-rule="evenodd"/>
                </svg>
            </span>
        </button>
    </th>
@else
    <th {{ $attributes->merge(['class' => $base]) }} scope="col">
        {{ $slot }}
    </th>
@endif