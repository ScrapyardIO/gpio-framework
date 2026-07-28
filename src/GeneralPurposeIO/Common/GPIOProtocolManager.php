<?php

namespace GeneralPurposeIO\Common;

use Fabricate\Contracts\Chassis\CircularDependencyException;
use RuntimeException;
use ReflectionException;
use Fabricate\Contracts\Core\Program;
use Fabricate\NutsAndBolts\RebindsCallbacksToSelf;
use GeneralPurposeIO\Contracts\Common\GPIOException;
use GeneralPurposeIO\Contracts\Core\GPIOProtocolFactory as FactoryContract;
use GeneralPurposeIO\Contracts\Common\GPIOCommunicationAdapterManager as AdapterManager;

class GPIOProtocolManager implements FactoryContract
{
    use RebindsCallbacksToSelf;

    protected array $protocols = [];

    public function __construct(protected Program $program) {}

    /**
     * @param string $name
     * @return mixed
     * @throws GPIOException
     * @throws CircularDependencyException
     */
    public function protocol(string $name): AdapterManager
    {
        if (! isset($this->protocols[$name])) {
            throw GPIOException::invalidProperty($name, static::class);
        }

        $enabled = config("gpio.protocols.{$name}.enabled", false);
        if(!$enabled)
        {
            throw new GPIOException("Protocol [{$name}] not enabled.");
        }

        return $this->protocols[$name]();
    }

    public function extend(string $name, callable $callback): void
    {
        try {
            $callback = $this->bindCallbackToSelf($callback) ?? throw new RuntimeException('Unable to bind custom driver callback');
        }
        catch (ReflectionException $e) {
            throw new RuntimeException('Unable to bind custom protocol callback', previous: $e);
        }

        $this->protocols[$name] = $callback;
    }

}