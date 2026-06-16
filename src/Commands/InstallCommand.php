<?php

namespace Ilhamsyabani\VoltStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    public $signature = 'volt-starter:install
                        {--auth : Include authentication scaffolding (login, register)}
                        {--roles : Include role & permission system}
                        {--showcase : Include component showcase page}
                        {--full : Install everything (auth + roles + showcase)}
                        {--folio : Setup Folio routing automatically}
                        {--force : Overwrite existing files}';

    public $description = 'Install Laravel Volt Starter Kit into your application';

    public function handle(): int
    {
        $this->info('🚀 Installing Laravel Volt Starter...');
        $this->newLine();

        // Pastikan Folio & Volt sudah terinstall
        if (! $this->checkDependencies()) {
            return self::FAILURE;
        }

        $this->publishLayouts();
        $this->publishComponents();
        $this->publishTheme();
        $this->publishDashboard();

        $withAuth     = $this->option('auth')     || $this->option('full');
        $withRoles    = $this->option('roles')    || $this->option('full');
        $withShowcase = $this->option('showcase') || $this->option('full');

        if ($withAuth)     $this->publishAuth();
        if ($withRoles)    $this->publishRoles();
        if ($withShowcase) $this->publishShowcase();

        $this->publishConfig();
        $this->publishSetup();
        $this->setupFolio();
        $this->setupVolt();
        $this->injectCssImport();
        $this->setupAppCss();

        $this->printNextSteps($withAuth, $withRoles);

        return self::SUCCESS;
    }

    protected function publishSetup(): void
    {
        $this->info('⚙️  Publishing framework setup files...');

        $this->callSilently('vendor:publish', [
            '--tag'   => 'volt-starter-setup',
            '--force' => $this->option('force'),
        ]);

        $this->line('  ✓ routes/folio.php published');
        $this->line('  ✓ AppServiceProvider.php updated');
        $this->line('  ✓ bootstrap/app.php updated');
    }

    // ─────────────────────────────────────────────────────────
    // Dependency check
    // ─────────────────────────────────────────────────────────

    protected function checkDependencies(): bool
    {
        $missing = [];

        if (! class_exists(\Laravel\Folio\FolioServiceProvider::class)) {
            $missing[] = 'laravel/folio';
        }
        if (! class_exists(\Livewire\Volt\VoltServiceProvider::class)) {
            $missing[] = 'livewire/volt';
        }
        if (! class_exists(\Livewire\LivewireServiceProvider::class)) {
            $missing[] = 'livewire/livewire';
        }

        if (! empty($missing)) {
            $this->error('Missing required packages. Please install them first:');
            $this->line('  composer require ' . implode(' ', $missing));
            $this->newLine();
            $this->line('Then run:');
            $this->line('  php artisan folio:install');
            $this->line('  php artisan volt:install');
            return false;
        }

        return true;
    }

    // ─────────────────────────────────────────────────────────
    // Publish stubs
    // ─────────────────────────────────────────────────────────

    protected function publishLayouts(): void
    {
        $this->info('📐 Publishing layouts...');
        $this->callSilently('vendor:publish', [
            '--tag'   => 'volt-starter-layouts',
            '--force' => $this->option('force'),
        ]);
        $this->line('  ✓ Layouts published');
    }

    protected function publishComponents(): void
    {
        $this->info('🧩 Publishing components...');
        foreach (['volt-starter-components', 'volt-starter-components-tier2'] as $tag) {
            $this->callSilently('vendor:publish', [
                '--tag'   => $tag,
                '--force' => $this->option('force'),
            ]);
        }
        $this->line('  ✓ 20+ components published to resources/views/components/ui/');
    }

    protected function publishTheme(): void
    {
        $this->info('🎨 Publishing theme CSS...');
        $this->callSilently('vendor:publish', [
            '--tag'   => 'volt-starter-theme',
            '--force' => $this->option('force'),
        ]);
        $this->line('  ✓ Theme CSS published');
    }

    protected function publishDashboard(): void
    {
        $this->info('📊 Publishing dashboard...');
        $this->callSilently('vendor:publish', [
            '--tag'   => 'volt-starter-dashboard',
            '--force' => $this->option('force'),
        ]);
        $this->line('  ✓ Dashboard page published');
    }

    protected function publishAuth(): void
    {
        $this->info('🔐 Publishing authentication pages...');
        $this->callSilently('vendor:publish', [
            '--tag'   => 'volt-starter-auth',
            '--force' => $this->option('force'),
        ]);
        $this->line('  ✓ Login & register pages published');
    }

    protected function publishRoles(): void
    {
        $this->info('👥 Publishing role system...');
        foreach (['volt-starter-roles', 'volt-starter-migrations'] as $tag) {
            $this->callSilently('vendor:publish', [
                '--tag'   => $tag,
                '--force' => $this->option('force'),
            ]);
        }
        $this->line('  ✓ Role system published');
    }

    protected function publishShowcase(): void
    {
        $this->info('🖼️  Publishing component showcase...');
        $this->callSilently('vendor:publish', [
            '--tag'   => 'volt-starter-showcase',
            '--force' => $this->option('force'),
        ]);
        $this->line('  ✓ Showcase available at /showcase');
    }

    protected function publishConfig(): void
    {
        $this->callSilently('vendor:publish', [
            '--tag'   => 'volt-starter-config',
            '--force' => $this->option('force'),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // Setup Folio
    // ─────────────────────────────────────────────────────────

    protected function setupFolio(): void
    {
        $this->info('📂 Configuring Folio...');

        // Jalankan folio:install jika belum
        $folioPath = config_path('folio.php');
        if (! File::exists($folioPath)) {
            $this->callSilently('folio:install');
        }

        // Tulis routes/folio.php
        $folioRoutesPath = base_path('routes/folio.php');

        $stub = <<<'PHP'
<?php

use Laravel\Folio\Folio;

Folio::path(resource_path('views/pages'))->middleware([
    '*'           => [],
    'dashboard'   => ['auth', 'verified'],
    'settings/*'  => ['auth', 'verified'],
    'showcase'    => ['auth', 'verified'],
    'respondent*' => ['auth', 'verified'],
]);
PHP;

        if (! File::exists($folioRoutesPath) || $this->option('force')) {
            File::put($folioRoutesPath, $stub);
            $this->line('  ✓ routes/folio.php created');
        } else {
            $this->line('  ~ routes/folio.php already exists, skipping');
        }

        // Pastikan Folio terdaftar di bootstrap/app.php
        $this->registerFolioInBootstrap();
    }

    protected function registerFolioInBootstrap(): void
    {
        $bootstrapPath = base_path('bootstrap/app.php');

        if (! File::exists($bootstrapPath)) {
            return;
        }

        $content = File::get($bootstrapPath);

        // Cek apakah Folio sudah terdaftar
        if (str_contains($content, 'Folio::route') || str_contains($content, 'folio.php')) {
            $this->line('  ~ Folio already registered in bootstrap/app.php');
            return;
        }

        // Inject Folio route registration ke withRouting()
        $search  = "web: __DIR__.'/../routes/web.php',";
        $replace = "web: __DIR__.'/../routes/web.php',\n            then: function () {\n                \Laravel\Folio\Folio::route(resource_path('views/pages'));\n            },";

        if (str_contains($content, $search)) {
            $content = str_replace($search, $replace, $content);
            File::put($bootstrapPath, $content);
            $this->line('  ✓ Folio registered in bootstrap/app.php');
        } else {
            // Fallback: tambahkan ke routes/web.php
            $this->registerFolioInWebRoutes();
        }
    }

    protected function registerFolioInWebRoutes(): void
    {
        $webRoutesPath = base_path('routes/web.php');

        if (! File::exists($webRoutesPath)) {
            return;
        }

        $content = File::get($webRoutesPath);

        if (str_contains($content, 'Folio::route') || str_contains($content, 'folio:')) {
            $this->line('  ~ Folio already registered in routes/web.php');
            return;
        }

        $inject = "\n\n// Laravel Folio — file-based page routing\n\\Laravel\\Folio\\Folio::route(resource_path('views/pages'));\n";
        File::append($webRoutesPath, $inject);
        $this->line('  ✓ Folio registered in routes/web.php');
    }

    // ─────────────────────────────────────────────────────────
    // Setup Volt
    // ─────────────────────────────────────────────────────────

    protected function setupVolt(): void
    {
        $this->info('⚡ Configuring Livewire Volt...');

        // Jalankan volt:install jika belum ada config
        if (! File::exists(config_path('livewire.php'))) {
            $this->callSilently('livewire:publish', ['--config' => true]);
        }

        // Daftarkan Volt paths di bootstrap/app.php atau AppServiceProvider
        $this->registerVoltPaths();
    }

    protected function registerVoltPaths(): void
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');

        if (! File::exists($providerPath)) {
            $this->line('  ~ AppServiceProvider not found, skipping Volt path registration');
            return;
        }

        $content = File::get($providerPath);

        if (str_contains($content, 'Volt::mount') || str_contains($content, 'volt::mount')) {
            $this->line('  ~ Volt paths already registered');
            return;
        }

        // Inject Volt::mount ke boot() method
        $bootMethod = 'public function boot(): void
    {';

        $voltMount = 'public function boot(): void
    {
        \Livewire\Volt\Volt::mount([
            resource_path(\'views/pages\'),
            resource_path(\'views/components\'),
        ]);';

        if (str_contains($content, $bootMethod)) {
            $content = str_replace($bootMethod, $voltMount, $content);
            File::put($providerPath, $content);
            $this->line('  ✓ Volt paths registered in AppServiceProvider');
        } else {
            $this->line('  ~ Could not auto-register Volt paths, add manually:');
            $this->line('    Volt::mount([resource_path(\'views/pages\')]);');
        }
    }

    // ─────────────────────────────────────────────────────────
    // Inject CSS
    // ─────────────────────────────────────────────────────────

    protected function injectCssImport(): void
    {
        $appCssPath = resource_path('css/app.css');

        if (! File::exists($appCssPath)) {
            return;
        }

        $content = File::get($appCssPath);

        if (str_contains($content, 'volt-starter.css')) {
            $this->line('  ~ CSS import already exists');
            return;
        }

        // Tambahkan import di bawah baris pertama
        $import  = "@import './volt-starter.css';\n";
        $content = $import . $content;
        File::put($appCssPath, $content);
        $this->line('  ✓ CSS import added to resources/css/app.css');
    }

    protected function setupAppCss(): void
    {
        // Pastikan tailwind.config.js atau vite.config.js scan views/components
        $tailwindPath = base_path('tailwind.config.js');

        if (! File::exists($tailwindPath)) {
            return;
        }

        $content = File::get($tailwindPath);

        // Cek apakah components sudah di-scan
        if (str_contains($content, 'views/components')) {
            return;
        }

        // Inject path ke content array
        $search  = "'./resources/views/**/*.blade.php'";
        $replace = "'./resources/views/**/*.blade.php',\n      './resources/views/components/**/*.blade.php'";

        if (str_contains($content, $search)) {
            $content = str_replace($search, $replace, $content);
            File::put($tailwindPath, $content);
            $this->line('  ✓ Tailwind content paths updated');
        }
    }

    // ─────────────────────────────────────────────────────────
    // Next steps
    // ─────────────────────────────────────────────────────────

    protected function printNextSteps(bool $withAuth, bool $withRoles): void
    {
        $this->newLine();
        $this->info('✅ Laravel Volt Starter installed successfully!');
        $this->newLine();

        $this->line('<options=bold>Next steps:</>');
        $this->line('  1. Run <comment>php artisan migrate</comment>');
        $this->line('  2. Run <comment>npm install && npm run dev</comment>');
        $this->line('  3. Run <comment>php artisan storage:link</comment>');
        $this->newLine();

        $this->line('<options=bold>Available routes:</>');
        $this->line('  /           → resources/views/pages/index.blade.php');
        $this->line('  /dashboard  → resources/views/pages/dashboard.blade.php');

        if ($withAuth) {
            $this->line('  /auth/login    → resources/views/pages/auth/login.blade.php');
            $this->line('  /auth/register → resources/views/pages/auth/register.blade.php');
            $this->line('  /settings/profile → resources/views/pages/settings/profile.blade.php');
        }

        $this->newLine();
        $this->line('<options=bold>Generator commands:</>');
        $this->line('  <comment>php artisan volt-starter:page users/index --auth</comment>');
        $this->line('  <comment>php artisan volt-starter:crud Post --fields=title:string,body:text</comment>');
        $this->newLine();
        $this->line('📚 Docs: <comment>https://github.com/ilhamsyabani/laravel-volt-starter</comment>');
    }
}
