<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }} — {{ $title ?? 'Dashboard' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Prevent dark mode flash --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="h-full bg-zinc-50 dark:bg-zinc-950 antialiased text-zinc-900 dark:text-zinc-100">

    <div class="flex h-full">
        <x-layouts.app.sidebar />

        <div class="flex-1 flex flex-col min-w-0">
            {{-- Mobile top bar --}}
            <header class="lg:hidden flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 px-4 py-3">
                <button
                    @click="$dispatch('toggle-sidebar')"
                    class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800"
                    aria-label="Toggle menu"
                >
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 5A.75.75 0 012.75 9h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 9.75zm0 5a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <span class="font-semibold text-sm">{{ config('app.name', 'Laravel') }}</span>
                <div class="w-9"></div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-ui.toast />

    @livewireScripts
</body>
</html>
