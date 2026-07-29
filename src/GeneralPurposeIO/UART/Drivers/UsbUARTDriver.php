<?php

namespace GeneralPurposeIO\UART\Drivers;

use Ftdi\FTDIContext;

class UsbUARTDriver extends UARTDriver
{
    public function __construct(
        protected readonly FTDIContext $context,
    ) {}

    public function read(int $length): array|false
    {
        $data = ftdi_read_data($this->context, $length);

        return empty($data) ? false : bytes2array($data);
    }

    public function write(array|string $data): int
    {
        $data = static::normalizeData($data);

        return ftdi_write_data($this->context, $data, strlen($data));
    }

    public function flush(): void
    {
        ftdi_usb_purge_buffers($this->context);
    }

    public function close(): void
    {
        ftdi_usb_close($this->context);
        ftdi_deinit($this->context);
        ftdi_free($this->context);
    }

    public function getContext(): FTDIContext
    {
        return $this->context;
    }
}
