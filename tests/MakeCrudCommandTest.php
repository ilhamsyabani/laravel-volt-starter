<?php

namespace Ilhamsyabani\VoltStarter\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('crud command creates index page', function () {
    $modelName = 'TestCrud' . time();

    $this->artisan('volt-starter:crud', ['model' => $modelName])
        ->assertSuccessful();

    $indexPath = resource_path("views/pages/" . strtolower($modelName) . "/index.blade.php");
    expect(File::exists($indexPath))->toBeTrue();

    // Cleanup
    File::deleteDirectory(resource_path("views/pages/" . strtolower($modelName)), true);
});

it('crud command creates create page', function () {
    $modelName = 'TestCrudCreate' . time();

    $this->artisan('volt-starter:crud', ['model' => $modelName])
        ->assertSuccessful();

    $createPath = resource_path("views/pages/" . strtolower($modelName) . "/create.blade.php");
    expect(File::exists($createPath))->toBeTrue();

    // Cleanup
    File::deleteDirectory(resource_path("views/pages/" . strtolower($modelName)), true);
});

it('crud command creates edit page', function () {
    $modelName = 'TestCrudEdit' . time();

    $this->artisan('volt-starter:crud', ['model' => $modelName])
        ->assertSuccessful();

    $editPath = resource_path("views/pages/" . strtolower($modelName) . "/[testcrudedit]/edit.blade.php");
    expect(File::exists($editPath))->toBeTrue();

    // Cleanup
    File::deleteDirectory(resource_path("views/pages/" . strtolower($modelName)), true);
});

it('crud command creates show page', function () {
    $modelName = 'TestCrudShow' . time();

    $this->artisan('volt-starter:crud', ['model' => $modelName])
        ->assertSuccessful();

    $showPath = resource_path("views/pages/" . strtolower($modelName) . "/[testcrudshow]/show.blade.php");
    expect(File::exists($showPath))->toBeTrue();

    // Cleanup
    File::deleteDirectory(resource_path("views/pages/" . strtolower($modelName)), true);
});

it('crud command with fields generates correct form fields', function () {
    $modelName = 'TestCrudFields' . time();

    $this->artisan('volt-starter:crud', [
        'model' => $modelName,
        '--fields' => 'title:string,body:text,status:select:draft|published',
    ])->assertSuccessful();

    $createPath = resource_path("views/pages/" . strtolower($modelName) . "/create.blade.php");
    $content = File::get($createPath);

    expect($content)->toContain('wire:model="title"');
    expect($content)->toContain('wire:model="body"');
    expect($content)->toContain('wire:model="status"');

    // Cleanup
    File::deleteDirectory(resource_path("views/pages/" . strtolower($modelName)), true);
});

it('crud command with migration generates migration file', function () {
    $modelName = 'TestCrudMigration' . time();

    $this->artisan('volt-starter:crud', [
        'model' => $modelName,
        '--with-migration' => true,
    ])->assertSuccessful();

    $migrationPath = database_path('migrations/create_' . strtolower($modelName) . 's_table.php');
    expect(File::exists($migrationPath))->toBeTrue();

    // Cleanup
    File::deleteDirectory(resource_path("views/pages/" . strtolower($modelName)), true);
    @unlink($migrationPath);
});

it('crud command shows generated routes info', function () {
    $this->artisan('volt-starter:crud', ['model' => 'Post'])
        ->expectsOutput('  📋 Index  → /posts')
        ->expectsOutput('  ➕ Create → /posts/create')
        ->assertSuccessful();
});
