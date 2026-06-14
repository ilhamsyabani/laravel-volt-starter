<?php

namespace Ilhamsyabani\VoltStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    public $signature = 'volt-starter:install
                        {--auth : Include full authentication scaffolding}
                        {--roles : Include role & permission system}
                        {--showcase : Include component showcase/demo page}
                        {--folio : Include Folio route configuration}
                        {--full : Install everything (auth + roles + showcase + folio)}
                        {--force : Overwrite existing files}';

    public $description = 'Install Laravel Volt Starter Kit into your application';

    public function handle(): int
    {
        $this->info('🚀 Installing Laravel Volt Starter...');
        $this->newLine();

        $this->publishLayouts();
        $this->publishComponents();
        $this->publishTheme();
        $this->publishDashboard();

        if ($this->option('auth') || $this->option('full')) {
            $this->publishAuth();
        }

        if ($this->option('roles') || $this->option('full')) {
            $this->publishRoles();
        }

        if ($this->option('showcase') || $this->option('full')) {
            $this->publishShowcase();
        }

        if ($this->option('folio') || $this->option('full')) {
            $this->publishFolio();
        }

        $this->publishMiddleware();
        $this->publishConfig();
        $this->registerMiddleware();
        $this->printNextSteps();

        return self::SUCCESS;
    }

    protected function publishLayouts(): void
    {
        $this->info('📐 Publishing layouts...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-layouts',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ Layouts published');
    }

    protected function publishComponents(): void
    {
        $this->info('🧩 Publishing components...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-components',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ UI components published');
        $this->line('    → 24+ components available');
    }

    protected function publishTheme(): void
    {
        $this->info('🎨 Publishing theme CSS...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-theme',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ Theme published to resources/css/volt-starter.css');
        $this->line('    → Import it in your resources/css/app.css:');
        $this->line('      <comment>@import \'./volt-starter.css\';</comment>');
    }

    protected function publishShowcase(): void
    {
        $this->info('🖼️  Publishing component showcase page...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-showcase',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ Showcase available at /showcase');
    }

    protected function publishDashboard(): void
    {
        $this->info('📊 Publishing dashboard page...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-dashboard',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ Dashboard published');
    }

    protected function publishAuth(): void
    {
        $this->info('🔐 Publishing authentication scaffolding...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-auth',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ Auth pages published');
    }

    protected function publishRoles(): void
    {
        $this->info('👥 Publishing role & permission system...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-roles',
            '--force' => $this->option('force'),
        ]);

        // Publish migration for roles column
        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-migrations',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ Roles system published');
    }

    protected function publishFolio(): void
    {
        $this->info('📂 Publishing Folio route configuration...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-folio',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ Folio routes published to routes/folio.php');
        $this->line('    → Add to bootstrap/app.php if needed');
    }

    protected function publishMiddleware(): void
    {
        $this->info('🔒 Publishing middleware...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-middleware',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ Middleware published');
    }

    protected function publishConfig(): void
    {
        $this->callSilently('vendor:publish', [
            '--tag' => 'volt-starter-config',
            '--force' => $this->option('force'),
        ]);
    }

    protected function registerMiddleware(): void
    {
        $kernelPath = app_path('Http/Kernel.php');

        if (!file_exists($kernelPath)) {
            return;
        }

        $content = file_get_contents($kernelPath);

        // Check if role middleware is already registered
        if (str_contains($content, "'role' =>")) {
            $this->line('  ⚠️  Role middleware already registered');
            return;
        }

        // Add role middleware alias
        $old = "'verified' => \\App\\Http\\Middleware\\EnsureEmailIsVerified::class,";
        $new = "'verified' => \\App\\Http\\Middleware\\EnsureEmailIsVerified::class,\n        'role' => \\App\\Http\\Middleware\\EnsureUserHasRole::class,";

        if (str_contains($content, $old) && !str_contains($content, "'role' =>")) {
            file_put_contents($kernelPath, str_replace($old, $new, $content));
            $this->line('  ✓ Role middleware registered in Kernel.php');
        }
    }

    protected function printNextSteps(): void
    {
        $this->newLine();
        $this->info('✅ Laravel Volt Starter installed successfully!');
        $this->newLine();
        $this->line('Next steps:');
        $this->line('  1. Run <comment>php artisan migrate</comment>');
        $this->line('  2. Run <comment>npm install && npm run dev</comment>');
        $this->line('  3. Run <comment>php artisan storage:link</comment>');
        $this->newLine();
        $this->line('Generator commands:');
        $this->line('  • <comment>php artisan volt-starter:page {Name}</comment> — Generate a Volt page');
        $this->line('  • <comment>php artisan volt-starter:crud {Model}</comment> — Generate full CRUD pages');
        $this->line('  • <comment>php artisan volt-starter:crud Post --fields=title:string,body:text --with-migration</comment>');
        $this->newLine();
        $this->line('📚 Documentation: <comment>https://github.com/ilhamsyabani/laravel-volt-starter</comment>');
    }
}