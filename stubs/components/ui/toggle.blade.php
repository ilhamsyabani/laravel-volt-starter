@props([
    'label'       => null,
    'description' => null,
    'id'          => null,
    'size'        => 'md',  // sm | md | lg
])

@php
$toggleId = $id ?? 'toggle-' . uniqid();

$track = [
    'sm' => 'w-8 h-4',
    'md' => 'w-11 h-6',
    'lg' => 'w-14 h-7',
];
$thumb = [
    'sm' => 'w-3 h-3 peer-checked:translate-x-4',
    'md' => 'w-5 h-5 peer-checked:translate-x-5',
    'lg' => 'w-6 h-6 peer-checked:translate-x-7',
];
$offset = ['sm' => 'top-0.5 left-0.5', 'md' => 'top-0.5 left-0.5', 'lg' => 'top-0.5 left-0.5'];
@endphp

<div class="flex items-center justify-between gap-4">
    @if ($label || $description)
        <div>
            @if ($label)
                <label for="{{ $toggleId }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 cursor-pointer">
                    {{ $label }}
                </label>
            @endif
            @if ($description)
                <p class="text-xs text-zinc-400">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div class="relative inline-flex items-center">
        <input
            id="{{ $toggleId }}"
            type="checkbox"
            {{ $attributes->merge(['class' => 'peer sr-only']) }}
        />
        <label
            for="{{ $toggleId }}"
            class="relative flex cursor-pointer items-center {{ $track[$size] ?? $track['md'] }} rounded-full bg-zinc-200 dark:bg-zinc-700 transition-colors peer-checked:bg-[rgb(var(--vs-primary-600))] peer-focus-visible:ring-2 peer-focus-visible:ring-[rgb(var(--vs-primary-500)/0.4)] peer-focus-visible:ring-offset-2 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
        >
            <span
                class="absolute {{ $offset[$size] ?? $offset['md'] }} {{ $thumb[$size] ?? $thumb['md'] }} rounded-full bg-white shadow-sm transition-transform duration-200"
                aria-hidden="true"
            ></span>
        </label>
    </div>
</div>
