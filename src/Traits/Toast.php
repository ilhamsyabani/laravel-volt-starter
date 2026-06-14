<?php

namespace Ilhamsyabani\VoltStarter\Traits;

use Livewire\Volt\Volt;

/**
 * Toast Notification Trait for Volt Components
 *
 * Usage:
 *   use Toast;
 *
 *   $this->success('Data saved!');
 *   $this->error('Something went wrong.');
 *   $this->warning('Please check your input.');
 *   $this->info('Processing...');
 */
trait Toast
{
    /**
     * Dispatch a success toast.
     */
    public function success(string $message): void
    {
        $this->dispatch('notify', type: 'success', message: $message);
    }

    /**
     * Dispatch an error toast.
     */
    public function error(string $message): void
    {
        $this->dispatch('notify', type: 'error', message: $message);
    }

    /**
     * Dispatch a warning toast.
     */
    public function warning(string $message): void
    {
        $this->dispatch('notify', type: 'warning', message: $message);
    }

    /**
     * Dispatch an info toast.
     */
    public function info(string $message): void
    {
        $this->dispatch('notify', type: 'info', message: $message);
    }

    /**
     * Dispatch a toast with custom type and message.
     */
    public function toast(string $type, string $message): void
    {
        $validTypes = ['success', 'error', 'warning', 'info'];

        if (!in_array($type, $validTypes)) {
            $type = 'info';
        }

        $this->dispatch('notify', type: $type, message: $message);
    }

    /**
     * Dispatch a success toast and redirect.
     */
    public function successRedirect(string $message, string $route, array $params = []): void
    {
        $this->success($message);
        $this->redirectRoute($route, $params, navigate: true);
    }

    /**
     * Dispatch an error toast and go back.
     */
    public function errorBack(string $message): void
    {
        $this->error($message);
        $this->redirect(url()->previous(), navigate: true);
    }
}
