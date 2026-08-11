<?php

namespace DeptOfScrapyardRobotics\Tests\Circuits;

use GeneralPurposeIO\Circuits\CircuitRegistry;
use GeneralPurposeIO\Contracts\Circuits\Attributes\IntegratedCircuit as IntegratedCircuitAttribute;
use GeneralPurposeIO\Contracts\Circuits\CircuitException;
use GeneralPurposeIO\Contracts\Circuits\IntegratedCircuit;
use PHPUnit\Framework\TestCase;

#[IntegratedCircuitAttribute('I2C', ['SPI', 'DigitalIO'])]
class CircuitRegistryDemoIc implements IntegratedCircuit
{
    public function __construct(
        public readonly string $via = '',
        public readonly array $args = [],
    ) {}

    public function close(): void {}

    public static function i2c(
        string|int $device,
        ?string $adapter = null,
        int $slave = 0x38,
        bool $boot_now = true,
    ): static {
        return new static('i2c', compact('device', 'adapter', 'slave', 'boot_now'));
    }

    public static function spi(
        string|int $spi_device,
        string|int $chip_select,
        string|int $digital_device,
        int $dc_pin,
        int $rst_pin,
        ?string $spi_adapter = null,
        ?string $digital_adapter = null,
        bool $boot_now = true,
    ): static {
        return new static('spi', compact(
            'spi_device',
            'chip_select',
            'digital_device',
            'dc_pin',
            'rst_pin',
            'spi_adapter',
            'digital_adapter',
            'boot_now',
        ));
    }
}

class CircuitRegistryTest extends TestCase
{
    public function testAddCircuitRegistersImplementorsOnly(): void
    {
        $registry = new CircuitRegistry;
        $registry->addCircuit('demo', CircuitRegistryDemoIc::class);
        $registry->addCircuit('nope', \stdClass::class);

        $this->assertSame(
            ['demo' => CircuitRegistryDemoIc::class],
            $registry->listCircuits(),
        );
    }

    public function testBuildInvokesNamedProtocolFactory(): void
    {
        $registry = new CircuitRegistry;
        $registry->addCircuit('demo', CircuitRegistryDemoIc::class);

        $ic = $registry->build('demo', 'i2c', [
            'device' => 1,
            'adapter' => 'posix',
            'slave' => 0x3C,
            'boot_now' => false,
        ]);

        $this->assertInstanceOf(CircuitRegistryDemoIc::class, $ic);
        $this->assertSame('i2c', $ic->via);
        $this->assertSame(1, $ic->args['device']);
        $this->assertSame('posix', $ic->args['adapter']);
        $this->assertSame(0x3C, $ic->args['slave']);
        $this->assertFalse($ic->args['boot_now']);
    }

    public function testFluentMakeMapsSpiDriverAndDevicePrefixes(): void
    {
        $registry = new CircuitRegistry;
        $registry->addCircuit('demo', CircuitRegistryDemoIc::class);

        $ic = $registry->ic('demo')
            ->protocol('spi')
            ->driver('usb')
            ->device('ft232h')
            ->chipSelect(0)
            ->dc(1)
            ->rst(2)
            ->make();

        $this->assertInstanceOf(CircuitRegistryDemoIc::class, $ic);
        $this->assertSame('spi', $ic->via);
        $this->assertSame('usb', $ic->args['spi_adapter']);
        $this->assertSame('usb', $ic->args['digital_adapter']);
        $this->assertSame('ft232h', $ic->args['spi_device']);
        $this->assertSame('ft232h', $ic->args['digital_device']);
        $this->assertSame(0, $ic->args['chip_select']);
        $this->assertSame(1, $ic->args['dc_pin']);
        $this->assertSame(2, $ic->args['rst_pin']);
    }

    public function testBuildRejectsMissingRequiredParams(): void
    {
        $registry = new CircuitRegistry;
        $registry->addCircuit('demo', CircuitRegistryDemoIc::class);

        $this->expectException(CircuitException::class);
        $this->expectExceptionMessage('missing required parameter [device]');

        $registry->build('demo', 'i2c', []);
    }
}
