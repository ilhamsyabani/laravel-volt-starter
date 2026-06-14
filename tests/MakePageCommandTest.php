<?php

namespace Ilhamsyabani\VoltStarter\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('page command creates a new page', function () {
    $pageName = 'test-page-' . time();

    $this->artisan('volt-starter:page', ['name' => $pageName])
        ->assertSuccessful();

    $pagePath = resource_path("views/pages/{$pageName}.blade.php");
    expect(File::exists($pagePath))->toBeTrue();

    // Cleanup
    File::deleteDirectory(resource_path("views/pages"), true);
});

it('page command creates page with auth middleware', function () {
    $pageName = 'test-auth-page-' . time();

    $this->artisan('volt-starter:page', [
        'name' => $pageName,
        '--auth' => true,
    ])->assertSuccessful();

    $pagePath = resource_path("views/pages/{$pageName}.blade.php");
    $content = File::get($pagePath);

    expect($content)->toContain("middleware(['auth', 'verified'])");

    // Cleanup
    File::deleteDirectory(resource_path("views/pages"), true);
});

it('page command creates page with admin middleware', function () {
    $pageName = 'test-admin-page-' . time();

    $this->artisan('volt-starter:page', [
        'name' => $pageName,
        '--admin' => true,
    ])->assertSuccessful();

    $pagePath = resource_path("views/pages/{$pageName}.blade.php");
    $content = File::get($pagePath);

    expect($content)->toContain("middleware(['auth', 'verified', 'role:admin'])");

    // Cleanup
    File::deleteDirectory(resource_path("views/pages"), true);
});

it('page command creates bare page', function () {
    $pageName = 'test-bare-page-' . time();

    $this->artisan('volt-starter:page', [
        'name' => $pageName,
        '--bare' => true,
    ])->assertSuccessful();

    $pagePath = resource_path("views/pages/{$pageName}.blade.php");
    $content = File::get($pagePath);

    expect($content)->toContain('<x-layouts.app>');
    expect($content)->not->toContain('computed');

    // Cleanup
    File::deleteDirectory(resource_path("views/pages"), true);
});

it('page command creates nested page', function () {
    $pageName = 'admin/test-nested-' . time();

    $this->artisan('volt-starter:page', ['name' => $pageName])
        ->assertSuccessful();

    $pagePath = resource_path("views/pages/{$pageName}.blade.php");
    expect(File::exists($pagePath))->toBeTrue();

    // Cleanup
    File::deleteDirectory(resource_path("views/pages"), true);
});
