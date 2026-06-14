<?php

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use function Livewire\Volt\{state, mount};

middleware(['auth', 'verified']);

state([
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'category_id' => '',
    'tags' => [],
    'image' => '',
    'status' => 'draft',
    'published_at' => '',
]);

mount(function () {
    $this->categories = Category::all()->map(fn($c) => ['value' => $c->id, 'label' => $c->name]);
    $this->allTags = Tag::all()->map(fn($t) => ['value' => $t->id, 'label' => $t->name]);
});

$save = function () {
    $this->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:posts,slug',
        'content' => 'required',
        'category_id' => 'required|exists:categories,id',
    ]);

    $post = Post::create([
        'title' => $this->title,
        'slug' => $this->slug,
        'excerpt' => $this->excerpt,
        'content' => $this->content,
        'category_id' => $this->category_id,
        'author_id' => auth()->id(),
        'status' => $this->status,
        'published_at' => $this->status === 'published' ? now() : null,
    ]);

    $post->tags()->attach($this->tags);

    $this->dispatch('notify', type: 'success', message: 'Post created successfully!');
    $this->redirect('/blog', navigate: true);
};

$generateSlug = function () {
    $this->slug = \Illuminate\Support\Str::slug($this->title);
};
?>

<x-layouts.app>
    <div class="max-w-3xl space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Create New Post</h1>
            <p class="mt-1 text-sm text-zinc-500">Write and publish your blog post</p>
        </div>

        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'href' => '/dashboard'],
            ['label' => 'Blog', 'href' => '/blog'],
            ['label' => 'Create'],
        ]" />

        <form wire:submit="save" class="space-y-6">

            {{-- Basic Info --}}
            <x-ui.card title="Basic Information">
                <div class="space-y-4">
                    <x-ui.input wire:model="title" label="Title" placeholder="Enter post title" required />

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <x-ui.input wire:model="slug" label="Slug" placeholder="post-url-slug" required />
                        </div>
                        <div class="flex items-end pb-1">
                            <x-ui.button type="button" variant="secondary" size="sm" wire:click="generateSlug">
                                Generate
                            </x-ui.button>
                        </div>
                    </div>

                    <x-ui.textarea wire:model="excerpt" label="Excerpt" placeholder="Brief summary..." :maxlength="200" />

                    <x-ui.select wire:model="category_id" label="Category" :options="$categories ?? []" required />
                </div>
            </x-ui.card>

            {{-- Content --}}
            <x-ui.card title="Content">
                <x-ui.textarea
                    wire:model="content"
                    label="Content"
                    placeholder="Write your post content..."
                    :maxlength="10000"
                    rows="15"
                />
            </x-ui.card>

            {{-- Tags --}}
            <x-ui.card title="Tags">
                <div class="flex flex-wrap gap-2">
                    @foreach ($allTags ?? [] as $tag)
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <x-ui.checkbox wire:model="tags" :value="$tag['value']" />
                            <span class="text-sm">{{ $tag['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </x-ui.card>

            {{-- Status --}}
            <x-ui.card title="Publishing">
                <div class="flex items-center gap-4">
                    <x-ui.toggle wire:model="status" :value="'published'" label="Publish immediately" />
                </div>
            </x-ui.card>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <x-ui.button type="submit" variant="primary" icon="check">
                    Create Post
                </x-ui.button>
                <x-ui.button variant="secondary" href="/blog" wire:navigate>
                    Cancel
                </x-ui.button>
            </div>

        </form>
    </div>
</x-layouts.app>
