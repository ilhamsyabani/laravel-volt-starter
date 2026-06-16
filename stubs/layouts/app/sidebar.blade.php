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
                @svg('ph-x', 'w-5 h-5')
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            <a
                href="{{ route('dashboard') }}"
                wire:navigate
                class="flex items-center gap-3 rounded-[var(--vs-radius)] px-3 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'vs-bg-primary-light vs-text-primary' : 'text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
            >
                @svg('ph-house', 'w-4 h-4')
                Dashboard
            </a>

            @if(auth()->user()?->isAdmin())
                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-zinc-400 uppercase tracking-wide">Management</p>

                <a
                    href="/users"
                    wire:navigate
                    class="flex items-center gap-3 rounded-[var(--vs-radius)] px-3 py-2 text-sm font-medium transition {{ request()->is('users*') ? 'vs-bg-primary-light vs-text-primary' : 'text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
                >
                    @svg('ph-users', 'w-4 h-4')
                    Users
                </a>

                <a
                    href="/settings/profile"
                    wire:navigate
                    class="flex items-center gap-3 rounded-[var(--vs-radius)] px-3 py-2 text-sm font-medium transition {{ request()->is('settings*') ? 'vs-bg-primary-light vs-text-primary' : 'text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
                >
                    @svg('ph-gear', 'w-4 h-4')
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
                <template x-if="!dark">
                    @svg('ph-moon', 'w-4 h-4')
                </template>
                <template x-if="dark">
                    @svg('ph-sun', 'w-4 h-4')
                </template>
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
                    @svg('ph-sign-out', 'w-4 h-4')
                    Log out
                </button>
            </form>
        </div>
    </div>
</aside>
