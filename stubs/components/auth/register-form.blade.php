<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Livewire\Volt\{state, rules};

state([
    'name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => '',
]);

rules([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:8|confirmed',
]);

$register = function () {
    $validated = $this->validate();

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    event(new Registered($user));
    Auth::login($user);

    redirect(route('dashboard'));
};
?>

<div>
    <form wire:submit.prevent="register" class="space-y-4">

        <x-ui.input
            wire:model.live="name"
            type="text"
            label="Full Name"
            placeholder="John Doe"
            icon="user"
            autofocus
            autocomplete="name"
            required
        />

        <x-ui.input
            wire:model.live="email"
            type="email"
            label="Email"
            placeholder="you@example.com"
            icon="envelope"
            autocomplete="email"
            required
        />

        <x-ui.input
            wire:model.live="password"
            type="password"
            label="Password"
            placeholder="Min. 8 characters"
            icon="lock-closed"
            autocomplete="new-password"
            required
        />

        <x-ui.input
            wire:model.live="password_confirmation"
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
</div>