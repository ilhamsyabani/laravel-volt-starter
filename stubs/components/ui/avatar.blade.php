@props([
    'src'  => null,
    'name' => null,        // untuk generate initials jika src kosong
    'size' => 'md',        // xs | sm | md | lg | xl
    'status' => null,      // online | offline | busy | away — tampilkan dot status
])

@php
$sizes = [
    'xs' => 'w-6 h-6 text-xs',
    'sm' => 'w-8 h-8 text-sm',
    'md' => 'w-10 h-10 text-base',
    'lg' => 'w-12 h-12 text-lg',
    'xl' => 'w-16 h-16 text-2xl',
];

$statusColors = [
    'online'  => 'bg-emerald-500',
    'offline' => 'bg-zinc-400',
    'busy'    => 'bg-red-500',
    'away'    => 'bg-amber-500',
];

$statusSizes = [
    'xs' => 'w-1.5 h-1.5',
    'sm' => 'w-2 h-2',
    'md' => 'w-2.5 h-2.5',
    'lg' => 'w-3 h-3',
    'xl' => 'w-4 h-4',
];

$sizeClass = $sizes[$size] ?? $sizes['md'];

// Generate initials from name
$initials = '';
if ($name) {
    $words = explode(' ', trim($name));
    $initials = strtoupper(substr($words[0], 0, 1) . (count($words) > 1 ? substr(end($words), 0, 1) : ''));
}
@endphp

<div class="relative inline-flex shrink-0">
    @if ($src)
        <img
            src="{{ $src }}"
            alt="{{ $name ?? 'Avatar' }}"
            {{ $attributes->merge(['class' => "{$sizeClass} rounded-full object-cover ring-2 ring-white dark:ring-zinc-900"]) }}
        />
    @else
        <div {{ $attributes->merge(['class' => "{$sizeClass} rounded-full vs-bg-primary-light vs-text-primary font-semibold flex items-center justify-center ring-2 ring-white dark:ring-zinc-900"]) }}>
            {{ $initials ?: '?' }}
        </div>
    @endif

    @if ($status)
        <span
            class="absolute bottom-0 right-0 rounded-full ring-2 ring-white dark:ring-zinc-900 {{ $statusColors[$status] ?? $statusColors['offline'] }} {{ $statusSizes[$size] ?? $statusSizes['md'] }}"
            aria-label="Status: {{ $status }}"
        ></span>
    @endif
</div>