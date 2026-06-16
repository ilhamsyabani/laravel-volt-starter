<?php

use function Livewire\Volt\{state};

// Redirect ke dashboard jika sudah login
if (auth()->check()) {
    return redirect()->route('dashboard');
}

?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased">

    {{-- Navbar --}}
    <header class="border-b border-zinc-100 dark:border-zinc-800">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg vs-bg-primary flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
                </div>
                <span class="font-semibold text-zinc-900 dark:text-white">{{ config('app.name') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="/auth/login" class="text-sm text-zinc-600 dark:text-zinc-300 hover:vs-text-primary transition">
                    Log in
                </a>
                <a href="/auth/register" class="text-sm px-4 py-2 rounded-lg vs-bg-primary text-white hover:opacity-90 transition">
                    Get started
                </a>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <main class="max-w-5xl mx-auto px-4 py-20 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 text-xs font-medium text-zinc-600 dark:text-zinc-300 mb-6">
            ⚡ Powered by Livewire Volt + Laravel Folio
        </div>

        <h1 class="text-4xl sm:text-5xl font-bold text-zinc-900 dark:text-white leading-tight mb-4">
            Build faster with<br>
            <span class="vs-text-primary">Laravel Volt Starter</span>
        </h1>

        <p class="text-lg text-zinc-500 dark:text-zinc-400 max-w-xl mx-auto mb-8">
            A modern starter kit with 20+ UI components, theming system, auth scaffolding,
            and CRUD generators — all MIT licensed.
        </p>

        <div class="flex items-center justify-center gap-3 flex-wrap">
            <a href="/auth/register" class="px-6 py-3 rounded-xl vs-bg-primary text-white font-medium hover:opacity-90 transition shadow-sm">
                Get Started Free
            </a>
            <a href="https://github.com/ilhamsyabani/laravel-volt-starter" target="_blank"
               class="px-6 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                View on GitHub
            </a>
        </div>
    </main>

    {{-- Features --}}
    <section class="max-w-5xl mx-auto px-4 pb-20">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach ([
                ['⚡', 'Livewire Volt', 'Single-file reactive components — no controllers needed'],
                ['🎨', '20+ Components', 'Button, Modal, Table, Tabs, Toast, Avatar and more — fully themed'],
                ['🌈', 'Theme System', 'Switch color schemes with one CSS class — indigo, rose, emerald, sky'],
                ['📂', 'Folio Routing', 'File-based routing — create a file, get a URL automatically'],
                ['🛠️', 'CRUD Generator', 'php artisan volt-starter:crud Post — done in seconds'],
                ['🔐', 'Auth Ready', 'Login, register, profile — all included out of the box'],
            ] as [$icon, $title, $desc])
                <div class="p-5 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                    <div class="text-2xl mb-2">{{ $icon }}</div>
                    <h3 class="font-semibold text-zinc-900 dark:text-white text-sm mb-1">{{ $title }}</h3>
                    <p class="text-xs text-zinc-500">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </section>

    @livewireScripts
</body>
</html>
