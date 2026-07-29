<?php

namespace GeneralPurposeIO\Contracts\UART;

interface UARTDriver
{
    public function read(int $length): array|false;

    public function write(array|string $data): int;

    public function flush(): void;

    public function close(): void;
}
