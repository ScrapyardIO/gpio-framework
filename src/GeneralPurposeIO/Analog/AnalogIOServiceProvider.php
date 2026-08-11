<?php

namespace GeneralPurposeIO\Analog;

use Fabricate\Contracts\Core\Program;
use Fabricate\NutsAndBolts\ServiceProvider;
use GeneralPurposeIO\Core\MagicAliases\GPIO;
use GeneralPurposeIO\Digital\DigitalInAdapterManager;
use GeneralPurposeIO\Digital\DigitalOutAdapterManager;
use Fabricate\NutsAndBolts\Contracts\DeferrableProvider;
use Fabricate\Chassis\Exceptions\CircularDependencyException;

class AnalogIOServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        //$this->container->singleton('gpio.analog-in', fn(Program $program) => new DigitalInAdapterManager($program));
        //$this->container->alias('gpio.analog-in', DigitalInAdapterManager::class);

        //$this->container->singleton('gpio.analog-out', fn(Program $program) => new DigitalOutAdapterManager($program));
        //$this->container->alias('gpio.analog-out', DigitalOutAdapterManager::class);
    }

    /**
     * @throws CircularDependencyException
     */
    public function boot(): void
    {
        /*
        $adapters = config('gpio.protocols.analog-in.adapters');
        foreach ($adapters as $adapter => $adapter_class) {
            ADC::extend($adapter, fn() => new $adapter_class());
        }

        $adapters = config('gpio.protocols.analog-out.adapters');
        foreach ($adapters as $adapter => $adapter_class) {
            DAC::extend($adapter, fn() => new $adapter_class());
        }

        GPIO::extend('analog-in', fn() => app('gpio.analog-in'));
        GPIO::extend('analog-out', fn() => app('gpio.analog-out'));
        */
    }

    public function provides(): array
    {
        return ['gpio.analog-in', 'gpio.analog-in'];
    }
}