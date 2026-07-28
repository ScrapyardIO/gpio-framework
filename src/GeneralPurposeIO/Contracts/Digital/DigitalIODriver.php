<?php

namespace GeneralPurposeIO\Contracts\Digital;

use GeneralPurposeIO\Digital\DigitalEdgeEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineRequest;

interface DigitalIODriver
{
    public function read($pin): bool;
    public function write($pin, bool $state): bool;
    public function listen(int $timeout, bool $rising_events, bool $falling_events, $pin): ?DigitalEdgeEvent;
    public function close(): void;
}