<?php

namespace GeneralPurposeIO\Digital;

use Fabricate\Contracts\Core\Program;
use Fabricate\NutsAndBolts\ServiceProvider;
use GeneralPurposeIO\Core\MagicAliases\GPIO;
use Fabricate\Contracts\NutsAndBolts\DeferrableProvider;
use Fabricate\Contracts\Chassis\CircularDependencyException;

class DigitalIOServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->program->singleton('gpio.digital-io', fn(Program $program) => new DigitalIOAdapterManager($program));
        $this->program->alias('gpio.digital-io', DigitalIOAdapterManager::class);
    }

    /**
     * @throws CircularDependencyException
     */
    public function boot(): void
    {
        $adapters = config('gpio.protocols.digital-io.adapters');
        foreach ($adapters as $adapter => $adapter_class) {
            DigitalIO::extend($adapter, fn() => new $adapter_class());
        }

        GPIO::extend('digital-io', fn() => app('gpio.digital-io'));
    }

    public function provides(): array
    {
        return ['gpio.digital-io'];
    }
}