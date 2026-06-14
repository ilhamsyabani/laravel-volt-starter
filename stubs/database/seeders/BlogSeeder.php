<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Tutorial', 'slug' => 'tutorial'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create tags
        $tags = [
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'Livewire', 'slug' => 'livewire'],
            ['name' => 'Vue', 'slug' => 'vue'],
            ['name' => 'React', 'slug' => 'react'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        // Create sample posts
        $user = User::first();

        $posts = [
            [
                'title' => 'Getting Started with Laravel Livewire Volt',
                'slug' => 'getting-started-laravel-livewire-volt',
                'excerpt' => 'Learn how to build reactive components with Laravel Livewire Volt.',
                'content' => 'Livewire Volt is a new way to build reactive components in Laravel...',
                'status' => 'published',
            ],
            [
                'title' => 'Building Modern UIs with Tailwind CSS',
                'slug' => 'building-modern-uis-tailwind-css',
                'excerpt' => 'Tailwind CSS makes building modern UIs faster than ever.',
                'content' => 'Tailwind CSS is a utility-first CSS framework...',
                'status' => 'published',
            ],
        ];

        foreach ($posts as $post) {
            $createdPost = Post::create([
                ...$post,
                'category_id' => Category::first()->id,
                'author_id' => $user->id,
                'published_at' => now(),
            ]);

            $createdPost->tags()->attach([1, 2, 3]);
        }

        $this->command->info('✅ Blog seeders created!');
    }
}
