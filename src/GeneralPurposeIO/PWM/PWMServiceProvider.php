<?php

namespace GeneralPurposeIO\PWM;

use Fabricate\Chassis\Exceptions\CircularDependencyException;
use Fabricate\Contracts\Core\Program;
use Fabricate\NutsAndBolts\ServiceProvider;
use GeneralPurposeIO\Core\MagicAliases\GPIO;
use Fabricate\NutsAndBolts\Contracts\DeferrableProvider;

class PWMServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->container->singleton('gpio.pwm', fn(Program $program) => new PWMAdapterManager($program));
        $this->container->alias('gpio.pwm', PWMAdapterManager::class);
    }

    /**
     * @throws CircularDependencyException
     */
    public function boot(): void
    {
        $adapters = config('gpio.protocols.pwm.adapters');
        foreach ($adapters as $adapter => $adapter_class) {
            PWM::extend($adapter, fn() => new $adapter_class());
        }

        GPIO::extend('pwm', fn() => app('gpio.pwm'));
    }

    public function provides(): array
    {
        return ['gpio.pwm'];
    }
}