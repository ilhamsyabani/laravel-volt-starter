<?php

namespace Ilhamsyabani\VoltStarter;

use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;
use Livewire\Volt\Volt;
use Ilhamsyabani\VoltStarter\Commands\InstallCommand;
use Ilhamsyabani\VoltStarter\Commands\MakePageCommand;
use Ilhamsyabani\VoltStarter\Commands\MakeCrudCommand;
use Ilhamsyabani\VoltStarter\Commands\PresetCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Main Service Provider for Laravel Volt Starter
 *
 * This provider:
 * - Registers all commands
 * - Publishes all stubs
 * - Loads Volt functions
 * - Configures Folio routes
 */
class VoltStarterServiceProvider extends PackageServiceProvider
{
    /**
     * AllVolt component classes to auto-register.
     */
    protected array $voltComponents = [
        // Components will be auto-discovered
    ];

    /**
     * Aliases for Blade components.
     */
    protected array $bladeComponentAliases = [
        'Ui.Button' => \Ilhamsyabani\VoltStarter\View\Components\Ui\Button::class,
        'Ui.Input' => \Ilhamsyabani\VoltStarter\View\Components\Ui\Input::class,
        'Ui.Card' => \Ilhamsyabani\VoltStarter\View\Components\Ui\Card::class,
        // Add more as needed
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-volt-starter')
            ->hasConfigFile()
            ->hasCommands([
                InstallCommand::class,
                MakePageCommand::class,
                MakeCrudCommand::class,
                PresetCommand::class,
            ]);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        // Load helpers
        $this->loadHelpers();

        // Register Volt components
        $this->registerVoltComponents();

        // Register blade components
        $this->registerBladeComponents();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish stubs
        $this->publishStubs();

        // Load Folio routes
        $this->loadFolioRoutes();

        // Register middleware aliases
        $this->registerMiddlewareAliases();
    }

    /**
     * Load helper functions.
     */
    protected function loadHelpers(): void
    {
        $helpersPath = __DIR__ . '/Support/helpers.php';

        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
    }

    /**
     * Register Volt component classes.
     */
    protected function registerVoltComponents(): void
    {
        // Volt components are auto-discovered in Laravel 11+
        // But we can register custom ones here if needed
    }

    /**
     * Register Blade components.
     */
    protected function registerBladeComponents(): void
    {
        // Register layout components
        $this->loadViewNamespace('volt-starter', __DIR__ . '/../stubs/views');

        // Register blade components if classes exist
        foreach ($this->bladeComponentAliases as $alias => $class) {
            if (class_exists($class)) {
                $this->app['blade.compiler']->component($alias, $class);
            }
        }
    }

    /**
     * Load a custom view namespace.
     */
    protected function loadViewNamespace(string $namespace, string $path): void
    {
        $this->app['view']->addNamespace($namespace, $path);
    }

