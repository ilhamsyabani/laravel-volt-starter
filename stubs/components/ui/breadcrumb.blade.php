{{--
    Usage:
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Surveys', 'href' => '/surveys'],
        ['label' => 'Edit'], // last item, no href = current page
    ]" />
--}}
@props(['items' => []])

<nav class="flex items-center text-sm" aria-label="Breadcrumb">
    <ol class="flex items-center gap-1.5 flex-wrap">
        @foreach ($items as $item)
            <li class="flex items-center gap-1.5">
                @if (!$loop->first)
                    <svg class="w-3.5 h-3.5 text-zinc-300 dark:text-zinc-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/>
                    </svg>
                @endif

                @if (!empty($item['href']) && !$loop->last)
                    <a href="{{ $item['href'] }}" wire:navigate class="text-zinc-500 hover:vs-text-primary transition">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="font-medium text-zinc-900 dark:text-white" @if($loop->last) aria-current="page" @endif>
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>