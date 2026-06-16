<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }} — Component Showcase</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 antialiased">
    <x-layouts.app>
        <div class="space-y-10 max-w-4xl">

            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Component Showcase</h1>
                <p class="mt-1 text-sm text-zinc-500">Preview semua komponen UI — gunakan untuk testing tema.</p>
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
                        <x-ui.checkbox label="Saya setuju dengan syarat & ketentuan" />
                        <x-ui.checkbox label="Kirim saya email promosi" description="Anda bisa berhenti berlangganan kapan saja" />
                    </div>

                    <div class="space-y-3">
                        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Radio Group</p>
                        <x-ui.radio
                            name="demo_radio"
                            :selected="'option-a'"
                            :options="[
                                ['value' => 'option-a', 'label' => 'Opsi A', 'description' => 'Deskripsi singkat opsi A'],
                                ['value' => 'option-b', 'label' => 'Opsi B'],
                            ]"
                        />
                    </div>

                    <div class="space-y-3 md:col-span-2">
                        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Toggle</p>
                        <x-ui.toggle label="Aktifkan notifikasi" description="Terima notifikasi via email" />
                        <x-ui.toggle label="Mode privat" size="lg" />
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
            @php
            $demoUsers = [
                ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'role' => 'Admin', 'status' => 'active'],
                ['name' => 'Siti Aminah', 'email' => 'siti@example.com', 'role' => 'User', 'status' => 'active'],
                ['name' => 'Rudi Hartono', 'email' => 'rudi@example.com', 'role' => 'User', 'status' => 'inactive'],
            ];
            @endphp
            <x-ui.card title="Table" subtitle="Demo data tabel" :padding="false">
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
                                            <x-ui.button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                        </x-slot:trigger>
                                        <x-ui.dropdown-item icon="pencil">Edit</x-ui.dropdown-item>
                                        <x-ui.dropdown-item icon="eye">View</x-ui.dropdown-item>
                                        <x-ui.dropdown-item icon="trash" danger>Delete</x-ui.dropdown-item>
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
                        Apakah Anda yakin ingin melanjutkan tindakan ini?
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
</body>
</html>