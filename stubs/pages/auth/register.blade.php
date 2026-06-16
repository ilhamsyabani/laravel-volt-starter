<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }} — Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 flex items-center justify-center p-4 text-zinc-900 dark:text-zinc-100">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-lg vs-bg-primary text-white font-bold text-xl mb-3">
                {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
            </div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Create account</h1>
            <p class="mt-1 text-sm text-zinc-500">Get started for free</p>
        </div>

        {{-- Card with Register Form Component --}}
        <x-ui.card>
            @livewire('auth.register-form')
        </x-ui.card>

        <p class="text-center text-sm text-zinc-500 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="vs-text-primary hover:underline font-medium">Sign in</a>
        </p>

    </div>

    @livewireScripts
</body>
</html>