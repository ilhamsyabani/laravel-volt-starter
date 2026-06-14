<?php

namespace Ilhamsyabani\VoltStarter\Contracts;

/**
 * Interface for searchable Volt components.
 */
interface SearchableInterface
{
    /**
     * Apply search filter to a query builder.
     */
    public function applySearch($query);
}
