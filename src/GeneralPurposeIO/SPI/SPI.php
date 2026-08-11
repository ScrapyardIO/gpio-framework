<?php

namespace GeneralPurposeIO\SPI;

use Fabricate\MagicAliases\MagicAlias;
use GeneralPurposeIO\Contracts\SPI\SPICommunicationAdapter as CommunicationAdapter;

/**
 * @method static void extend(string $name, callable $callback)
 * @method static CommunicationAdapter adapter(string $name)
 */
class SPI extends MagicAlias
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getMagicAliasAccessor(): string
    {
        return 'gpio.spi';
    }
}