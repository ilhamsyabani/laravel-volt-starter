@props([
    'size'    => 'md',     // xs | sm | md | lg | xl
    'variant' => 'primary',  // primary | white | current
    'overlay' => false,    // true = full overlay loading (untuk container relative)
    'label'   => null,     // teks di samping/bawah spinner
])

@php
$sizes = [
    'xs' => 'w-3.5 h-3.5',
    'sm' => 'w-4 h-4',
    'md' => 'w-6 h-6',
    'lg' => 'w-8 h-8',
    'xl' => 'w-12 h-12',
];

$colors = [
    'primary' => 'text-[rgb(var(--vs-primary-600))]',
    'white'   => 'text-white',
    'current' => 'text-current',
];

$sizeClass  = $sizes[$size] ?? $sizes['md'];
$colorClass = $colors[$variant] ?? $colors['primary'];

$spinner = <<<HTML
<svg class="{$sizeClass} {$colorClass} animate-spin" fill="none" viewBox="0 0 24 24" role="status" aria-label="Loading">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
</svg>
HTML;
@endphp

@if ($overlay)
    {{-- Full overlay — taruh di dalam parent dengan class "relative" --}}
    <div
        {{ $attributes->merge([
            'class' => 'absolute inset-0 z-10 flex flex-col items-center justify-center gap-2 bg-white/70 dark:bg-zinc-900/70 backdrop-blur-sm rounded-[var(--vs-radius-lg)]'
        ]) }}
    >
        {!! $spinner !!}
        @if ($label)
            <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $label }}</span>
        @endif
    </div>
@else
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
        {!! $spinner !!}
        @if ($label)
            <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $label }}</span>
        @endif
    </div>
@endif
