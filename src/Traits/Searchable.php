<?php

namespace Ilhamsyabani\VoltStarter\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Searchable Trait for Volt Components
 *
 * Usage:
 *   use Searchable;
 *
 *   state(['search' => '']);
 *
 *   $items = computed(function () {
 *       return $this->applySearch(Post::query())
 *           ->latest()
 *           ->paginate(10);
 *   });
 */
trait Searchable
{
    /**
     * Apply search filter to query.
     * Override this method to customize search fields.
     */
    protected function applySearch(Builder $query): Builder
    {
        $search = $this->search ?? '';

        if (empty($search)) {
            return $query;
        }

        // Default: search by 'name' column
        // Override this method to customize
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Apply search with multiple fields.
     */
    protected function applySearchWith(array $fields, Builder $query): Builder
    {
        $search = $this->search ?? '';

        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($fields, $search) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'like', "%{$search}%");
            }
        });
    }

    /**
     * Apply search with relationship.
     */
    protected function applySearchWithRelation(
        string $relation,
        array $fields,
        Builder $query
    ): Builder {
        $search = $this->search ?? '';

        if (empty($search)) {
            return $query;
        }

        return $query->whereHas($relation, function ($q) use ($fields, $search) {
            $q->where(function ($qq) use ($fields, $search) {
                foreach ($fields as $field) {
                    $qq->orWhere($field, 'like', "%{$search}%");
                }
            });
        });
    }
}
