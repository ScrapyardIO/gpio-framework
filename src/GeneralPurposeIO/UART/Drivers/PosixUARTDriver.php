<?php

namespace GeneralPurposeIO\UART\Drivers;

use Microscrap\Bindings\UART\DataObjects\UARTPort;

class PosixUARTDriver extends UARTDriver
{
    public function __construct(
        protected readonly UARTPort $port,
    ) {}

    public function read(int $length): array|false
    {
        $data = uart_read($this->port, $length);

        return $data === false ? false : bytes2array($data);
    }

    public function write(array|string $data): int
    {
        return uart_write($this->port, static::normalizeData($data));
    }

    public function flush(): void
    {
        uart_flush($this->port);
    }

    public function close(): void
    {
        uart_close($this->port);
    }

    public function getPort(): UARTPort
    {
        return $this->port;
    }
}
