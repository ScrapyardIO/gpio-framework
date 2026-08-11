<?php

namespace GeneralPurposeIO\Core;

use Composer\InstalledVersions;
use Fabricate\Contracts\Core\Program;
use Fabricate\Core\Console\AboutCommand;
use GeneralPurposeIO\I2C\I2CServiceProvider;
use GeneralPurposeIO\PWM\PWMServiceProvider;
use GeneralPurposeIO\SPI\SPIServiceProvider;
use GeneralPurposeIO\UART\UARTServiceProvider;
use GeneralPurposeIO\Common\GPIOProtocolManager;
use Fabricate\Core\Machine as ScrapyardIOMachine;
use GeneralPurposeIO\Analog\AnalogIOServiceProvider;
use Fabricate\NutsAndBolts\AggregateServiceProvider;
use GeneralPurposeIO\Circuits\CircuitServiceProvider;
use GeneralPurposeIO\Digital\DigitalIOServiceProvider;
use Fabricate\NutsAndBolts\Contracts\DeferrableProvider;
use Fabricate\Chassis\Exceptions\BindingResolutionException;
use GeneralPurposeIO\Contracts\Core\GPIOProtocolFactory as FactoryContract;

class GPIOServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{
    protected array $providers = [
        CircuitServiceProvider::class,
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

        if ($this->container instanceof ScrapyardIOMachine && $this->container->runningInConsole()) {
            $this->publishes([$source => $this->container->configPath('gpio.php')]);
        }

        $this->mergeConfigFrom($source, 'gpio');
    }

    protected function registerSingletons(): void
    {
        $this->container->singleton('gpio', fn (Program $program) => new GPIOProtocolManager($program));
        $this->container->alias('gpio', GPIOProtocolManager::class);
        $this->container->alias('gpio', FactoryContract::class);
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
        $this->registerAboutSection();
    }

    /**
     * Contribute protocol inventory to Workshop `about`.
     */
    protected function registerAboutSection(): void
    {
        if (! class_exists(AboutCommand::class)) {
            return;
        }

        AboutCommand::add('GPIO', fn (): array => $this->gpioAboutRows());
    }

    /**
     * @return array<string, mixed>
     */
    protected function gpioAboutRows(): array
    {
        $usbUart = $this->microscrapIsInstalled('microscrap/ftdi');
        $usbMpsse = $this->microscrapIsInstalled('microscrap/mpsse');
        $hasPosix = $this->microscrapIsInstalled('microscrap/posix');

        $posixUart = $hasPosix && $this->microscrapIsInstalled('microscrap/uart');
        $posixDigital = $hasPosix && $this->microscrapIsInstalled('microscrap/gpio');
        $posixI2c = $hasPosix && $this->microscrapIsInstalled('microscrap/i2c');
        $posixSpi = $hasPosix && $this->microscrapIsInstalled('microscrap/spi');

        return [
            'digital-io' => $this->formatAboutAdapters($usbMpsse, $posixDigital),
            'analog-io' => 'none',
            'uart' => $this->formatAboutAdapters($usbUart, $posixUart),
            'pwm' => is_dir('/sys/class/pwm') ? 'native' : 'none',
            'i2c' => $this->formatAboutAdapters($usbMpsse, $posixI2c),
            'spi' => $this->formatAboutAdapters($usbMpsse, $posixSpi),
        ];
    }

    /**
     * Format adapter availability for About (usb green, posix yellow, dual like Drivers→Logs).
     */
    protected function formatAboutAdapters(bool $usb, bool $posix): mixed
    {
        if (! $usb && ! $posix) {
            return 'none';
        }

        if ($usb && $posix) {
            return AboutCommand::format(
                value: ['usb', 'posix'],
                console: fn () => '<fg=green;options=bold>usb</> <fg=gray;options=bold>/</> <fg=yellow;options=bold>posix</>',
                json: fn (array $value) => $value,
            );
        }

        if ($usb) {
            return AboutCommand::format(
                value: 'usb',
                console: fn (string $value) => '<fg=green;options=bold>'.$value.'</>',
            );
        }

        return AboutCommand::format(
            value: 'posix',
            console: fn (string $value) => '<fg=yellow;options=bold>'.$value.'</>',
        );
    }

    protected function microscrapIsInstalled(string $package): bool
    {
        return class_exists(InstalledVersions::class)
            && InstalledVersions::isInstalled($package);
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
