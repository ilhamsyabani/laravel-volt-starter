<?php

use App\Models\Post;
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
]);

mount(function (Post $post) {
    if ($post->author_id !== auth()->id() && !auth()->user()->isAdmin()) {
        abort(403);
    }

    $this->post = $post;
    $this->title = $post->title;
    $this->slug = $post->slug;
    $this->excerpt = $post->excerpt;
    $this->content = $post->content;
    $this->category_id = $post->category_id;
    $this->tags = $post->tags->pluck('id')->toArray();
    $this->status = $post->status;

    $this->categories = \App\Models\Category::all()->map(fn($c) => ['value' => $c->id, 'label' => $c->name]);
    $this->allTags = \App\Models\Tag::all()->map(fn($t) => ['value' => $t->id, 'label' => $t->name]);
});

$save = function (Post $post) {
    $this->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
        'content' => 'required',
        'category_id' => 'required|exists:categories,id',
    ]);

    $post->update([
        'title' => $this->title,
        'slug' => $this->slug,
        'excerpt' => $this->excerpt,
        'content' => $this->content,
        'category_id' => $this->category_id,
        'status' => $this->status,
    ]);

    $post->tags()->sync($this->tags);

    $this->dispatch('notify', type: 'success', message: 'Post updated successfully!');
    $this->redirect('/blog', navigate: true);
};

$delete = function (Post $post) {
    $post->delete();
    $this->dispatch('notify', type: 'success', message: 'Post deleted!');
    $this->redirect('/blog', navigate: true);
};

$generateSlug = function () {
    $this->slug = \Illuminate\Support\Str::slug($this->title);
};
?>

<x-layouts.app>
    <div class="max-w-3xl space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Edit Post</h1>
                <p class="mt-1 text-sm text-zinc-500">Update your blog post</p>
            </div>
            <x-ui.button variant="danger" size="sm" wire:click="$set('showDeleteModal', true)">
                Delete
            </x-ui.button>
        </div>

        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'href' => '/dashboard'],
            ['label' => 'Blog', 'href' => '/blog'],
            ['label' => $this->title],
        ]" />

        <form wire:submit="save($this->post)" class="space-y-6">

            {{-- Basic Info --}}
            <x-ui.card title="Basic Information">
                <div class="space-y-4">
                    <x-ui.input wire:model="title" label="Title" required />
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <x-ui.input wire:model="slug" label="Slug" required />
                        </div>
                        <div class="flex items-end pb-1">
                            <x-ui.button type="button" variant="secondary" size="sm" wire:click="generateSlug">
                                Regenerate
                            </x-ui.button>
                        </div>
                    </div>
                    <x-ui.textarea wire:model="excerpt" label="Excerpt" :maxlength="200" />
                    <x-ui.select wire:model="category_id" label="Category" :options="$categories ?? []" required />
                </div>
            </x-ui.card>

            {{-- Content --}}
            <x-ui.card title="Content">
                <x-ui.textarea wire:model="content" label="Content" :maxlength="10000" rows="15" />
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

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <x-ui.button type="submit" variant="primary" icon="check">
                    Update Post
                </x-ui.button>
                <x-ui.button variant="secondary" href="/blog" wire:navigate>
                    Cancel
                </x-ui.button>
            </div>

        </form>

        {{-- Delete Modal --}}
        <x-ui.modal name="showDeleteModal" title="Delete Post">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                Are you sure you want to delete this post? This action cannot be undone.
            </p>
            <x-slot:footer>
                <x-ui.button variant="secondary" wire:click="$set('showDeleteModal', false)">Cancel</x-ui.button>
                <x-ui.button variant="danger" wire:click="delete($this->post)">Delete</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>

    </div>
</x-layouts.app>
