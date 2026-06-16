<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use function Livewire\Volt\{state, mount};

middleware(['auth', 'verified']);

state([
    'name'  => '',
    'email' => '',
    'current_password' => '',
    'new_password' => '',
    'new_password_confirmation' => '',
]);

mount(function () {
    $this->name  = auth()->user()->name;
    $this->email = auth()->user()->email;
});

$updateProfile = function () {
    $this->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . auth()->id(),
    ]);

    auth()->user()->update([
        'name'  => $this->name,
        'email' => $this->email,
    ]);

    $this->dispatch('notify', type: 'success', message: 'Profile updated successfully.');
};

$updatePassword = function () {
    $this->validate([
        'current_password' => 'required|current_password',
        'new_password'     => ['required', 'confirmed', Password::defaults()],
    ]);

    auth()->user()->update([
        'password' => Hash::make($this->new_password),
    ]);

    $this->current_password = '';
    $this->new_password = '';
    $this->new_password_confirmation = '';

    $this->dispatch('notify', type: 'success', message: 'Password updated successfully.');
};

?>

<x-layouts.app title="Profile Settings">
    <div class="max-w-2xl space-y-8">

        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Profile Settings</h1>
            <p class="mt-1 text-sm text-zinc-500">Manage your account information.</p>
        </div>

        {{-- Profile Info --}}
        <x-ui.card title="Personal Information">
            <form wire:submit="updateProfile" class="space-y-4">
                <x-ui.input wire:model="name"  label="Full Name"  icon="user" required />
                <x-ui.input wire:model="email" type="email" label="Email Address" icon="envelope" required />

                <x-ui.button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="updateProfile">
                    Save Changes
                </x-ui.button>
            </form>
        </x-ui.card>

        {{-- Change Password --}}
        <x-ui.card title="Change Password">
            <form wire:submit="updatePassword" class="space-y-4">
                <x-ui.input wire:model="current_password" type="password" label="Current Password" icon="lock-closed" />
                <x-ui.input wire:model="new_password" type="password" label="New Password" icon="lock-closed" />
                <x-ui.input wire:model="new_password_confirmation" type="password" label="Confirm New Password" icon="lock-closed" />

                <x-ui.button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="updatePassword">
                    Update Password
                </x-ui.button>
            </form>
        </x-ui.card>

        {{-- Danger Zone --}}
        <x-ui.card>
            <h2 class="text-base font-semibold text-red-600 mb-1">Danger Zone</h2>
            <p class="text-sm text-zinc-500 mb-4">Once your account is deleted, all data will be permanently removed.</p>
            <x-ui.button variant="danger" wire:confirm="Are you sure you want to delete your account? This action cannot be undone.">
                Delete Account
            </x-ui.button>
        </x-ui.card>

    </div>
</x-layouts.app>
