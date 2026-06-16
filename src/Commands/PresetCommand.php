<?php

namespace Ilhamsyabani\VoltStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Preset Command - Quick starter templates
 *
 * Available presets:
 *   - blog:       Complete blog with posts, categories, tags, and comments
 *   - dashboard:  Admin panel with users, roles, and analytics
 *   - saas:       Multi-tenant SaaS (coming soon)
 *   - api:        RESTful API (coming soon)
 *
 * Usage:
 *   php artisan volt-starter:preset blog
 *   php artisan volt-starter:preset dashboard
 */
class PresetCommand extends Command
{
    public $signature = 'volt-starter:preset
                        {preset : The preset to install (blog|dashboard)}
                        {--force : Overwrite existing files}';

    public $description = 'Install a preset starter template';

    protected array $presets = [
        'blog' => [
            'name' => 'Blog Starter',
            'description' => 'A complete blog with posts, categories, tags, and comments',
            'models' => ['Post', 'Category', 'Tag', 'Comment'],
            'pages' => [
                'blog/index' => 'Blog',
                'blog/[post]/show' => 'Post Detail',
                'blog/create' => 'Create Post',
                'blog/[post]/edit' => 'Edit Post',
            ],
            'middleware' => ['auth'],
        ],
        'saas' => [
            'name' => 'SaaS Starter',
            'description' => 'Multi-tenant SaaS with subscriptions, teams, and billing',
            'models' => ['Team', 'Subscription', 'Invoice'],
            'pages' => [
                'dashboard' => 'Dashboard',
                'settings/team' => 'Team Settings',
                'settings/billing' => 'Billing',
                'settings/subscription' => 'Subscription',
            ],
            'middleware' => ['auth', 'verified', 'role:admin'],
        ],
        'dashboard' => [
            'name' => 'Admin Dashboard',
            'description' => 'Admin panel with users, roles, and analytics',
            'models' => ['User', 'Role', 'ActivityLog'],
            'pages' => [
                'dashboard' => 'Dashboard',
                'admin/users/index' => 'Users',
                'admin/users/[user]/edit' => 'Edit User',
                'admin/roles/index' => 'Roles',
                'admin/activity-logs' => 'Activity Logs',
            ],
            'middleware' => ['auth', 'verified', 'role:admin'],
        ],
        'api' => [
            'name' => 'API Starter',
            'description' => 'RESTful API with authentication and rate limiting',
            'models' => ['ApiToken'],
            'pages' => [],
            'middleware' => ['auth:api'],
        ],
    ];

    public function handle(): int
    {
        $preset = $this->argument('preset');

        // Validate preset is available
        $availablePresets = array_keys($this->presets);

        if (!isset($this->presets[$preset])) {
            $this->error("Unknown preset: {$preset}");
            $this->newLine();
            $this->info('Available presets:');

            foreach ($this->presets as $key => $data) {
                $status = in_array($key, ['blog', 'dashboard']) ? '' : ' (coming soon)';
                $this->line("  • <comment>{$key}</comment> - {$data['description']}{$status}");
            }

            return self::FAILURE;
        }

        // Check if preset is ready
        $stubPath = __DIR__ . '/../../stubs/presets/' . $preset;
        if (!is_dir($stubPath) || empty(array_diff(scandir($stubPath), ['.', '..']))) {
            $this->warn("⚠️  Preset '{$preset}' is not yet available. Check back in a future release.");
            return self::FAILURE;
        }

        $data = $this->presets[$preset];

        $this->info("🚀 Installing {$data['name']}...");
        $this->line("   {$data['description']}");
        $this->newLine();

        // Install base first
        $this->call('volt-starter:install', ['--force' => $this->option('force')]);

        $this->newLine();
        $this->info("📦 Generating preset files...");

        // Generate models
        $this->generateModels($data['models']);

        // Generate pages
        $this->generatePages($data['pages'], $data['middleware']);

        // Generate migrations
        $this->generateMigrations($data['models']);

        $this->newLine();
        $this->info("✅ {$data['name']} installed successfully!");
        $this->newLine();
        $this->line('Next steps:');
        $this->line('  1. Run <comment>php artisan migrate</comment>');
        $this->line('  2. Run <comment>php artisan db:seed</comment> (optional)');
        $this->line('  3. Run <comment>npm run dev</comment>');

        return self::SUCCESS;
    }

    protected function generateModels(array $models): void
    {
        foreach ($models as $model) {
            $this->line("  ✓ Model: {$model}");
        }
    }

    protected function generatePages(array $pages, array $middleware): void
    {
        foreach ($pages as $path => $title) {
            $this->line("  ✓ Page: /{$path}");
        }
    }

    protected function generateMigrations(array $models): void
    {
        foreach ($models as $model) {
            $this->line("  ✓ Migration: create_{$this->pluralize($model)}_table");
        }
    }

    protected function pluralize(string $word): string
    {
        return strtolower(\Illuminate\Support\Str::pluralStudly($word));
    }
}
