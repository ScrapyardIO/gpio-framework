<?php

namespace GeneralPurposeIO\UART;

use Fabricate\Chassis\Exceptions\CircularDependencyException;
use Fabricate\Contracts\Core\Program;
use Fabricate\NutsAndBolts\ServiceProvider;
use GeneralPurposeIO\Core\MagicAliases\GPIO;
use Fabricate\NutsAndBolts\Contracts\DeferrableProvider;

class UARTServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->container->singleton('gpio.uart', fn(Program $program) => new UARTAdapterManager($program));
        $this->container->alias('gpio.uart', UARTAdapterManager::class);
    }

    /**
     * @throws CircularDependencyException
     */
    public function boot(): void
    {
        $adapters = config('gpio.protocols.uart.adapters');
        foreach ($adapters as $adapter => $adapter_class) {
            UART::extend($adapter, fn() => new $adapter_class());
        }

        GPIO::extend('uart', fn() => app('gpio.uart'));
    }

    public function provides(): array
    {
        return ['gpio.uart'];
    }
}