<?php

use function Livewire\Volt\{state};

middleware(['auth', 'verified']);

state([
    'text_value'     => '',
    'textarea_value' => '',
    'select_value'   => '',
    'check1'         => true,
    'check2'         => false,
    'radio_value'    => 'option-a',
    'toggle1'        => true,
    'toggle2'        => false,
    'sortField'      => 'name',
    'sortDirection'  => 'asc',
]);

$demoUsers = [
    ['name' => 'Budi Santoso',   'email' => 'budi@example.com',   'role' => 'Admin',     'status' => 'active'],
    ['name' => 'Siti Aminah',    'email' => 'siti@example.com',   'role' => 'User',      'status' => 'active'],
    ['name' => 'Rudi Hartono',   'email' => 'rudi@example.com',   'role' => 'User',      'status' => 'inactive'],
];

$sortBy = function (string $field) {
    if ($this->sortField === $field) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }
};

$showError = false;

$triggerToast = function (string $type) {
    $messages = [
        'success' => 'Data berhasil disimpan!',
        'error'   => 'Terjadi kesalahan, coba lagi.',
        'warning' => 'Periksa kembali isian Anda.',
        'info'    => 'Ini adalah informasi penting.',
    ];

    $this->dispatch('notify', type: $type, message: $messages[$type]);
};

?>

