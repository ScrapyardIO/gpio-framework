<?php

namespace GeneralPurposeIO\SPI;

use Fabricate\Chassis\Exceptions\CircularDependencyException;
use Fabricate\Contracts\Core\Program;
use Fabricate\NutsAndBolts\ServiceProvider;
use GeneralPurposeIO\Core\MagicAliases\GPIO;
use Fabricate\NutsAndBolts\Contracts\DeferrableProvider;

class SPIServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->container->singleton('gpio.spi', fn(Program $program) => new SPIAdapterManager($program));
        $this->container->alias('gpio.spi', SPIAdapterManager::class);
    }

    /**
     * @throws CircularDependencyException
     */
    public function boot(): void
    {
        $adapters = config('gpio.protocols.spi.adapters');
        foreach ($adapters as $adapter => $adapter_class) {
            SPI::extend($adapter, fn() => new $adapter_class());
        }

        GPIO::extend('spi', fn() => app('gpio.spi'));
    }

    public function provides(): array
    {
        return ['gpio.spi'];
    }
}