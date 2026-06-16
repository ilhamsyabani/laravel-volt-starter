<?php

namespace Ilhamsyabani\VoltStarter\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Sortable Trait for Volt Components
 *
 * Usage:
 *   use Sortable;
 *
 *   state(['sortField' => 'created_at', 'sortDirection' => 'desc']);
 *
 *   $items = computed(function () {
 *       return $this->applySort(Post::query())
 *           ->paginate(10);
 *   });
 */
trait Sortable
{
    /**
     * Apply sorting to query.
     */
    public function applySort(Builder $query): Builder
    {
        $field = $this->sortField ?? 'created_at';
        $direction = $this->sortDirection ?? 'desc';

        // Validate direction
        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            $direction = 'desc';
        }

        return $query->orderBy($field, $direction);
    }

    /**
     * Toggle sort direction and field.
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    /**
     * Get sort icon class for a field.
     */
    public function getSortIcon(string $field): string
    {
        if ($this->sortField !== $field) {
            return 'text-zinc-300 dark:text-zinc-600';
        }

        return $this->sortDirection === 'asc'
            ? 'vs-text-primary'
            : 'vs-text-muted';
    }

    /**
     * Get sort direction indicator.
     */
    public function getSortDirectionIndicator(string $field): ?string
    {
        if ($this->sortField !== $field) {
            return null;
        }

        return $this->sortDirection === 'asc' ? '↑' : '↓';
    }
}
