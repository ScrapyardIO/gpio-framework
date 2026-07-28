<?php

namespace GeneralPurposeIO\Core;

use Fabricate\Contracts\Core\Program;
use GeneralPurposeIO\I2C\I2CServiceProvider;
use GeneralPurposeIO\PWM\PWMServiceProvider;
use GeneralPurposeIO\SPI\SPIServiceProvider;
use GeneralPurposeIO\UART\UARTServiceProvider;
use GeneralPurposeIO\Common\GPIOProtocolManager;
use Fabricate\Core\Machine as ScrapyardIOMachine;
use GeneralPurposeIO\Analog\AnalogIOServiceProvider;
use Fabricate\NutsAndBolts\AggregateServiceProvider;
use GeneralPurposeIO\Digital\DigitalIOServiceProvider;
use Fabricate\Contracts\NutsAndBolts\DeferrableProvider;
use Fabricate\Contracts\Chassis\BindingResolutionException;
use GeneralPurposeIO\Contracts\Core\GPIOProtocolFactory as FactoryContract;

class GPIOServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{
    protected array $providers = [
        UARTServiceProvider::class,
        DigitalIOServiceProvider::class,
        SPIServiceProvider::class,
        I2CServiceProvider::class,
        PWMServiceProvider::class,
        AnalogIOServiceProvider::class,
    ];

    /**
     * @throws BindingResolutionException
     */
    protected function publishConfig(): void
    {
        $source = realpath($raw = __DIR__.'/../../../config/gpio.php') ?: $raw;

        if ($this->program instanceof ScrapyardIOMachine && $this->program->runningInConsole()) {
            $this->publishes([$source => $this->program->configPath('gpio.php')]);
        }

        $this->mergeConfigFrom($source, 'gpio');
    }

    protected function registerSingletons(): void
    {
        $this->program->singleton('gpio', fn(Program $program) => new GPIOProtocolManager($program));
        $this->program->alias('gpio', GPIOProtocolManager::class);
        $this->program->alias('gpio', FactoryContract::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        $this->publishConfig();
        $this->registerSingletons();

        parent::register();
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['gpio', ...parent::provides()];
    }
}