<?php

use App\Models\User;
use function Livewire\Volt\{state, computed};

middleware(['auth', 'verified', 'role:admin']);

state(['search' => '', 'role' => '']);

$users = computed(function () {
    return User::query()
        ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%"))
        ->when($this->role, fn($q) => $q->where('role', $this->role))
        ->latest()
        ->paginate(15);
});

$stats = computed(function () {
    return [
        'total' => User::count(),
        'admins' => User::where('role', 'admin')->count(),
        'users' => User::where('role', 'user')->count(),
        'newThisMonth' => User::whereMonth('created_at', now()->month)->count(),
    ];
});

$delete = function (User $user) {
    if ($user->id === auth()->id()) {
        $this->dispatch('notify', type: 'error', message: 'You cannot delete yourself!');
        return;
    }

    $user->delete();
    $this->dispatch('notify', type: 'success', message: 'User deleted.');
};

$changeRole = function (User $user, string $role) {
    $user->update(['role' => $role]);
    $this->dispatch('notify', type: 'success', message: 'Role updated.');
};
?>

<x-layouts.app>
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Users</h1>
                <p class="mt-1 text-sm text-zinc-500">Manage system users and roles</p>
            </div>
            <x-ui.button icon="user-plus">Add User</x-ui.button>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-ui.card :padding="false">
                <div class="p-4 flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600">
                        @svg('users', 'w-5 h-5')
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500">Total Users</p>
                        <p class="text-2xl font-bold">{{ $this->stats['total'] }}</p>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card :padding="false">
                <div class="p-4 flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600">
                        @svg('shield-check', 'w-5 h-5')
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500">Admins</p>
                        <p class="text-2xl font-bold">{{ $this->stats['admins'] }}</p>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card :padding="false">
                <div class="p-4 flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600">
                        @svg('user', 'w-5 h-5')
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500">Regular Users</p>
                        <p class="text-2xl font-bold">{{ $this->stats['users'] }}</p>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card :padding="false">
                <div class="p-4 flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600">
                        @svg('user-plus', 'w-5 h-5')
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500">New This Month</p>
                        <p class="text-2xl font-bold">{{ $this->stats['newThisMonth'] }}</p>
                    </div>
                </div>
            </x-ui.card>
        </div>

        {{-- Filters --}}
        <x-ui.card :padding="false">
            <div class="p-4 flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Search users..." icon="magnifying-glass" />
                </div>
                <div class="flex gap-2">
                    <x-ui.button size="sm" :variant="$role === '' ? 'primary' : 'secondary'" wire:click="$set('role', '')">
                        All
                    </x-ui.button>
                    <x-ui.button size="sm" :variant="$role === 'superadmin' ? 'primary' : 'secondary'" wire:click="$set('role', 'superadmin')">
                        Superadmin
                    </x-ui.button>
                    <x-ui.button size="sm" :variant="$role === 'admin' ? 'primary' : 'secondary'" wire:click="$set('role', 'admin')">
                        Admin
                    </x-ui.button>
                    <x-ui.button size="sm" :variant="$role === 'user' ? 'primary' : 'secondary'" wire:click="$set('role', 'user')">
                        User
                    </x-ui.button>
                </div>
            </div>
        </x-ui.card>

        {{-- Users Table --}}
        <x-ui.card :padding="false">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <x-ui.table.th>User</x-ui.table.th>
                        <x-ui.table.th>Role</x-ui.table.th>
                        <x-ui.table.th>Joined</x-ui.table.th>
                        <x-ui.table.th class="text-right">Actions</x-ui.table.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($this->users as $user)
                        <x-ui.table.row>
                            <x-ui.table.td>
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar :name="$user->name" size="sm" />
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge :color="$user->getRoleBadgeColor()">
                                    {{ $user->getRoleDisplayName() }}
                                </x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <span class="text-zinc-500">{{ $user->created_at->format('M j, Y') }}</span>
                            </x-ui.table.td>
                            <x-ui.table.td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Role Dropdown --}}
                                    <x-ui.dropdown align="right">
                                        <x-slot:trigger>
                                            <x-ui.button size="sm" variant="ghost" icon="chevron-down">
                                                Change Role
                                            </x-ui.button>
                                        </x-slot:trigger>
                                        <x-ui.dropdown-item wire:click="changeRole({{ $user->id }}, 'superadmin')">
                                            Superadmin
                                        </x-ui.dropdown-item>
                                        <x-ui.dropdown-item wire:click="changeRole({{ $user->id }}, 'admin')">
                                            Admin
                                        </x-ui.dropdown-item>
                                        <x-ui.dropdown-item wire:click="changeRole({{ $user->id }}, 'user')">
                                            User
                                        </x-ui.dropdown-item>
                                    </x-ui.dropdown>

                                    <x-ui.button size="sm" variant="ghost" href="/admin/users/{{ $user->id }}/edit" wire:navigate icon="pencil">
                                        Edit
                                    </x-ui.button>
                                    <x-ui.button size="sm" variant="danger" wire:click="delete({{ $user->id }})" icon="trash">
                                        Delete
                                    </x-ui.button>
                                </div>
                            </x-ui.table.td>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty colspan="4">
                            <x-ui.empty-state icon="users" title="No users found" subtitle="Try adjusting your search or filters." />
                        </x-ui.table.empty>
                    @endforelse
                </tbody>
            </table>

            @if ($this->users->hasPages())
                <div class="border-t border-zinc-200 dark:border-zinc-700 p-4">
                    {{ $this->users->links() }}
                </div>
            @endif
        </x-ui.card>

    </div>
</x-layouts.app>