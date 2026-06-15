<?php

/**
 * Command tests - uses describe blocks for simpler testing.
 * These test the command classes directly without running artisan.
 */
describe('install command', function () {
    it('can be instantiated', function () {
        expect(class_exists(\Ilhamsyabani\VoltStarter\Commands\InstallCommand::class))->toBeTrue();
    });

    it('has correct signature', function () {
        $command = new \Ilhamsyabani\VoltStarter\Commands\InstallCommand();
        expect($command->getName())->toBe('volt-starter:install');
    });

    it('has all required options', function () {
        $command = new \Ilhamsyabani\VoltStarter\Commands\InstallCommand();
        $definition = $command->getDefinition();

        expect($definition->hasOption('auth'))->toBeTrue();
        expect($definition->hasOption('roles'))->toBeTrue();
        expect($definition->hasOption('full'))->toBeTrue();
        expect($definition->hasOption('folio'))->toBeTrue();
        expect($definition->hasOption('force'))->toBeTrue();
    });
});

describe('crud command', function () {
    it('can be instantiated', function () {
        expect(class_exists(\Ilhamsyabani\VoltStarter\Commands\MakeCrudCommand::class))->toBeTrue();
    });

    it('has correct signature', function () {
        $command = new \Ilhamsyabani\VoltStarter\Commands\MakeCrudCommand();
        expect($command->getName())->toBe('volt-starter:crud');
    });

    it('has all required options', function () {
        $command = new \Ilhamsyabani\VoltStarter\Commands\MakeCrudCommand();
        $definition = $command->getDefinition();

        expect($definition->hasOption('fields'))->toBeTrue();
        expect($definition->hasOption('with-migration'))->toBeTrue();
        expect($definition->hasOption('force'))->toBeTrue();
    });
});

describe('page command', function () {
    it('can be instantiated', function () {
        expect(class_exists(\Ilhamsyabani\VoltStarter\Commands\MakePageCommand::class))->toBeTrue();
    });

    it('has correct signature', function () {
        $command = new \Ilhamsyabani\VoltStarter\Commands\MakePageCommand();
        expect($command->getName())->toBe('volt-starter:page');
    });

    it('has all required options', function () {
        $command = new \Ilhamsyabani\VoltStarter\Commands\MakePageCommand();
        $definition = $command->getDefinition();

        expect($definition->hasOption('auth'))->toBeTrue();
        expect($definition->hasOption('admin'))->toBeTrue();
        expect($definition->hasOption('superadmin'))->toBeTrue();
        expect($definition->hasOption('bare'))->toBeTrue();
    });
});
