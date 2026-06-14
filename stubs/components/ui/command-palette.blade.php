{{--
    Command Palette - Quick action finder (Cmd+K style)

    Usage:
    1. Add button to trigger:
       <x-ui.button @click="$dispatch('open-command-palette')">Search</x-ui.button>

    2. The component auto-registers via Livewire events
--}}
<div
    x-data="{
        open: false,
        query: '',
        selectedIndex: 0,
        commands: [
            { id: 'dashboard', label: 'Go to Dashboard', icon: 'home', href: '/dashboard' },
            { id: 'users', label: 'Manage Users', icon: 'users', href: '/admin/users' },
            { id: 'posts', label: 'Manage Posts', icon: 'document-text', href: '/blog' },
            { id: 'profile', label: 'Edit Profile', icon: 'user', href: '/settings/profile' },
            { id: 'settings', label: 'Settings', icon: 'cog-6-tooth', href: '/settings' },
            { id: 'logout', label: 'Log out', icon: 'arrow-right-on-rectangle', action: 'logout' },
        ],
        filteredCommands() {
            if (!this.query) return this.commands;
            const q = this.query.toLowerCase();
            return this.commands.filter(c => c.label.toLowerCase().includes(q));
        },
        handleKeydown(e) {
            if (e.key === 'k' && (e.metaKey || e.ctrlKey)) {
                e.preventDefault();
                this.open = true;
            }
            if (e.key === 'Escape') {
                this.open = false;
            }
            if (!this.open) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.selectedIndex = Math.min(this.selectedIndex + 1, this.filteredCommands().length - 1);
            }
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.selectedIndex = Math.max(this.selectedIndex - 1, 0);
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                this.executeCommand(this.filteredCommands()[this.selectedIndex]);
            }
        },
        executeCommand(cmd) {
            if (!cmd) return;
            this.open = false;
            this.query = '';
            this.selectedIndex = 0;

            if (cmd.action === 'logout') {
                document.querySelector('form[action*=\'logout\']')?.submit();
                return;
            }

            if (cmd.href) {
                window.location.href = cmd.href;
            }
        }
    }"
    x-init="
        window.addEventListener('keydown', handleKeydown);
        $watch('open', val => {
            if (val) {
                setTimeout(() => \$refs.searchInput?.focus(), 100);
            }
        });
    "
    x-on:open-command-palette.window="open = true"
    x-on:close-command-palette.window="open = false"
    x-show="open"
    style="display: none;"
    class="fixed inset-0 z-[200] overflow-y-auto"
    role="dialog"
    aria-modal="true"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
        aria-hidden="true"
    ></div>

    {{-- Palette --}}
    <div class="flex min-h-full items-start justify-center p-4 pt-[15vh]">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="w-full max-w-lg overflow-hidden rounded-xl bg-white dark:bg-zinc-900 shadow-2xl border border-zinc-200 dark:border-zinc-700"
        >
            {{-- Search Input --}}
            <div class="flex items-center gap-3 border-b border-zinc-200 dark:border-zinc-700 px-4 py-3">
                <svg class="w-5 h-5 text-zinc-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                </svg>
                <input
                    x-ref="searchInput"
                    x-model="query"
                    type="text"
                    placeholder="Type a command or search..."
                    class="flex-1 bg-transparent text-sm text-zinc-900 dark:text-white placeholder-zinc-400 outline-none"
                >
                <kbd class="hidden sm:inline-flex items-center gap-1 rounded border border-zinc-300 dark:border-zinc-600 px-1.5 py-0.5 text-[10px] font-medium text-zinc-500">
                    ESC
                </kbd>
            </div>

            {{-- Results --}}
            <div class="max-h-80 overflow-y-auto py-2">
                <template x-for="(cmd, index) in filteredCommands()" :key="cmd.id">
                    <button
                        @click="executeCommand(cmd)"
                        :class="selectedIndex === index ? 'bg-zinc-100 dark:bg-zinc-800' : ''"
                        class="flex w-full items-center gap-3 px-4 py-2.5 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                            @svg(cmd.icon, 'w-4 h-4 text-zinc-500')
                        </span>
                        <span class="flex-1 text-sm text-zinc-700 dark:text-zinc-200" x-text="cmd.label"></span>
                        <span x-show="selectedIndex === index" class="text-xs text-zinc-400">Enter ↵</span>
                    </button>
                </template>

                <div x-show="filteredCommands().length === 0" class="px-4 py-8 text-center">
                    <p class="text-sm text-zinc-500">No results found.</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between border-t border-zinc-200 dark:border-zinc-700 px-4 py-2 bg-zinc-50 dark:bg-zinc-800/50">
                <div class="flex items-center gap-4 text-[10px] text-zinc-400">
                    <span class="flex items-center gap-1">
                        <kbd class="rounded border border-zinc-300 dark:border-zinc-600 px-1">↑↓</kbd>
                        Navigate
                    </span>
                    <span class="flex items-center gap-1">
                        <kbd class="rounded border border-zinc-300 dark:border-zinc-600 px-1">↵</kbd>
                        Select
                    </span>
                    <span class="flex items-center gap-1">
                        <kbd class="rounded border border-zinc-300 dark:border-zinc-600 px-1">esc</kbd>
                        Close
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>