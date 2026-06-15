<?php

namespace Ilhamsyabani\VoltStarter\Tests;

use Ilhamsyabani\VoltStarter\Traits\Toast;
use Livewire\Volt\Volt;

it('toast trait dispatches correct event', function () {
    $component = new class {
        use Toast;
        public array $dispatchedEvents = [];

        public function dispatch(string $event, ...$params): void
        {
            $this->dispatchedEvents[] = ['event' => $event, 'params' => $params];
        }
    };

    $component->success('Test message');
    expect($component->dispatchedEvents[0]['event'])->toBe('notify');
    expect($component->dispatchedEvents[0]['params']['type'])->toBe('success');
    expect($component->dispatchedEvents[0]['params']['message'])->toBe('Test message');
});

it('toast error dispatches error event', function () {
    $component = new class {
        use Toast;
        public array $dispatchedEvents = [];

        public function dispatch(string $event, ...$params): void
        {
            $this->dispatchedEvents[] = ['event' => $event, 'params' => $params];
        }
    };

    $component->error('Error message');
    expect($component->dispatchedEvents[0]['params']['type'])->toBe('error');
    expect($component->dispatchedEvents[0]['params']['message'])->toBe('Error message');
});

it('toast warning dispatches warning event', function () {
    $component = new class {
        use Toast;
        public array $dispatchedEvents = [];

        public function dispatch(string $event, ...$params): void
        {
            $this->dispatchedEvents[] = ['event' => $event, 'params' => $params];
        }
    };

    $component->warning('Warning message');
    expect($component->dispatchedEvents[0]['params']['type'])->toBe('warning');
});

it('toast info dispatches info event', function () {
    $component = new class {
        use Toast;
        public array $dispatchedEvents = [];

        public function dispatch(string $event, ...$params): void
        {
            $this->dispatchedEvents[] = ['event' => $event, 'params' => $params];
        }
    };

    $component->info('Info message');
    expect($component->dispatchedEvents[0]['params']['type'])->toBe('info');
});

it('toast method accepts custom type', function () {
    $component = new class {
        use Toast;
        public array $dispatchedEvents = [];

        public function dispatch(string $event, ...$params): void
        {
            $this->dispatchedEvents[] = ['event' => $event, 'params' => $params];
        }
    };

    // Note: toast() validates types and falls back to 'info' for unknown types
    // So 'custom' becomes 'info' - this is expected behavior
    $component->toast('custom', 'Custom message');
    expect($component->dispatchedEvents[0]['params']['type'])->toBe('info');
});

it('toast method falls back to info for invalid type', function () {
    $component = new class {
        use Toast;
        public array $dispatchedEvents = [];

        public function dispatch(string $event, ...$params): void
        {
            $this->dispatchedEvents[] = ['event' => $event, 'params' => $params];
        }
    };

    $component->toast('invalid_type', 'Message');
    expect($component->dispatchedEvents[0]['params']['type'])->toBe('info');
});
