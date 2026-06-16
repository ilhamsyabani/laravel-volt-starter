@props([
    'name',           // wajib: identifier unik, dipakai untuk trigger
    'title'   => null,
    'maxWidth'=> 'md',   // sm | md | lg | xl | 2xl
])

@php
$widths = [
    'sm'  => 'max-w-sm',
    'md'  => 'max-w-md',
    'lg'  => 'max-w-lg',
    'xl'  => 'max-w-xl',
    '2xl' => 'max-w-2xl',
];
$widthClass = $widths[$maxWidth] ?? $widths['md'];
@endphp

{{--
    Trigger dari mana saja:
    <x-ui.button @click="$dispatch('open-modal', { name: '{{ $name }}' })">Open</x-ui.button>

    Tutup dari dalam modal:
    @click="$dispatch('close-modal', { name: '{{ $name }}' })"

    Dari Livewire (PHP):
    $this->dispatch('open-modal', name: 'edit-user');
--}}

<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') { open = true; document.body.style.overflow = 'hidden' }"
    x-on:close-modal.window="if (!$event.detail || $event.detail.name === '{{ $name }}') { open = false; document.body.style.overflow = '' }"
    x-on:keydown.escape.window="if (open) { open = false; document.body.style.overflow = '' }"
    x-show="open"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    @if($title) aria-labelledby="modal-title-{{ $name }}" @endif
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false; document.body.style.overflow = ''"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm"
        aria-hidden="true"
    ></div>

    {{-- Panel --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="relative w-full {{ $widthClass }} rounded-[var(--vs-radius-lg)] bg-white dark:bg-zinc-900 shadow-[var(--vs-shadow-lg)] border border-zinc-200 dark:border-zinc-700"
        >
            @if ($title)
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
                    <h3 id="modal-title-{{ $name }}" class="text-base font-semibold text-zinc-900 dark:text-white">
                        {{ $title }}
                    </h3>
                    <button
                        @click="open = false; document.body.style.overflow = ''"
                        class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition"
                        aria-label="Close modal"
                    >
                        @svg('ph-x', 'w-5 h-5')
                    </button>
                </div>
            @endif

            <div class="p-6">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 rounded-b-[var(--vs-radius-lg)]">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
