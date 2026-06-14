@props([
    'align' => 'right',  // left | right
    'width' => '48',     // tailwind width number, e.g. 48 = 12rem
])

@php
$alignClass = $align === 'left' ? 'left-0 origin-top-left' : 'right-0 origin-top-right';
@endphp

<div x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false" class="relative">

    {{-- Trigger --}}
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click="open = false"
        style="display: none;"
        class="absolute z-50 mt-2 w-{{ $width }} {{ $alignClass }} rounded-[var(--vs-radius)] bg-white dark:bg-zinc-800 shadow-[var(--vs-shadow-lg)] border border-zinc-200 dark:border-zinc-700 py-1 focus:outline-none"
        role="menu"
    >
        {{ $slot }}
    </div>
</div>