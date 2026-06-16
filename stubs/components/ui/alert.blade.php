@props([
    'type'        => 'info',   // info | success | warning | error
    'title'       => null,
    'dismissible' => false,
])

@php
$variants = [
    'info' => [
        'wrap' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50 text-blue-800 dark:text-blue-300',
        'icon' => 'text-blue-500 dark:text-blue-400',
        'ph'   => 'ph-info',
    ],
    'success' => [
        'wrap' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-300',
        'icon' => 'text-emerald-500 dark:text-emerald-400',
        'ph'   => 'ph-check-circle',
    ],
    'warning' => [
        'wrap' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800/50 text-amber-800 dark:text-amber-300',
        'icon' => 'text-amber-500 dark:text-amber-400',
        'ph'   => 'ph-warning',
    ],
    'error' => [
        'wrap' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 text-red-800 dark:text-red-300',
        'icon' => 'text-red-500 dark:text-red-400',
        'ph'   => 'ph-warning-circle',
    ],
];

$v = $variants[$type] ?? $variants['info'];
@endphp

<div
    {{ $attributes->merge(['class' => "rounded-[var(--vs-radius)] border p-4 {$v['wrap']}"]) }}
    role="alert"
    @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif
>
    <div class="flex gap-3">
        @svg($v['ph'], 'w-5 h-5 shrink-0 ' . $v['icon'])
        <div class="flex-1 text-sm">
            @if ($title)
                <p class="font-semibold mb-0.5">{{ $title }}</p>
            @endif
            <div class="{{ $title ? 'opacity-90' : '' }}">{{ $slot }}</div>
        </div>
        @if ($dismissible)
            <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100 transition" aria-label="Dismiss">
                @svg('ph-x', 'w-4 h-4')
            </button>
        @endif
    </div>
</div>
