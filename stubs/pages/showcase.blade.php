<?php

use function Livewire\Volt\{state};
use function Laravel\Folio\middleware;

middleware(['auth', 'verified']);

?>

<x-layouts.app title="Component Showcase">
    <div class="space-y-10 max-w-4xl">

        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Component Showcase</h1>
            <p class="mt-1 text-sm text-zinc-500">Preview all UI components — use this to test your theme.</p>
        </div>

        {{-- Buttons --}}
        <x-ui.card title="Buttons" subtitle="Variant, size, icon, loading state">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button variant="primary">Primary</x-ui.button>
                <x-ui.button variant="secondary">Secondary</x-ui.button>
                <x-ui.button variant="ghost">Ghost</x-ui.button>
                <x-ui.button variant="danger">Danger</x-ui.button>
                <x-ui.button variant="warning">Warning</x-ui.button>
                <x-ui.button variant="success">Success</x-ui.button>
            </div>
            <div class="flex flex-wrap items-center gap-3 mt-4">
                <x-ui.button size="sm" icon="ph-plus">Small + Icon</x-ui.button>
                <x-ui.button size="md" icon="ph-trash" variant="danger">Delete</x-ui.button>
                <x-ui.button size="lg" iconTrailing="ph-arrow-right">Continue</x-ui.button>
                <x-ui.button loading>Loading...</x-ui.button>
                <x-ui.button disabled>Disabled</x-ui.button>
            </div>
        </x-ui.card>

        {{-- Form elements --}}
        <x-ui.card title="Form Elements" subtitle="Input, Textarea, Select with validation">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input
                    label="Full Name"
                    placeholder="Enter name"
                    icon="ph-user"
                    required
                />
                <x-ui.input
                    label="Email (with error)"
                    name="email_demo"
                    error="Invalid email format"
                    value="invalid-email"
                    icon="ph-envelope"
                />
                <x-ui.select
                    label="Select Category"
                    :options="['public' => 'Public', 'user' => 'User', 'internal' => 'Internal']"
                />
                <x-ui.input
                    label="Search"
                    placeholder="Search something..."
                    icon="ph-magnifying-glass"
                    iconTrailing="ph-x"
                />
            </div>
            <div class="mt-4">
                <x-ui.textarea
                    label="Description"
                    placeholder="Write description..."
                    hint="Max 200 characters"
                    :maxlength="200"
                />
            </div>
        </x-ui.card>

        {{-- Checkbox, Radio, Toggle --}}
        <x-ui.card title="Checkbox, Radio & Toggle">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Checkbox</p>
                    <x-ui.checkbox label="I agree to the terms & conditions" />
                    <x-ui.checkbox label="Send me promotional emails" description="You can unsubscribe at any time" />
                </div>

                <div class="space-y-3">
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Radio Group</p>
                    <x-ui.radio
                        name="demo_radio"
                        :selected="'option-a'"
                        :options="[
                            ['value' => 'option-a', 'label' => 'Option A', 'description' => 'Brief description for option A'],
                            ['value' => 'option-b', 'label' => 'Option B'],
                        ]"
                    />
                </div>

                <div class="space-y-3 md:col-span-2">
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Toggle</p>
                    <x-ui.toggle label="Enable notifications" description="Receive notifications via email" />
                    <x-ui.toggle label="Private mode" size="lg" />
                </div>
            </div>
        </x-ui.card>

        {{-- Badges --}}
        <x-ui.card title="Badges">
            <div class="flex flex-wrap gap-2">
                <x-ui.badge color="zinc">Default</x-ui.badge>
                <x-ui.badge color="primary">Primary</x-ui.badge>
                <x-ui.badge color="green" dot>Active</x-ui.badge>
                <x-ui.badge color="red" dot>Failed</x-ui.badge>
                <x-ui.badge color="yellow">Pending</x-ui.badge>
                <x-ui.badge color="blue">Info</x-ui.badge>
                <x-ui.badge color="purple" size="md">Premium</x-ui.badge>
            </div>
        </x-ui.card>

        {{-- Alerts --}}
        <x-ui.card title="Alerts" subtitle="Inline notification">
            <div class="space-y-3">
                <x-ui.alert type="info" title="Information">
                    The survey will close on June 30, 2026.
                </x-ui.alert>
                <x-ui.alert type="success" title="Success!">
                    Your answer has been saved.
                </x-ui.alert>
                <x-ui.alert type="warning" dismissible>
                    Some questions have not been answered.
                </x-ui.alert>
                <x-ui.alert type="error" title="Failed">
                    Could not load survey data. Please try again.
                </x-ui.alert>
            </div>
        </x-ui.card>

        {{-- Spinner --}}
        <x-ui.card title="Spinner / Loading">
            <div class="flex items-center gap-6">
                <x-ui.spinner size="xs" />
                <x-ui.spinner size="sm" />
                <x-ui.spinner size="md" />
                <x-ui.spinner size="lg" />
                <x-ui.spinner size="xl" />
                <x-ui.spinner label="Loading data..." />
            </div>
        </x-ui.card>

        {{-- ════════ TIER 2 ════════ --}}

        {{-- Breadcrumb --}}
        <x-ui.card title="Breadcrumb">
            <x-ui.breadcrumb :items="[
                ['label' => 'Dashboard', 'href' => '/dashboard'],
                ['label' => 'Surveys', 'href' => '/surveys'],
                ['label' => 'Edit Survey'],
            ]" />
        </x-ui.card>

        {{-- Avatar --}}
        <x-ui.card title="Avatar">
            <div class="flex items-center gap-4">
                <x-ui.avatar name="John Doe" size="xs" />
                <x-ui.avatar name="Jane Smith" size="sm" status="online" />
                <x-ui.avatar name="Robert Brown" size="md" status="busy" />
                <x-ui.avatar name="Alice Johnson" size="lg" status="away" />
                <x-ui.avatar name="Charlie Davis" size="xl" status="offline" />
            </div>
        </x-ui.card>

        {{-- Tooltip --}}
        <x-ui.card title="Tooltip" subtitle="Hover elements below">
            <div class="flex items-center gap-4">
                <x-ui.tooltip text="Edit this data" position="top">
                    <x-ui.button variant="ghost" icon="ph-pencil" size="sm" />
                </x-ui.tooltip>
                <x-ui.tooltip text="Delete permanently" position="bottom">
                    <x-ui.button variant="ghost" icon="ph-trash" size="sm" />
                </x-ui.tooltip>
                <x-ui.tooltip text="View full details" position="right">
                    <x-ui.badge color="primary">Info</x-ui.badge>
                </x-ui.tooltip>
            </div>
        </x-ui.card>

        {{-- Tabs --}}
        <x-ui.card title="Tabs">
            <x-ui.tabs :tabs="['general' => 'General', 'security' => 'Security', 'notifications' => 'Notifications']" default="general">
                <x-slot:general>
                    <p class="text-sm text-zinc-500">General application settings are displayed here.</p>
                </x-slot:general>
                <x-slot:security>
                    <p class="text-sm text-zinc-500">Security settings, change password, 2FA.</p>
                </x-slot:security>
                <x-slot:notifications>
                    <p class="text-sm text-zinc-500">Manage email & push notification preferences.</p>
                </x-slot:notifications>
            </x-ui.tabs>
        </x-ui.card>

        {{-- Table --}}
        @php
        $demoUsers = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Admin', 'status' => 'active'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'User', 'status' => 'active'],
            ['name' => 'Robert Brown', 'email' => 'robert@example.com', 'role' => 'User', 'status' => 'inactive'],
        ];
        @endphp
        <x-ui.card title="Table" subtitle="Demo table data" :padding="false">
            <div class="p-6 pt-0">
                <x-ui.table>
                    <x-slot:head>
                        <x-ui.table.th>Name</x-ui.table.th>
                        <x-ui.table.th>Email</x-ui.table.th>
                        <x-ui.table.th>Role</x-ui.table.th>
                        <x-ui.table.th align="center">Status</x-ui.table.th>
                        <x-ui.table.th align="right">Actions</x-ui.table.th>
                    </x-slot:head>

                    @foreach ($demoUsers as $user)
                        <x-ui.table.row>
                            <x-ui.table.td>
                                <div class="flex items-center gap-2">
                                    <x-ui.avatar :name="$user['name']" size="xs" />
                                    {{ $user['name'] }}
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td>{{ $user['email'] }}</x-ui.table.td>
                            <x-ui.table.td>{{ $user['role'] }}</x-ui.table.td>
                            <x-ui.table.td align="center">
                                <x-ui.badge :color="$user['status'] === 'active' ? 'green' : 'zinc'" dot>
                                    {{ ucfirst($user['status']) }}
                                </x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td align="right">
                                <x-ui.dropdown align="right">
                                    <x-slot:trigger>
                                        <x-ui.button variant="ghost" size="sm" icon="ph-dots-three" />
                                    </x-slot:trigger>
                                    <x-ui.dropdown-item icon="ph-pencil">Edit</x-ui.dropdown-item>
                                    <x-ui.dropdown-item icon="ph-eye">View</x-ui.dropdown-item>
                                    <x-ui.dropdown-item icon="ph-trash" danger>Delete</x-ui.dropdown-item>
                                </x-ui.dropdown>
                            </x-ui.table.td>
                        </x-ui.table.row>
                    @endforeach
                </x-ui.table>
            </div>
        </x-ui.card>

        {{-- Modal --}}
        <x-ui.card title="Modal / Dialog">
            <x-ui.button @click="$dispatch('open-modal', { name: 'demo-modal' })">
                Open Modal
            </x-ui.button>

            <x-ui.modal name="demo-modal" title="Confirm Action" maxWidth="sm">
                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                    Are you sure you want to proceed with this action?
                </p>
                <x-slot:footer>
                    <x-ui.button variant="secondary" @click="$dispatch('close-modal')">Cancel</x-ui.button>
                    <x-ui.button variant="danger">Confirm</x-ui.button>
                </x-slot:footer>
            </x-ui.modal>
        </x-ui.card>

        {{-- Skeleton --}}
        <x-ui.card title="Skeleton Loading">
            <div class="space-y-6">
                <div>
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-2">Text lines</p>
                    <x-ui.skeleton type="text" :lines="3" />
                </div>
                <div class="flex items-center gap-3">
                    <x-ui.skeleton type="avatar" />
                    <x-ui.skeleton type="text" :lines="2" class="flex-1" />
                </div>
            </div>
        </x-ui.card>

        {{-- Empty State --}}
        <x-ui.card title="Empty State">
            <x-ui.empty-state icon="ph-file-text" title="No items yet" message="Create your first item to get started.">
                <x-slot:actions>
                    <x-ui.button variant="primary" icon="ph-plus" size="sm">Create New</x-ui.button>
                </x-slot:actions>
            </x-ui.empty-state>
        </x-ui.card>

        {{-- Theme switcher demo --}}
        <x-ui.card title="Theme Preview" subtitle="Add class to <html> to switch theme">
            <div class="flex flex-wrap gap-3">
                <button onclick="document.documentElement.className=''" class="px-3 py-2 rounded-lg border text-sm">Default (Indigo)</button>
                <button onclick="document.documentElement.className='theme-rose'" class="px-3 py-2 rounded-lg border text-sm">Rose</button>
                <button onclick="document.documentElement.className='theme-emerald'" class="px-3 py-2 rounded-lg border text-sm">Emerald</button>
                <button onclick="document.documentElement.className='theme-amber'" class="px-3 py-2 rounded-lg border text-sm">Amber</button>
                <button onclick="document.documentElement.className='theme-sky'" class="px-3 py-2 rounded-lg border text-sm">Sky</button>
            </div>
        </x-ui.card>

    </div>
</x-layouts.app>
