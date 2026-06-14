@props([
    'title'    => null,
    'subtitle' => null,
    'padding'  => true,   // false untuk full-bleed content (mis. table)
])

@php
$paddingClass = $padding ? 'p-6' : '';
@endphp

<div {{ $attributes->merge([
    'class' => 'rounded-[var(--vs-radius-lg)] border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-[var(--vs-shadow-sm)]'
]) }}>

    @if ($title || $subtitle || isset($actions))
        <div class="flex items-start justify-between gap-4 px-6 pt-6 {{ $padding ? '' : 'pb-6' }}">
            <div>
                @if ($title)
                    <h3 class="text-base font-semibold text-zinc-900 dark:text-white">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex items-center gap-2 shrink-0">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif

    <div class="{{ $paddingClass }} {{ ($title || $subtitle) && $padding ? 'pt-4' : '' }}">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-zinc-100 dark:border-zinc-800 px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/50 rounded-b-[var(--vs-radius-lg)]">
            {{ $footer }}
        </div>
    @endisset

</div>
