<?php

namespace DeptOfScrapyardRobotics\Tests\UART;

use GeneralPurposeIO\Contracts\UART\DataBits;
use GeneralPurposeIO\Contracts\UART\FlowControl;
use GeneralPurposeIO\Contracts\UART\Parity;
use GeneralPurposeIO\Contracts\UART\StopBits;
use GeneralPurposeIO\Contracts\UART\UARTDriver as UARTDriverContract;
use GeneralPurposeIO\UART\Adapters\FtdiUARTAdapter;
use GeneralPurposeIO\UART\Adapters\PosixUARTAdapter;
use GeneralPurposeIO\UART\Bus\UARTBus;
use GeneralPurposeIO\UART\Drivers\UARTDriver;
use GeneralPurposeIO\UART\Factory\UARTFactory;
use PHPUnit\Framework\TestCase;

class UARTTest extends TestCase
{
    public function testUARTAdaptersUseAutoloadSafeClassNames(): void
    {
        $config = require dirname(__DIR__, 2).'/config/gpio.php';

        $this->assertSame(PosixUARTAdapter::class, $config['protocols']['uart']['adapters']['posix']);
        $this->assertSame(FtdiUARTAdapter::class, $config['protocols']['uart']['adapters']['usb']);
    }

    public function testFactoryDefaultsAndConfigurationArePreserved(): void
    {
        $factory = new class extends UARTFactory
        {
            protected function assertReady(): void {}

            public function driver(): UARTDriverContract
            {
                return new FakeUARTDriver();
            }
        };

        $this->assertSame(9_600, $factory->baud_rate);
        $this->assertSame(Parity::NONE, $factory->parity);
        $this->assertSame(StopBits::ONE, $factory->stop_bits);
        $this->assertSame(DataBits::EIGHT, $factory->data_bits);
        $this->assertSame(FlowControl::NONE, $factory->flow_control);

        $result = $factory
            ->baud(115_200)
            ->parity(Parity::EVEN)
            ->stopBits(StopBits::TWO)
            ->dataBits(DataBits::SEVEN)
            ->flowControl(FlowControl::HARDWARE);

        $this->assertSame($factory, $result);
        $this->assertSame(115_200, $factory->baud_rate);
        $this->assertSame(Parity::EVEN, $factory->parity);
        $this->assertSame(StopBits::TWO, $factory->stop_bits);
        $this->assertSame(DataBits::SEVEN, $factory->data_bits);
        $this->assertSame(FlowControl::HARDWARE, $factory->flow_control);
    }

    public function testBusDelegatesIOAndLifecycleToItsDriver(): void
    {
        $driver = new FakeUARTDriver();
        $bus = new class($driver) extends UARTBus {};

        $this->assertSame([0x41, 0x42], $bus->read(2));
        $this->assertSame(2, $bus->write([0x41, 0x42]));

        $bus->flush();
        $bus->close();

        $this->assertSame([2], $driver->reads);
        $this->assertSame([[0x41, 0x42]], $driver->writes);
        $this->assertSame(1, $driver->flushes);
        $this->assertSame(1, $driver->closes);
        $this->assertSame($driver, $bus->driver());
    }
}

class FakeUARTDriver extends UARTDriver
{
    public array $reads = [];

    public array $writes = [];

    public int $flushes = 0;

    public int $closes = 0;

    public function read(int $length): array|false
    {
        $this->reads[] = $length;

        return [0x41, 0x42];
    }

    public function write(array|string $data): int
    {
        $this->writes[] = $data;

        return is_array($data) ? count($data) : strlen($data);
    }

    public function flush(): void
    {
        $this->flushes++;
    }

    public function close(): void
    {
        $this->closes++;
    }
}