    /**
     * Publish all stubs to the application.
     */
    protected function publishStubs(): void
    {
        $stubsPath = __DIR__ . '/../stubs';

        // Layouts
        $this->publishes([
            $stubsPath . '/layouts/app.blade.php' => resource_path('views/components/layouts/app.blade.php'),
            $stubsPath . '/layouts/app/sidebar.blade.php' => resource_path('views/components/layouts/app/sidebar.blade.php'),
        ], 'volt-starter-layouts');

        // UI Components
        $this->publishes([
            $stubsPath . '/components/ui/button.blade.php' => resource_path('views/components/ui/button.blade.php'),
            $stubsPath . '/components/ui/input.blade.php' => resource_path('views/components/ui/input.blade.php'),
            $stubsPath . '/components/ui/textarea.blade.php' => resource_path('views/components/ui/textarea.blade.php'),
            $stubsPath . '/components/ui/select.blade.php' => resource_path('views/components/ui/select.blade.php'),
            $stubsPath . '/components/ui/checkbox.blade.php' => resource_path('views/components/ui/checkbox.blade.php'),
            $stubsPath . '/components/ui/radio.blade.php' => resource_path('views/components/ui/radio.blade.php'),
            $stubsPath . '/components/ui/toggle.blade.php' => resource_path('views/components/ui/toggle.blade.php'),
            $stubsPath . '/components/ui/badge.blade.php' => resource_path('views/components/ui/badge.blade.php'),
            $stubsPath . '/components/ui/card.blade.php' => resource_path('views/components/ui/card.blade.php'),
            $stubsPath . '/components/ui/alert.blade.php' => resource_path('views/components/ui/alert.blade.php'),
            $stubsPath . '/components/ui/toast.blade.php' => resource_path('views/components/ui/toast.blade.php'),
            $stubsPath . '/components/ui/spinner.blade.php' => resource_path('views/components/ui/spinner.blade.php'),
            $stubsPath . '/components/ui/avatar.blade.php' => resource_path('views/components/ui/avatar.blade.php'),
            $stubsPath . '/components/ui/breadcrumb.blade.php' => resource_path('views/components/ui/breadcrumb.blade.php'),
            $stubsPath . '/components/ui/dropdown.blade.php' => resource_path('views/components/ui/dropdown.blade.php'),
            $stubsPath . '/components/ui/dropdown-item.blade.php' => resource_path('views/components/ui/dropdown-item.blade.php'),
            $stubsPath . '/components/ui/empty-state.blade.php' => resource_path('views/components/ui/empty-state.blade.php'),
            $stubsPath . '/components/ui/modal.blade.php' => resource_path('views/components/ui/modal.blade.php'),
            $stubsPath . '/components/ui/pagination.blade.php' => resource_path('views/components/ui/pagination.blade.php'),
            $stubsPath . '/components/ui/skeleton.blade.php' => resource_path('views/components/ui/skeleton.blade.php'),
            $stubsPath . '/components/ui/table.blade.php' => resource_path('views/components/ui/table.blade.php'),
            $stubsPath . '/components/ui/table/th.blade.php' => resource_path('views/components/ui/table/th.blade.php'),
            $stubsPath . '/components/ui/table/td.blade.php' => resource_path('views/components/ui/table/td.blade.php'),
            $stubsPath . '/components/ui/table/row.blade.php' => resource_path('views/components/ui/table/row.blade.php'),
            $stubsPath . '/components/ui/table/empty.blade.php' => resource_path('views/components/ui/table/empty.blade.php'),
            $stubsPath . '/components/ui/tabs.blade.php' => resource_path('views/components/ui/tabs.blade.php'),
            $stubsPath . '/components/ui/tooltip.blade.php' => resource_path('views/components/ui/tooltip.blade.php'),
        ], 'volt-starter-components');

        // Theme CSS
        $this->publishes([
            $stubsPath . '/css/volt-starter.css' => resource_path('css/volt-starter.css'),
        ], 'volt-starter-theme');

        // Pages
        $this->publishes([
            $stubsPath . '/pages/showcase.blade.php' => resource_path('views/pages/showcase.blade.php'),
            $stubsPath . '/pages/dashboard.blade.php' => resource_path('views/pages/dashboard.blade.php'),
            $stubsPath . '/pages/auth/login.blade.php' => resource_path('views/pages/auth/login.blade.php'),
            $stubsPath . '/pages/auth/register.blade.php' => resource_path('views/pages/auth/register.blade.php'),
            $stubsPath . '/pages/settings/profile.blade.php' => resource_path('views/pages/settings/profile.blade.php'),
        ], 'volt-starter-pages');

        // Middleware
        $this->publishes([
            $stubsPath . '/middleware/EnsureUserHasRole.php' => app_path('Http/Middleware/EnsureUserHasRole.php'),
            $stubsPath . '/middleware/EnsureUserIsOwner.php' => app_path('Http/Middleware/EnsureUserIsOwner.php'),
            $stubsPath . '/middleware/EnsureUserHasPermission.php' => app_path('Http/Middleware/EnsureUserHasPermission.php'),
        ], 'volt-starter-middleware');

        // Folio Routes
        $this->publishes([
            $stubsPath . '/routes/folio.php' => base_path('routes/folio.php'),
        ], 'volt-starter-folio');

        // Migrations
        $this->publishes([
            $stubsPath . '/migrations/add_role_to_users_table.php' => database_path('migrations/' . date('Y_m_d_His') . '_add_role_to_users_table.php'),
        ], 'volt-starter-migrations');
    }

    /**
     * Load Folio routes.
     */
    protected function loadFolioRoutes(): void
    {
        if (!class_exists(Folio::class)) {
            return;
        }

        $folioRouteFile = base_path('routes/folio.php');

        if (file_exists($folioRouteFile)) {
            Route::middleware(['web'])
                ->group(fn () => require $folioRouteFile);
        } else {
            Route::middleware(['web'])
                ->group(fn () => Folio::route(resource_path('views/pages')));
        }
    }

    /**
     * Register middleware aliases.
     */
    protected function registerMiddlewareAliases(): void
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        // Only register if not already registered
        if (!$router->hasMiddlewareAlias('role')) {
            $router->aliasMiddleware('role', \App\Http\Middleware\EnsureUserHasRole::class);
        }

        if (!$router->hasMiddlewareAlias('owner')) {
            $router->aliasMiddleware('owner', \App\Http\Middleware\EnsureUserIsOwner::class);
        }

        if (!$router->hasMiddlewareAlias('can')) {
            $router->aliasMiddleware('can', \App\Http\Middleware\EnsureUserHasPermission::class);
        }
    }
}
