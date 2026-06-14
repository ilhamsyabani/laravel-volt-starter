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
]);

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
