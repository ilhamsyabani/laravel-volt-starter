{{--
    Progress Bar - Animated loading indicator

    Usage:
    <x-ui.progress :value="75" label="Uploading..." />
    <x-ui.progress :value="100" :show-label="false" color="success" />
--}}
@props([
    'value' => 0,           // 0-100
    'label' => null,
    'showLabel' => true,
    'color' => 'primary',   // primary | success | warning | danger
    'size' => 'md',         // sm | md | lg
    'striped' => false,
    'animated' => false,
])

@php
$value = max(0, min(100, (int) $value));

$sizes = [
    'sm' => 'h-1',
    'md' => 'h-2',
    'lg' => 'h-3',
];

$colors = [
    'primary' => 'vs-bg-primary',
    'success' => 'bg-emerald-500',
    'warning' => 'bg-amber-500',
    'danger' => 'bg-red-500',
];

$barClass = ($colors[$color] ?? $colors['primary']) . ' ' . ($striped || $animated ? 'bg-stripes' : '');
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if ($showLabel && $label)
        <div class="flex items-center justify-between text-sm">
            <span class="text-zinc-600 dark:text-zinc-400">{{ $label }}</span>
            <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $value }}%</span>
        </div>
    @elseif ($showLabel && !$label)
        <div class="flex items-center justify-end text-sm">
            <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $value }}%</span>
        </div>
    @endif

    <div class="w-full rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden {{ $sizeClass }}">
        <div
            class="h-full rounded-full transition-all duration-500 ease-out {{ $barClass }}"
            :class="{ 'animate-pulse': {{ $value < 100 && $value > 0 ? 'true' : 'false' }} }"
            style="width: {{ $value }}%;"
            role="progressbar"
            :aria-valuenow="{{ $value }}"
            aria-valuemin="0"
            aria-valuemax="100"
        ></div>
    </div>
</div>