{{--
    Tooltip — wrap any element. Pure CSS + Alpine for show/hide.

    Usage:
    <x-ui.tooltip text="Delete this item">
        <x-ui.button variant="ghost" icon="trash" />
    </x-ui.tooltip>
--}}
@props([
    'text'     => '',
    'position' => 'top', // top | bottom | left | right
])

@php
$positions = [
    'top'    => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
    'left'   => 'right-full top-1/2 -translate-y-1/2 mr-2',
    'right'  => 'left-full top-1/2 -translate-y-1/2 ml-2',
];

$arrowPositions = [
    'top'    => 'top-full left-1/2 -translate-x-1/2 -mt-1 border-t-zinc-800 dark:border-t-zinc-700 border-x-transparent border-b-transparent',
    'bottom' => 'bottom-full left-1/2 -translate-x-1/2 -mb-1 border-b-zinc-800 dark:border-b-zinc-700 border-x-transparent border-t-transparent',
    'left'   => 'left-full top-1/2 -translate-y-1/2 -ml-1 border-l-zinc-800 dark:border-l-zinc-700 border-y-transparent border-r-transparent',
    'right'  => 'right-full top-1/2 -translate-y-1/2 -mr-1 border-r-zinc-800 dark:border-r-zinc-700 border-y-transparent border-l-transparent',
];
@endphp

<div class="relative inline-flex" x-data="{ show: false }">
    <div @mouseenter="show = true" @mouseleave="show = false" @focus="show = true" @blur="show = false">
        {{ $slot }}
    </div>

    <div
        x-show="show"
        x-transition.opacity.duration.100ms
        style="display: none;"
        class="absolute z-50 {{ $positions[$position] ?? $positions['top'] }} whitespace-nowrap rounded-md bg-zinc-800 dark:bg-zinc-700 px-2 py-1 text-xs text-white pointer-events-none"
        role="tooltip"
    >
        {{ $text }}
        <div class="absolute w-0 h-0 border-4 {{ $arrowPositions[$position] ?? $arrowPositions['top'] }}"></div>
    </div>
</div>