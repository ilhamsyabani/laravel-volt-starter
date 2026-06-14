<?php

namespace Ilhamsyabani\VoltStarter\Contracts;

/**
 * Interface for sortable Volt components.
 */
interface SortableInterface
{
    /**
     * Apply sorting to a query builder.
     */
    public function applySort($query);

    /**
     * Get available sort fields.
     */
    public function getSortableFields(): array;
}
