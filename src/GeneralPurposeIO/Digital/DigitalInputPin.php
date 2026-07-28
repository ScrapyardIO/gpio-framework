<?php

namespace GeneralPurposeIO\Digital;

use GeneralPurposeIO\Contracts\Digital\DigitalIODriver;

class DigitalInputPin
{
    public function __construct(
        public $pin,
        protected DigitalIODriver $driver,
    ) {}

    public function read(): bool
    {
        return $this->driver->read($this->pin);
    }

    public function isHigh(): bool
    {
        return $this->read();
    }

    public function isLow(): bool
    {
        return !$this->read();
    }

    public function listen(bool $rising_events = false, bool $falling_events = false, int $timeout_ms = 1000): ?DigitalEdgeEvent
    {
        return $this->driver->listen($timeout_ms, $rising_events, $falling_events, $this->pin);
    }

    public function close(): void
    {
        if (is_object($this->pin) && isset($this->pin->fd) && is_int($this->pin->fd)) {
            posix_close($this->pin->fd);
        }

        $this->driver->close();
    }
}