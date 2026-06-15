<?php

namespace Ilhamsyabani\VoltStarter\Tests;

use Ilhamsyabani\VoltStarter\VoltStarterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            VoltStarterServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
    }

    /**
     * Get application timezone.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string|null
     */
    protected function getApplicationTimezone($app): ?string
    {
        return 'Asia/Jakarta';
    }

    /**
     * Call artisan command and return output.
     *
     * @param  string  $command
     * @param  array  $parameters
     * @return int
     */
    public function artisan($command, $parameters = [])
    {
        return $this->artisanCall($command, $parameters);
    }

    /**
     * Execute an artisan console command.
     *
     * @param  string  $command
     * @param  array  $parameters
     * @return int
     */
    protected function artisanCall($command, $parameters = []): int
    {
        $this->refreshApplication();

        return $this->getApplication()->run(
            new \Symfony\Component\Console\Input\ArrayInput(array_merge(
                ['command' => $command],
                $parameters
            )),
            new \Symfony\Component\Console\Output\ConsoleOutput
        );
    }
}
