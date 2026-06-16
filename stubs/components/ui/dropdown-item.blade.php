@props([
    'href'    => null,
    'icon'    => null,
    'danger'  => false,
])

@php
$base = 'flex items-center gap-2 w-full px-4 py-2 text-sm text-left transition';
$colorClass = $danger
    ? 'text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20'
    : 'text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700';
$tag = $href ? 'a' : 'button';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "{$base} {$colorClass}"]) }} role="menuitem">
        @if ($icon) @svg($icon, 'w-4 h-4') @endif
        {{ $slot }}
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => "{$base} {$colorClass}"]) }} role="menuitem">
        @if ($icon) @svg($icon, 'w-4 h-4') @endif
        {{ $slot }}
    </button>
@endif
