<?php

namespace GeneralPurposeIO\UART\Adapters;

use GeneralPurposeIO\Common\ConfirmPOSIXDependencies;
use GeneralPurposeIO\Contracts\Common\GPIOException;
use GeneralPurposeIO\UART\UARTCommunicationAdapter;

class PosixUARTAdapter extends UARTCommunicationAdapter
{
    /**
     * @throws GPIOException
     */
    protected function confirmDependencies(): void
    {
        ConfirmPOSIXDependencies::run('UART');

        if (!function_exists('uart_open')) {
            throw new GPIOException('The UART POSIX adapter requires the UART package. Require it with composer require microscrap/uart');
        }
    }
}