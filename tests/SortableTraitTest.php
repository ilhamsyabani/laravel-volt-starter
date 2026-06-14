<?php

namespace Ilhamsyabani\VoltStarter\Tests;

use Ilhamsyabani\VoltStarter\Traits\Sortable;
use Illuminate\Database\Eloquent\Builder;

it('sortable applies sort with default values', function () {
    $component = new class {
        use Sortable;
        public string $sortField = 'created_at';
        public string $sortDirection = 'desc';
    };

    $query = mock(Builder::class);
    $query->shouldReceive('orderBy')
        ->once()
        ->with('created_at', 'desc')
        ->andReturnSelf();

    $component->applySort($query);
});

it('sortable applies sort with asc direction', function () {
    $component = new class {
        use Sortable;
        public string $sortField = 'name';
        public string $sortDirection = 'asc';
    };

    $query = mock(Builder::class);
    $query->shouldReceive('orderBy')
        ->once()
        ->with('name', 'asc')
        ->andReturnSelf();

    $component->applySort($query);
});

it('sortable toggles direction when sorting same field', function () {
    $component = new class {
        use Sortable;
        public string $sortField = 'name';
        public string $sortDirection = 'desc';
    };

    $component->sortBy('name');

    expect($component->sortDirection)->toBe('asc');
});

it('sortable changes field and resets to desc when sorting new field', function () {
    $component = new class {
        use Sortable;
        public string $sortField = 'name';
        public string $sortDirection = 'asc';
    };

    $component->sortBy('created_at');

    expect($component->sortField)->toBe('created_at');
    expect($component->sortDirection)->toBe('desc');
});

it('sortable validates direction', function () {
    $component = new class {
        use Sortable;
        public string $sortField = 'name';
        public string $sortDirection = 'invalid';
    };

    $query = mock(Builder::class);
    $query->shouldReceive('orderBy')
        ->once()
        ->with('name', 'desc') // Should default to desc
        ->andReturnSelf();

    $component->applySort($query);
});

it('sortable returns sort icon class for inactive field', function () {
    $component = new class {
        use Sortable;
        public string $sortField = 'name';
        public string $sortDirection = 'desc';
    };

    $icon = $component->getSortIcon('created_at');
    expect($icon)->toBe('text-zinc-300 dark:text-zinc-600');
});

it('sortable returns sort direction indicator', function () {
    $component = new class {
        use Sortable;
        public string $sortField = 'name';
        public string $sortDirection = 'asc';
    };

    expect($component->getSortDirectionIndicator('name'))->toBe('↑');
    expect($component->getSortDirectionIndicator('created_at'))->toBeNull();
});

it('sortable returns desc indicator for desc direction', function () {
    $component = new class {
        use Sortable;
        public string $sortField = 'name';
        public string $sortDirection = 'desc';
    };

    expect($component->getSortDirectionIndicator('name'))->toBe('↓');
});
