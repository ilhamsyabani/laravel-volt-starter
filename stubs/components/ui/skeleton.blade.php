@props([
    'type'  => 'text',  // text | avatar | card | table-row
    'lines' => 3,       // untuk type=text, jumlah baris
])

@php
$shimmer = 'animate-pulse bg-zinc-200 dark:bg-zinc-700 rounded';
@endphp

@if ($type === 'avatar')
    <div {{ $attributes->merge(['class' => "{$shimmer} w-10 h-10 rounded-full"]) }}></div>

@elseif ($type === 'card')
    <div {{ $attributes->merge(['class' => 'rounded-[var(--vs-radius-lg)] border border-zinc-200 dark:border-zinc-700 p-5 space-y-3']) }}>
        <div class="{{ $shimmer }} h-5 w-1/3"></div>
        <div class="{{ $shimmer }} h-4 w-full"></div>
        <div class="{{ $shimmer }} h-4 w-5/6"></div>
    </div>

@elseif ($type === 'table-row')
    <tr {{ $attributes }}>
        @for ($i = 0; $i < ($lines ?: 4); $i++)
            <td class="px-4 py-3">
                <div class="{{ $shimmer }} h-4 w-full max-w-[120px]"></div>
            </td>
        @endfor
    </tr>

@else
    <div {{ $attributes->merge(['class' => 'space-y-2']) }}>
        @for ($i = 0; $i < $lines; $i++)
            <div class="{{ $shimmer }} h-4 {{ $i === $lines - 1 ? 'w-2/3' : 'w-full' }}"></div>
        @endfor
    </div>
@endif
