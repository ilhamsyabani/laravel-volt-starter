@props([
    'color' => 'zinc',  // zinc | primary | green | red | yellow | blue | purple | pink
    'size'  => 'sm',    // sm | md
    'dot'   => false,   // tampilkan dot indikator
])

@php
$colors = [
    'zinc'    => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
    'primary' => 'bg-[rgb(var(--vs-primary-100))] text-[rgb(var(--vs-primary-700))] dark:bg-[rgb(var(--vs-primary-900)/0.3)] dark:text-[rgb(var(--vs-primary-300))]',
    'green'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
    'red'     => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
    'yellow'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    'blue'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'purple'  => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    'pink'    => 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400',
];

$dotColors = [
    'zinc'    => 'bg-zinc-400',
    'primary' => 'bg-[rgb(var(--vs-primary-500))]',
    'green'   => 'bg-emerald-500',
    'red'     => 'bg-red-500',
    'yellow'  => 'bg-amber-500',
    'blue'    => 'bg-blue-500',
    'purple'  => 'bg-purple-500',
    'pink'    => 'bg-pink-500',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs gap-1',
    'md' => 'px-2.5 py-1 text-sm gap-1.5',
];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center rounded-full font-medium ' . ($colors[$color] ?? $colors['zinc']) . ' ' . ($sizes[$size] ?? $sizes['sm'])
]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$color] ?? $dotColors['zinc'] }}" aria-hidden="true"></span>
    @endif
    {{ $slot }}
</span>