<x-layouts.app>
    <div class="space-y-10 max-w-4xl">

        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Component Showcase</h1>
            <p class="mt-1 text-sm text-zinc-500">Preview semua komponen UI Tier 1 — gunakan untuk testing tema.</p>
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
                <x-ui.button size="sm" icon="plus">Small + Icon</x-ui.button>
                <x-ui.button size="md" icon="trash" variant="danger">Delete</x-ui.button>
                <x-ui.button size="lg" iconTrailing="arrow-right">Continue</x-ui.button>
                <x-ui.button loading>Loading...</x-ui.button>
                <x-ui.button disabled>Disabled</x-ui.button>
            </div>
        </x-ui.card>

        {{-- Form elements --}}
        <x-ui.card title="Form Elements" subtitle="Input, Textarea, Select dengan validasi">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input
                    wire:model="text_value"
                    label="Nama Lengkap"
                    placeholder="Masukkan nama"
                    icon="user"
                    required
                />
                <x-ui.input
                    label="Email (dengan error)"
                    name="email_demo"
                    error="Format email tidak valid"
                    value="email-salah"
                    icon="envelope"
                />
                <x-ui.select
                    wire:model="select_value"
                    label="Pilih Kategori"
                    :options="['public' => 'Public', 'user' => 'User', 'internal' => 'Internal']"
                />
                <x-ui.input
                    label="Search"
                    placeholder="Cari sesuatu..."
                    icon="magnifying-glass"
                    iconTrailing="x-mark"
                />
            </div>
            <div class="mt-4">
                <x-ui.textarea
                    wire:model="textarea_value"
                    label="Deskripsi"
                    placeholder="Tulis deskripsi..."
                    hint="Maksimal 200 karakter"
                    :maxlength="200"
                />
            </div>
        </x-ui.card>

        {{-- Checkbox, Radio, Toggle --}}
        <x-ui.card title="Checkbox, Radio & Toggle">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Checkbox</p>
                    <x-ui.checkbox wire:model="check1" label="Saya setuju dengan syarat & ketentuan" />
                    <x-ui.checkbox wire:model="check2" label="Kirim saya email promosi" description="Anda bisa berhenti berlangganan kapan saja" />
                </div>

                <div class="space-y-3">
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Radio Group</p>
                    <x-ui.radio
                        name="demo_radio"
                        :selected="$radio_value"
                        :options="[
                            ['value' => 'option-a', 'label' => 'Opsi A', 'description' => 'Deskripsi singkat opsi A'],
                            ['value' => 'option-b', 'label' => 'Opsi B'],
                        ]"
                    />
                </div>

                <div class="space-y-3 md:col-span-2">
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Toggle</p>
                    <x-ui.toggle wire:model="toggle1" label="Aktifkan notifikasi" description="Terima notifikasi via email" />
                    <x-ui.toggle wire:model="toggle2" label="Mode privat" size="lg" />
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
                <x-ui.alert type="info" title="Informasi">
                    Survei akan ditutup pada 30 Juni 2026.
                </x-ui.alert>
                <x-ui.alert type="success" title="Berhasil!">
                    Jawaban Anda telah disimpan.
                </x-ui.alert>
                <x-ui.alert type="warning" dismissible>
                    Beberapa pertanyaan belum terjawab.
                </x-ui.alert>
                <x-ui.alert type="error" title="Gagal">
                    Tidak dapat memuat data survei. Silakan coba lagi.
                </x-ui.alert>
            </div>
        </x-ui.card>

        {{-- Toast trigger --}}
        <x-ui.card title="Toast Notifications" subtitle="Klik untuk memicu toast (pastikan <x-ui.toast /> ada di layout)">
            <div class="flex flex-wrap gap-3">
                <x-ui.button variant="success" wire:click="triggerToast('success')">Success Toast</x-ui.button>
                <x-ui.button variant="danger" wire:click="triggerToast('error')">Error Toast</x-ui.button>
                <x-ui.button variant="warning" wire:click="triggerToast('warning')">Warning Toast</x-ui.button>
                <x-ui.button variant="secondary" wire:click="triggerToast('info')">Info Toast</x-ui.button>
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
                <x-ui.spinner label="Memuat data..." />
            </div>

            <div class="relative mt-4 h-24 rounded-[var(--vs-radius)] border border-dashed border-zinc-300 dark:border-zinc-600">
                <x-ui.spinner overlay label="Loading..." />
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
                <x-ui.avatar name="Budi Santoso" size="xs" />
                <x-ui.avatar name="Siti Aminah" size="sm" status="online" />
                <x-ui.avatar name="Rudi Hartono" size="md" status="busy" />
                <x-ui.avatar name="Andi Wijaya" size="lg" status="away" />
                <x-ui.avatar name="Eka Putri" size="xl" status="offline" />
            </div>
        </x-ui.card>

        {{-- Tooltip --}}
        <x-ui.card title="Tooltip" subtitle="Hover elemen di bawah">
            <div class="flex items-center gap-4">
                <x-ui.tooltip text="Edit data ini" position="top">
                    <x-ui.button variant="ghost" icon="pencil" size="sm" />
                </x-ui.tooltip>
                <x-ui.tooltip text="Hapus secara permanen" position="bottom">
                    <x-ui.button variant="ghost" icon="trash" size="sm" />
                </x-ui.tooltip>
                <x-ui.tooltip text="Lihat detail lengkap" position="right">
                    <x-ui.badge color="primary">Info</x-ui.badge>
                </x-ui.tooltip>
            </div>
        </x-ui.card>

        {{-- Tabs --}}
        <x-ui.card title="Tabs">
            <x-ui.tabs :tabs="['general' => 'General', 'security' => 'Security', 'notifications' => 'Notifications']" default="general">
                <x-slot:general>
                    <p class="text-sm text-zinc-500">Pengaturan umum aplikasi ditampilkan di sini.</p>
                </x-slot:general>
                <x-slot:security>
                    <p class="text-sm text-zinc-500">Pengaturan keamanan, ganti password, 2FA.</p>
                </x-slot:security>
                <x-slot:notifications>
                    <p class="text-sm text-zinc-500">Atur preferensi notifikasi email & push.</p>
                </x-slot:notifications>
            </x-ui.tabs>
        </x-ui.card>

        {{-- Table --}}
        <x-ui.card title="Table" subtitle="Dengan sortable header dan empty state" :padding="false">
            <div class="p-6 pt-0">
                <x-ui.table>
                    <x-slot:head>
                        <x-ui.table.th sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">Name</x-ui.table.th>
                        <x-ui.table.th>Email</x-ui.table.th>
                        <x-ui.table.th>Role</x-ui.table.th>
                        <x-ui.table.th align="center">Status</x-ui.table.th>
                        <x-ui.table.th align="right">Actions</x-ui.table.th>
                    </x-slot:head>

                    @forelse ($demoUsers as $user)
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
                                        <x-ui.button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                    </x-slot:trigger>
                                    <x-ui.dropdown-item icon="pencil">Edit</x-ui.dropdown-item>
                                    <x-ui.dropdown-item icon="eye">View</x-ui.dropdown-item>
                                    <x-ui.dropdown-item icon="trash" danger>Delete</x-ui.dropdown-item>
                                </x-ui.dropdown>
                            </x-ui.table.td>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty colspan="5" icon="users" title="No users found" message="Try adjusting your search filters." />
                    @endforelse
                </x-ui.table>
            </div>
        </x-ui.card>

        {{-- Modal --}}
        <x-ui.card title="Modal / Dialog">
            <div class="flex flex-wrap gap-3">
                <x-ui.button @click="$dispatch('open-modal', { name: 'demo-modal' })">
                    Open Modal
                </x-ui.button>
            </div>

            <x-ui.modal name="demo-modal" title="Confirm Action" maxWidth="sm">
                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                    Apakah Anda yakin ingin melanjutkan tindakan ini? Tindakan ini tidak dapat dibatalkan.
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
                <div>
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-2">Card</p>
                    <x-ui.skeleton type="card" />
                </div>
            </div>
        </x-ui.card>

        {{-- Empty State --}}
        <x-ui.card title="Empty State">
            <x-ui.empty-state icon="document-text" title="No surveys yet" message="Create your first survey to get started.">
                <x-slot:actions>
                    <x-ui.button variant="primary" icon="plus" size="sm">Create Survey</x-ui.button>
                </x-slot:actions>
            </x-ui.empty-state>
        </x-ui.card>

        {{-- Theme switcher demo --}}
        <x-ui.card title="Theme Preview" subtitle="Tambahkan class ke <html> untuk ganti tema">
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
