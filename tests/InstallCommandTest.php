<?php

namespace Ilhamsyabani\VoltStarter\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('install command runs successfully', function () {
    $this->artisan('volt-starter:install')
        ->assertSuccessful();
});

it('install command publishes layouts', function () {
    $this->artisan('volt-starter:install', ['--force' => true])
        ->assertSuccessful();

    // Layouts should be published
    expect(File::exists(resource_path('views/components/layouts/app.blade.php')))->toBeTrue();
});

it('install command with auth option works', function () {
    $this->artisan('volt-starter:install', ['--auth' => true, '--force' => true])
        ->assertSuccessful();
});

it('install command with roles option works', function () {
    $this->artisan('volt-starter:install', ['--roles' => true, '--force' => true])
        ->assertSuccessful();
});

it('install command with full option works', function () {
    $this->artisan('volt-starter:install', ['--full' => true, '--force' => true])
        ->assertSuccessful();
});

it('install command with folio option works', function () {
    $this->artisan('volt-starter:install', ['--folio' => true, '--force' => true])
        ->assertSuccessful();
});

it('install command shows next steps', function () {
    $this->artisan('volt-starter:install')
        ->expectsOutput('Next steps:')
        ->assertSuccessful();
});
