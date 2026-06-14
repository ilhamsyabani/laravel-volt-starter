@props([
    'variant' => 'primary',   // primary | secondary | ghost | danger | warning | success
    'size'    => 'md',        // sm | md | lg
    'type'    => 'button',
    'href'    => null,
    'icon'    => null,        // heroicon name (leading)
    'iconTrailing' => null,   // heroicon name (trailing)
    'disabled' => false,
    'loading'  => false,
])

@php
$base = 'inline-flex items-center justify-center gap-2 font-medium rounded-[var(--vs-radius)] border transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed select-none';

$variants = [
    'primary'   => 'vs-bg-primary text-white border-transparent hover:vs-bg-primary-hover focus:ring-[rgb(var(--vs-primary-500))] shadow-sm',
    'secondary' => 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 border-zinc-300 dark:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:ring-[rgb(var(--vs-primary-500))] shadow-sm',
    'ghost'     => 'bg-transparent text-zinc-600 dark:text-zinc-300 border-transparent hover:bg-zinc-100 dark:hover:bg-zinc-800 focus:ring-[rgb(var(--vs-primary-500))]',
    'danger'    => 'bg-red-600 text-white border-transparent hover:bg-red-700 focus:ring-red-500 shadow-sm',
    'warning'   => 'bg-amber-500 text-white border-transparent hover:bg-amber-600 focus:ring-amber-400 shadow-sm',
    'success'   => 'bg-emerald-600 text-white border-transparent hover:bg-emerald-700 focus:ring-emerald-500 shadow-sm',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-5 py-2.5 text-base',
];

$iconSizes = ['sm' => 'w-3.5 h-3.5', 'md' => 'w-4 h-4', 'lg' => 'w-5 h-5'];

$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
$iconClass = $iconSizes[$size] ?? $iconSizes['md'];
$tag = $href ? 'a' : 'button';
@endphp

@if ($href)
<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled) aria-disabled="true" tabindex="-1" @endif
>
@else
<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @disabled($disabled || $loading)
>
@endif

    {{-- Loading spinner --}}
    @if ($loading)
        <svg class="{{ $iconClass }} animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
    @elseif ($icon)
        @svg($icon, $iconClass)
    @endif

    {{ $slot }}

    @if ($iconTrailing && !$loading)
        @svg($iconTrailing, $iconClass)
    @endif

@if ($href)
</a>
@else
</button>
@endif
