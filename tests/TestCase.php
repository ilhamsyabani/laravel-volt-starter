<?php

namespace Ilhamsyabani\VoltStarter\Tests;

use Ilhamsyabani\VoltStarter\VoltStarterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            VoltStarterServiceProvider::class,
        ];
    }

    protected function getApplicationEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
