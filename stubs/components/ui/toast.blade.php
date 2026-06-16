{{--
    Toast Container — pasang sekali di layout utama (app.blade.php)
    Trigger dari Volt: $this->dispatch('notify', type: 'success', message: 'Saved!')
    Bisa stack beberapa toast sekaligus.
--}}

<div
    x-data="{
        toasts: [],
        icons: {
            success: '{{ @svg("ph-check-circle", "w-3.5 h-3.5")->toHtml() }}',
            error:   '{{ @svg("ph-warning-circle", "w-3.5 h-3.5")->toHtml() }}',
            warning: '{{ @svg("ph-warning", "w-3.5 h-3.5")->toHtml() }}',
            info:    '{{ @svg("ph-info", "w-3.5 h-3.5")->toHtml() }}',
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
            class="pointer-events-auto flex items-start gap-3 rounded-[var(--vs-radius-lg)] bg-white dark:bg-zinc-900 shadow-[var(--vs-shadow-lg)] border border-zinc-200 dark:border-zinc-700 p-4"
            role="alert"
        >
            <span
                class="shrink-0 flex items-center justify-center w-6 h-6 rounded-full text-white"
                :class="colors[toast.type]"
                x-html="icons[toast.type]"
            ></span>
            <p class="flex-1 text-sm text-zinc-700 dark:text-zinc-200" x-text="toast.message"></p>
            <button @click="remove(toast.id)" class="shrink-0 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200" aria-label="Close">
                @svg('ph-x', 'w-4 h-4')
            </button>
        </div>
    </template>
</div>
