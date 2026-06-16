<?php

use function Livewire\Volt\{state, computed};

middleware(['auth', 'verified']);

$stats = computed(function () {
    return [
        [
            'label' => 'Total Users',
            'value' => \App\Models\User::count(),
            'icon'  => 'users',
        ],
    ];
});

?>

<x-layouts.app>
@volt('dashboard')
    <div class="space-y-8">

        {{-- Page header --}}
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
                Welcome back, {{ auth()->user()->name }} 👋
            </h1>
            <p class="mt-1 text-sm text-zinc-500">
                Here's what's happening in your app today.
            </p>
        </div>

        {{-- Stats grid --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($this->stats as $stat)
                <x-ui.card :padding="false">
                    <div class="p-5 flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-[var(--vs-radius)] vs-bg-primary-light vs-text-primary">
                            @svg($stat['icon'], 'w-5 h-5')
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stat['value'] }}</p>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        {{-- Quick actions --}}
        <x-ui.card title="Quick Actions">
            <div class="flex flex-wrap gap-3">
                <x-ui.button href="/users" icon="users">
                    Manage Users
                </x-ui.button>
                <x-ui.button href="/settings/profile" icon="user" variant="ghost">
                    Edit Profile
                </x-ui.button>
                <x-ui.button href="/showcase" icon="swatch" variant="secondary">
                    Component Showcase
                </x-ui.button>
            </div>
        </x-ui.card>

    </div>
@endvolt
</x-layouts.app>
