{{--
    Toast Container — pasang sekali di layout utama (app.blade.php)
    Trigger dari Volt: $this->dispatch('notify', type: 'success', message: 'Saved!')
    Bisa stack beberapa toast sekaligus.
--}}

<div
    x-data="{
        toasts: [],
        icons: {
            success: '<path fill-rule=\'evenodd\' d=\'M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z\' clip-rule=\'evenodd\'/>',
            error:   '<path fill-rule=\'evenodd\' d=\'M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 10-1.06-1.06L10 8.94 8.28 7.22z\' clip-rule=\'evenodd\'/>',
            warning: '<path fill-rule=\'evenodd\' d=\'M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z\' clip-rule=\'evenodd\'/>',
            info:    '<path fill-rule=\'evenodd\' d=\'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z\' clip-rule=\'evenodd\'/>',
        },
        colors: {
            success: 'bg-emerald-600',
            error:   'bg-red-600',
            warning: 'bg-amber-500',
            info:    'bg-blue-600',
        },
        add(type, message) {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, type: type || 'info', message });
            setTimeout(() => this.remove(id), 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    x-init="
        Livewire.on('notify', (e) => add(e.type ?? e.detail?.type, e.message ?? e.detail?.message));
    "
    class="fixed bottom-5 right-5 z-[100] flex flex-col gap-2 w-full max-w-sm pointer-events-none"
    aria-live="polite"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-x-4"
            class="pointer-events-auto flex items-start gap-3 rounded-[var(--vs-radius-lg)] bg-white dark:bg-zinc-800 shadow-[var(--vs-shadow-lg)] border border-zinc-200 dark:border-zinc-700 p-4"
            role="alert"
        >
            <span
                class="shrink-0 flex items-center justify-center w-6 h-6 rounded-full text-white"
                :class="colors[toast.type]"
            >
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" x-html="icons[toast.type]"></svg>
            </span>
            <p class="flex-1 text-sm text-zinc-700 dark:text-zinc-200" x-text="toast.message"></p>
            <button @click="remove(toast.id)" class="shrink-0 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200" aria-label="Close">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                </svg>
            </button>
        </div>
    </template>
</div>
