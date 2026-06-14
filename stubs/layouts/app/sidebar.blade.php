<aside
    x-data="{ open: false, dark: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
    x-init="
        $watch('dark', val => {
            document.documentElement.classList.toggle('dark', val);
            localStorage.setItem('theme', val ? 'dark' : 'light');
        });
    "
    @toggle-sidebar.window="open = !open"
    class="shrink-0"
>
    {{-- Mobile overlay --}}
    <div
        x-show="open"
        x-transition.opacity
        @click="open = false"
        class="fixed inset-0 z-30 bg-black/30 lg:hidden"
        style="display: none;"
        aria-hidden="true"
    ></div>

    {{-- Sidebar panel --}}
    <div
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:sticky top-0 left-0 z-40 flex h-full w-64 flex-col border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 transition-transform duration-200 lg:translate-x-0"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-2 px-4 py-4 border-b border-zinc-100 dark:border-zinc-800">
            <div class="flex h-8 w-8 items-center justify-center rounded-[var(--vs-radius)] vs-bg-primary text-white font-bold text-sm">
                {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
            </div>
            <span class="font-semibold text-zinc-900 dark:text-white text-sm">
                {{ config('app.name', 'Laravel') }}
            </span>
            <button @click="open = false" class="ml-auto lg:hidden p-1 text-zinc-400 hover:text-zinc-600" aria-label="Close menu">
                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            <a
                href="{{ route('dashboard') }}"
                wire:navigate
                class="flex items-center gap-3 rounded-[var(--vs-radius)] px-3 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'vs-bg-primary-light vs-text-primary' : 'text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
            >
                @svg('home', 'w-4 h-4')
                Dashboard
            </a>

            @if(auth()->user()?->isAdmin())
                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-zinc-400 uppercase tracking-wide">Management</p>

                <a
                    href="/users"
                    wire:navigate
                    class="flex items-center gap-3 rounded-[var(--vs-radius)] px-3 py-2 text-sm font-medium transition {{ request()->is('users*') ? 'vs-bg-primary-light vs-text-primary' : 'text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
                >
                    @svg('users', 'w-4 h-4')
                    Users
                </a>

                <a
                    href="/settings/profile"
                    wire:navigate
                    class="flex items-center gap-3 rounded-[var(--vs-radius)] px-3 py-2 text-sm font-medium transition {{ request()->is('settings*') ? 'vs-bg-primary-light vs-text-primary' : 'text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
                >
                    @svg('cog-6-tooth', 'w-4 h-4')
                    Settings
                </a>
            @endif

        </nav>

        {{-- Footer: dark mode + user --}}
        <div class="border-t border-zinc-100 dark:border-zinc-800 p-3 space-y-1">

            <button
                @click="dark = !dark"
                class="flex w-full items-center gap-3 rounded-[var(--vs-radius)] px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition"
            >
                <svg x-show="!dark" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                <svg x-show="dark" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" style="display:none"><path d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM10 7a3 3 0 100 6 3 3 0 000-6zM15.657 5.404a.75.75 0 10-1.06-1.06l-1.061 1.06a.75.75 0 001.06 1.06l1.06-1.06zM6.464 14.596a.75.75 0 10-1.06-1.06l-1.06 1.06a.75.75 0 101.06 1.06l1.06-1.06zM18 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0118 10zM5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 015 10zM14.596 14.596a.75.75 0 001.06-1.06l-1.06-1.06a.75.75 0 00-1.06 1.06l1.06 1.06zM5.404 5.404a.75.75 0 001.06-1.06l-1.06-1.06a.75.75 0 10-1.06 1.06l1.06 1.06z"/></svg>
                <span x-text="dark ? 'Light Mode' : 'Dark Mode'"></span>
            </button>

            <div class="flex items-center gap-3 px-3 py-2">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full vs-bg-primary-light vs-text-primary font-semibold text-xs">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'G', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-zinc-900 dark:text-white truncate">{{ auth()->user()?->name ?? 'Guest' }}</p>
                    <p class="text-[11px] text-zinc-400 truncate">{{ auth()->user()?->email }}</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-[var(--vs-radius)] px-3 py-2 text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                    @svg('arrow-right-on-rectangle', 'w-4 h-4')
                    Log out
                </button>
            </form>
        </div>
    </div>
</aside>
