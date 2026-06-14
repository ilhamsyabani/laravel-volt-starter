<?php

namespace Ilhamsyabani\VoltStarter\Tests;

use Ilhamsyabani\VoltStarter\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;

it('searchable applies search with default field', function () {
    $component = new class {
        use Searchable;
        public string $search = 'test';
    };

    $query = mock(Builder::class);
    $query->shouldReceive('where')
        ->once()
        ->with('name', 'like', '%test%')
        ->andReturnSelf();

    $component->applySearch($query);
});

it('searchable returns query unchanged when search is empty', function () {
    $component = new class {
        use Searchable;
        public string $search = '';
    };

    $query = mock(Builder::class);
    $query->shouldNotReceive('where');

    $result = $component->applySearch($query);
    expect($result)->toBe($query);
});

it('searchable applies search with multiple fields', function () {
    $component = new class {
        use Searchable;
        public string $search = 'test';
    };

    $query = mock(Builder::class);
    $query->shouldReceive('where')
        ->once()
        ->andReturnSelf();

    $component->applySearchWith(['title', 'content'], $query);
});

it('searchable applies search with relation', function () {
    $component = new class {
        use Searchable;
        public string $search = 'test';
    };

    $query = mock(Builder::class);
    $query->shouldReceive('whereHas')
        ->once()
        ->with('author', \Mockery::type('callable'))
        ->andReturnSelf();

    $component->applySearchWithRelation('author', ['name', 'email'], $query);
});
