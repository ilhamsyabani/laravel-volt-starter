<?php

use function Livewire\Volt\{state, rules};

state([
    'email' => '',
    'password' => '',
    'remember' => false,
]);

rules([
    'email' => 'required|email',
    'password' => 'required|string',
]);

$login = function () {
    $this->validate();

    if (! auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
        $this->addError('email', __('auth.failed'));
        return;
    }

    session()->regenerate();
    redirect()->intended(route('dashboard'));
};
?>

<div>
    <form wire:submit.prevent="login" class="space-y-4">

        <x-ui.input
            wire:model.live="email"
            type="email"
            label="Email"
            placeholder="you@example.com"
            icon="envelope"
            autofocus
            autocomplete="email"
            required
        />

        <x-ui.input
            wire:model.live="password"
            type="password"
            label="Password"
            placeholder="••••••••"
            icon="lock-closed"
            autocomplete="current-password"
            required
        />

        <div class="flex items-center justify-between">
            <x-ui.checkbox wire:model.live="remember" label="Remember me" />
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs vs-text-primary hover:underline">
                    Forgot password?
                </a>
            @endif
        </div>

        <x-ui.button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled" wire:target="login">
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login">Signing in...</span>
        </x-ui.button>

    </form>
</div>