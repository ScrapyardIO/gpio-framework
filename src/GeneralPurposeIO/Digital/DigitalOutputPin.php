<?php

namespace GeneralPurposeIO\Digital;

use GeneralPurposeIO\Contracts\Digital\DigitalIODriver;

class DigitalOutputPin
{
    public function __construct(
        public $pin,
        protected DigitalIODriver $driver,
    ) {}

    public function read(): bool
    {
        return $this->driver->read($this->pin);
    }

    public function write(bool $value): bool
    {
        return $this->driver->write($this->pin, $value);
    }

    public function high(): bool
    {
        $this->write(true);
        return $this->read();
    }

    public function low(): bool
    {
        $this->write(false);
        return $this->read();
    }

    public function isHigh(): bool
    {
        return $this->read();
    }

    public function isLow(): bool
    {
        return !$this->read();
    }

    public function close(): void
    {
        if (is_object($this->pin) && isset($this->pin->fd) && is_int($this->pin->fd)) {
            posix_close($this->pin->fd);
        }

        $this->driver->close();
    }
}