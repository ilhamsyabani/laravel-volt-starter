@props([
    'icon'    => 'inbox',
    'title'   => 'No data found',
    'message' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center py-12 px-4']) }}>
    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-400 mb-3">
        @svg($icon, 'w-6 h-6')
    </div>
    <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $title }}</p>
    @if ($message)
        <p class="text-xs text-zinc-400 mt-1">{{ $message }}</p>
    @endif
    @isset($actions)
        <div class="mt-4">{{ $actions }}</div>
    @endisset
    {{ $slot }}
</div>
