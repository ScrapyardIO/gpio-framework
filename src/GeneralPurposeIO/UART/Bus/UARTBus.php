<?php

namespace GeneralPurposeIO\UART\Bus;

use GeneralPurposeIO\Contracts\UART\UARTDriver;

abstract class UARTBus
{
    public function __construct(
        protected UARTDriver $driver,
    ) {}

    public function read(int $length): array|false
    {
        return $this->driver->read($length);
    }

    public function write(array|string $data): int
    {
        return $this->driver->write($data);
    }

    public function flush(): void
    {
        $this->driver->flush();
    }

    public function close(): void
    {
        $this->driver->close();
    }

    public function driver(): UARTDriver
    {
        return $this->driver;
    }
}
