<?php

namespace GeneralPurposeIO\PWM;

use Fabricate\Contracts\Chassis\CircularDependencyException;
use Fabricate\NutsAndBolts\Manager;
use GeneralPurposeIO\Contracts\Common\GPIOException;
use GeneralPurposeIO\Contracts\PWM\PWMCommunicationAdapter as AdapterInterface;
use GeneralPurposeIO\Contracts\Common\GPIOCommunicationAdapterManager as AdapterManager;
use InvalidArgumentException;

class PWMAdapterManager extends Manager implements AdapterManager
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
            throw new GPIOException("PWM Adapter [$adapter] not registered. Is it defined in config(gpio.protocols.pwm.adapters)?");
        }
    }
    /**
     * @throws CircularDependencyException
     */
    public function getDefaultDriver(): ?string
    {
        return config('gpio.protocols.pwm.default');
    }
}