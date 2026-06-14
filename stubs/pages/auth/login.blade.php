<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use function Livewire\Volt\{state, rules};

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
    $this->redirectIntended(route('dashboard'), navigate: true);
};

?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }} — Log in</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 flex items-center justify-center p-4 text-zinc-900 dark:text-zinc-100">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-[var(--vs-radius-lg)] vs-bg-primary text-white font-bold text-xl mb-3">
                {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
            </div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ config('app.name') }}</h1>
            <p class="mt-1 text-sm text-zinc-500">Sign in to your account</p>
        </div>

        {{-- Card --}}
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
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-xs vs-text-primary hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <x-ui.button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled" wire:target="login">
                    <span wire:loading.remove wire:target="login">Sign in</span>
                    <span wire:loading wire:target="login">Signing in...</span>
                </x-ui.button>

            </form>
        </x-ui.card>

        @if (Route::has('register'))
            <p class="text-center text-sm text-zinc-500 mt-4">
                Don't have an account?
                <a href="{{ route('register') }}" wire:navigate class="vs-text-primary hover:underline font-medium">Sign up</a>
            </p>
        @endif

    </div>

    @livewireScripts
</body>
</html>
