<?php

namespace Ilhamsyabani\VoltStarter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ilhamsyabani\VoltStarter\Commands\InstallCommand;
use Ilhamsyabani\VoltStarter\Commands\MakePageCommand;
use Ilhamsyabani\VoltStarter\Commands\MakeCrudCommand;
use Ilhamsyabani\VoltStarter\Commands\PresetCommand;

class VoltStarterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-volt-starter')
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        // ✅ Commands hanya diregistrasi saat console, dan PresetCommand ikut didaftarkan
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                MakePageCommand::class,
                MakeCrudCommand::class,
                PresetCommand::class,
            ]);
        }

        // ✅ FolioServiceProvider TIDAK di-register otomatis dari sini.
        // Folio routing didaftarkan oleh user sendiri setelah menjalankan:
        //   php artisan volt-starter:install --folio
        // yang akan mempublish routes/folio.php dan mendaftarkannya ke bootstrap/app.php.

        $stubsPath = __DIR__ . '/../stubs';

        // Layouts
        $this->publishes([
            $stubsPath . '/layouts/app.blade.php'         => resource_path('views/components/layouts/app.blade.php'),
            $stubsPath . '/layouts/app/sidebar.blade.php' => resource_path('views/components/layouts/app/sidebar.blade.php'),
            $stubsPath . '/layouts/auth.blade.php'        => resource_path('views/components/layouts/auth.blade.php'),
        ], 'volt-starter-layouts');

        // UI Components Tier 1
        $this->publishes([
            $stubsPath . '/components/ui/button.blade.php'   => resource_path('views/components/ui/button.blade.php'),
            $stubsPath . '/components/ui/input.blade.php'    => resource_path('views/components/ui/input.blade.php'),
            $stubsPath . '/components/ui/textarea.blade.php' => resource_path('views/components/ui/textarea.blade.php'),
            $stubsPath . '/components/ui/select.blade.php'   => resource_path('views/components/ui/select.blade.php'),
            $stubsPath . '/components/ui/checkbox.blade.php' => resource_path('views/components/ui/checkbox.blade.php'),
            $stubsPath . '/components/ui/radio.blade.php'    => resource_path('views/components/ui/radio.blade.php'),
            $stubsPath . '/components/ui/toggle.blade.php'   => resource_path('views/components/ui/toggle.blade.php'),
            $stubsPath . '/components/ui/badge.blade.php'    => resource_path('views/components/ui/badge.blade.php'),
            $stubsPath . '/components/ui/card.blade.php'     => resource_path('views/components/ui/card.blade.php'),
            $stubsPath . '/components/ui/alert.blade.php'    => resource_path('views/components/ui/alert.blade.php'),
            $stubsPath . '/components/ui/toast.blade.php'    => resource_path('views/components/ui/toast.blade.php'),
            $stubsPath . '/components/ui/spinner.blade.php'  => resource_path('views/components/ui/spinner.blade.php'),
        ], 'volt-starter-components');

        // UI Components Tier 2
        $this->publishes([
            $stubsPath . '/components/ui/modal.blade.php'         => resource_path('views/components/ui/modal.blade.php'),
            $stubsPath . '/components/ui/dropdown.blade.php'      => resource_path('views/components/ui/dropdown.blade.php'),
            $stubsPath . '/components/ui/dropdown-item.blade.php' => resource_path('views/components/ui/dropdown-item.blade.php'),
            $stubsPath . '/components/ui/table.blade.php'         => resource_path('views/components/ui/table.blade.php'),
            $stubsPath . '/components/ui/table/th.blade.php'      => resource_path('views/components/ui/table/th.blade.php'),
            $stubsPath . '/components/ui/table/td.blade.php'      => resource_path('views/components/ui/table/td.blade.php'),
            $stubsPath . '/components/ui/table/row.blade.php'     => resource_path('views/components/ui/table/row.blade.php'),
            $stubsPath . '/components/ui/table/empty.blade.php'   => resource_path('views/components/ui/table/empty.blade.php'),
            $stubsPath . '/components/ui/pagination.blade.php'    => resource_path('views/components/ui/pagination.blade.php'),
            $stubsPath . '/components/ui/tabs.blade.php'          => resource_path('views/components/ui/tabs.blade.php'),
            $stubsPath . '/components/ui/breadcrumb.blade.php'    => resource_path('views/components/ui/breadcrumb.blade.php'),
            $stubsPath . '/components/ui/avatar.blade.php'        => resource_path('views/components/ui/avatar.blade.php'),
            $stubsPath . '/components/ui/tooltip.blade.php'       => resource_path('views/components/ui/tooltip.blade.php'),
            $stubsPath . '/components/ui/empty-state.blade.php'   => resource_path('views/components/ui/empty-state.blade.php'),
            $stubsPath . '/components/ui/skeleton.blade.php'      => resource_path('views/components/ui/skeleton.blade.php'),
        ], 'volt-starter-components-tier2');

        // Theme CSS
        $this->publishes([
            $stubsPath . '/css/volt-starter.css' => resource_path('css/volt-starter.css'),
        ], 'volt-starter-theme');

        // Core pages
        $this->publishes([
            $stubsPath . '/pages/index.blade.php'     => resource_path('views/pages/index.blade.php'),
            $stubsPath . '/pages/dashboard.blade.php' => resource_path('views/pages/dashboard.blade.php'),
        ], 'volt-starter-dashboard');

        // Auth pages
        $this->publishes([
            $stubsPath . '/pages/auth/login.blade.php'    => resource_path('views/pages/auth/login.blade.php'),
            $stubsPath . '/pages/auth/register.blade.php' => resource_path('views/pages/auth/register.blade.php'),
        ], 'volt-starter-auth');

        // Settings + role system
        $this->publishes([
            $stubsPath . '/pages/settings/profile.blade.php' => resource_path('views/pages/settings/profile.blade.php'),
        ], 'volt-starter-roles');

        // Migration
        $this->publishes([
            $stubsPath . '/migrations/add_role_to_users_table.php' => database_path('migrations/' . date('Y_m_d_His') . '_add_role_to_users_table.php'),
        ], 'volt-starter-migrations');

        // Showcase
        $this->publishes([
            $stubsPath . '/pages/showcase.blade.php' => resource_path('views/pages/showcase.blade.php'),
        ], 'volt-starter-showcase');

        // Framework setup files (Folio + Volt + AppServiceProvider)
        $this->publishes([
            $stubsPath . '/routes/folio.php'           => base_path('routes/folio.php'),
            $stubsPath . '/app/AppServiceProvider.php' => app_path('Providers/AppServiceProvider.php'),
            $stubsPath . '/bootstrap/app.php'          => base_path('bootstrap/app.php'),
        ], 'volt-starter-setup');
    }
}