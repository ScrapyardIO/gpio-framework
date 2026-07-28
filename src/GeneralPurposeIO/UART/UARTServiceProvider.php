<?php

namespace GeneralPurposeIO\UART;

use Fabricate\Contracts\Chassis\CircularDependencyException;
use Fabricate\Contracts\Core\Program;
use Fabricate\NutsAndBolts\ServiceProvider;
use GeneralPurposeIO\Core\MagicAliases\GPIO;
use Fabricate\Contracts\NutsAndBolts\DeferrableProvider;

class UARTServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->program->singleton('gpio.uart', fn(Program $program) => new UARTAdapterManager($program));
        $this->program->alias('gpio.uart', UARTAdapterManager::class);
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