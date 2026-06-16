{{--
    Tabs — full Alpine.js, no Livewire round-trip needed for switching.

    Usage:
    <x-ui.tabs :tabs="['profile' => 'Profile', 'security' => 'Security', 'notifications' => 'Notifications']" default="profile">
        <x-slot:profile>... content ...</x-slot:profile>
        <x-slot:security>... content ...</x-slot:security>
        <x-slot:notifications>... content ...</x-slot:notifications>
    </x-ui.tabs>
--}}
@props([
    'tabs'    => [],          // ['key' => 'Label']
    'default' => null,
])

@php
$defaultTab = $default ?? array_key_first($tabs);
@endphp

<div x-data="{ activeTab: '{{ $defaultTab }}' }">

    {{-- Tab buttons --}}
    <div class="flex gap-1 border-b border-zinc-200 dark:border-zinc-700 overflow-x-auto" role="tablist">
        @foreach ($tabs as $key => $label)
            <button
                type="button"
                role="tab"
                @click="activeTab = '{{ $key }}'"
                :class="activeTab === '{{ $key }}'
                    ? 'border-[rgb(var(--vs-primary-600))] vs-text-primary'
                    : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 hover:border-zinc-300'"
                class="shrink-0 px-4 py-2.5 text-sm font-medium border-b-2 transition -mb-px"
                :aria-selected="activeTab === '{{ $key }}'"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Tab panels --}}
    <div class="mt-4">
        @foreach ($tabs as $key => $label)
            <div x-show="activeTab === '{{ $key }}'" x-transition.opacity.duration.150ms role="tabpanel">
                {{ ${$key} ?? '' }}
            </div>
        @endforeach
    </div>
</div>
