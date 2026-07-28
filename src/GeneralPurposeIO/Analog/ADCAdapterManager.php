<?php

namespace GeneralPurposeIO\Analog;

use InvalidArgumentException;
use Fabricate\NutsAndBolts\Manager;
use GeneralPurposeIO\Contracts\Common\GPIOException;
use Fabricate\Contracts\Chassis\CircularDependencyException;
use GeneralPurposeIO\Contracts\Analog\AnalogInputCommunicationAdapter as AdapterInterface;
use GeneralPurposeIO\Contracts\Common\GPIOCommunicationAdapterManager as AdapterManager;

class ADCAdapterManager extends Manager implements AdapterManager
{
    /**
     * @throws GPIOException
     */
    public function adapter(?string $adapter = null): AdapterInterface
    {
        try {
            return $this->driver($adapter);
        }
        catch (InvalidArgumentException)
        {
            throw new GPIOException("ADC Adapter [$adapter] not registered. Is it defined in config(gpio.protocols.analog-in.adapters)?");
        }
    }
    /**
     * @throws CircularDependencyException
     */
    public function getDefaultDriver(): ?string
    {
        return config('gpio.protocols.analog-in.default');
    }
}