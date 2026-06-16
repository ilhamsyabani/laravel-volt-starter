<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use function Laravel\Folio\{middleware};
use function Livewire\Volt\{state, rules};

middleware(['guest']);

state([
    'email'    => '',
    'password' => '',
    'remember' => false,
]);

rules([
    'email'    => 'required|email',
    'password' => 'required|string',
]);

$login = function () {
    $this->validate();

    if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    session()->regenerate();
    $this->redirectIntended('/dashboard', navigate: true);
};

?>

<x-layouts.auth title="Sign in">
    <div class="w-full max-w-sm mx-auto">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-[var(--vs-radius-lg)] vs-bg-primary text-white font-bold text-xl mb-3">
                {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
            </div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ config('app.name') }}</h1>
            <p class="mt-1 text-sm text-zinc-500">Sign in to your account</p>
        </div>

        <div>
            <x-ui.card>
                <form wire:submit="login" class="space-y-4">

                    <x-ui.input
                        wire:model="email"
                        type="email"
                        label="Email"
                        placeholder="you@example.com"
                        icon="envelope"
                        autofocus
                        autocomplete="email"
                        required
                    />

                    <x-ui.input
                        wire:model="password"
                        type="password"
                        label="Password"
                        placeholder="••••••••"
                        icon="lock-closed"
                        autocomplete="current-password"
                        required
                    />

                    <div class="flex items-center justify-between">
                        <x-ui.checkbox wire:model="remember" label="Remember me" />
                        <a href="/auth/forgot-password" class="text-xs vs-text-primary hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <x-ui.button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled" wire:target="login">
                        <span wire:loading.remove wire:target="login">Sign in</span>
                        <span wire:loading wire:target="login">Signing in...</span>
                    </x-ui.button>

                </form>
            </x-ui.card>

            <p class="text-center text-sm text-zinc-500 mt-4">
                Don't have an account?
                <a href="/auth/register" wire:navigate class="vs-text-primary hover:underline font-medium">Sign up</a>
            </p>
        </div>

    </div>
</x-layouts.auth>
