<?php

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use function Livewire\Volt\{state, computed};
use Illuminate\Support\Facades\Auth;

middleware(['auth', 'verified']);

state(['search' => '']);

$posts = computed(function () {
    return Post::with(['author', 'category', 'tags'])
        ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
        ->latest()
        ->paginate(10);
});

?>

<x-layouts.app>
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Blog</h1>
                <p class="mt-1 text-sm text-zinc-500">Manage your blog posts</p>
            </div>
            <x-ui.button icon="plus" href="/blog/create">
                New Post
            </x-ui.button>
        </div>

        {{-- Search --}}
        <x-ui.input
            wire:model.live.debounce.300ms="search"
            placeholder="Search posts..."
            icon="magnifying-glass"
        />

        {{-- Posts Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($this->posts as $post)
                <x-ui.card :padding="false">
                    <div class="aspect-video bg-zinc-100 dark:bg-zinc-800 rounded-t-lg">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover rounded-t-lg">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-zinc-400">
                                @svg('photo', 'w-12 h-12')
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            @if ($post->category)
                                <x-ui.badge size="sm">{{ $post->category->name }}</x-ui.badge>
                            @endif
                            <span class="text-xs text-zinc-400">{{ $post->created_at->format('M j, Y') }}</span>
                        </div>
                        <h3 class="font-semibold text-zinc-900 dark:text-white hover:vs-text-primary">
                            <a href="/blog/{{ $post->id }}">{{ $post->title }}</a>
                        </h3>
                        <p class="mt-1 text-sm text-zinc-500 line-clamp-2">{{ $post->excerpt ?? Str::limit($post->content, 100) }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-ui.avatar :name="$post->author->name ?? 'U'" size="xs" />
                                <span class="text-xs text-zinc-500">{{ $post->author->name ?? 'Unknown' }}</span>
                            </div>
                            <x-ui.button size="sm" variant="ghost" href="/blog/{{ $post->id }}/edit" wire:navigate icon="pencil">
                                Edit
                            </x-ui.button>
                        </div>
                    </div>
                </x-ui.card>
            @empty
                <div class="col-span-full">
                    <x-ui.empty-state icon="document-text" title="No posts yet" subtitle="Create your first blog post to get started.">
                        <x-slot:actions>
                            <x-ui.button href="/blog/create">Create Post</x-ui.button>
                        </x-slot:actions>
                    </x-ui.empty-state>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($this->posts->hasPages())
            <div>
                {{ $this->posts->links() }}
            </div>
        @endif

    </div>
</x-layouts.app>
