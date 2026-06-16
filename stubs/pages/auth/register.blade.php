<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Laravel\Folio\{middleware};
use function Livewire\Volt\{state, rules};

middleware(['guest']);

state([
    'name'                  => '',
    'email'                 => '',
    'password'              => '',
    'password_confirmation' => '',
]);

rules([
    'name'     => 'required|string|max:255',
    'email'    => 'required|email|unique:users,email',
    'password' => 'required|string|min:8|confirmed',
]);

$register = function () {
    $validated = $this->validate();

    $user = User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    event(new Registered($user));
    Auth::login($user);

    $this->redirect('/dashboard', navigate: true);
};

?>

<x-layouts.auth title="Create account">
    <div class="w-full max-w-sm mx-auto">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-[var(--vs-radius-lg)] vs-bg-primary text-white font-bold text-xl mb-3">
                {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
            </div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Create account</h1>
            <p class="mt-1 text-sm text-zinc-500">Get started for free</p>
        </div>

        <div>
            <x-ui.card>
                <form wire:submit="register" class="space-y-4">

                    <x-ui.input
                        wire:model="name"
                        label="Full Name"
                        placeholder="John Doe"
                        icon="user"
                        autofocus
                        autocomplete="name"
                        required
                    />

                    <x-ui.input
                        wire:model="email"
                        type="email"
                        label="Email"
                        placeholder="you@example.com"
                        icon="envelope"
                        autocomplete="email"
                        required
                    />

                    <x-ui.input
                        wire:model="password"
                        type="password"
                        label="Password"
                        placeholder="Min. 8 characters"
                        icon="lock-closed"
                        autocomplete="new-password"
                        required
                    />

                    <x-ui.input
                        wire:model="password_confirmation"
                        type="password"
                        label="Confirm Password"
                        placeholder="Repeat password"
                        icon="lock-closed"
                        autocomplete="new-password"
                        required
                    />

                    <x-ui.button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled" wire:target="register">
                        <span wire:loading.remove wire:target="register">Create account</span>
                        <span wire:loading wire:target="register">Creating...</span>
                    </x-ui.button>

                </form>
            </x-ui.card>

            <p class="text-center text-sm text-zinc-500 mt-4">
                Already have an account?
                <a href="/auth/login" wire:navigate class="vs-text-primary hover:underline font-medium">Sign in</a>
            </p>
        </div>

    </div>
</x-layouts.auth>
