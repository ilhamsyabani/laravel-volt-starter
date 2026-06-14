<?php

namespace Ilhamsyabani\VoltStarter\Contracts;

/**
 * Interface for Volt Service Providers
 *
 * Implement this interface to create a custom Volt service provider
 * that auto-registers components and views.
 */
interface VoltServiceProviderInterface
{
    /**
     * Get the path to the Volt components directory.
     */
    public function getComponentsPath(): string;

    /**
     * Get the namespace for components.
     */
    public function getComponentsNamespace(): string;

    /**
     * Get the prefix for component tags.
     */
    public function getComponentPrefix(): string;

    /**
     * Get additional middleware to apply.
     */
    public function getMiddleware(): array;

    /**
     * Check if this provider should auto-discover components.
     */
    public function shouldAutoDiscover(): bool;
}
