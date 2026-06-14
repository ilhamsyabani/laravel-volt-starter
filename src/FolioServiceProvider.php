<?php

namespace Ilhamsyabani\VoltStarter;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;

class FolioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load Folio if available
        if (class_exists(Folio::class)) {
            Folio::route($this->app->basePath('routes/folio.php'))
                ->middleware([
                    'web',
                ]);
        }
    }
}