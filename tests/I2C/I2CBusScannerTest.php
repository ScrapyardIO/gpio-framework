<?php

namespace DeptOfScrapyardRobotics\Tests\I2C;

use GeneralPurposeIO\I2C\Drivers\I2CDriver;
use GeneralPurposeIO\I2C\I2CBusScanner;
use PHPUnit\Framework\TestCase;

class I2CBusScannerTest extends TestCase
{
    public function testRendersI2cdetectStyleGrid(): void
    {
        $driver = new class extends I2CDriver
        {
            public function close(): void {}

            public function probe(int $address): bool
            {
                return in_array($address, [0x3c, 0x38], true);
            }

            public function read(int $address, int $len): array|false
            {
                return false;
            }

            public function write(int $address, array|string $data): int
            {
                return 0;
            }

            public function writeRead(int $address, array|string $bytes_to_write, int $bytes_to_read): array|false
            {
                return false;
            }

            public function bulkWrite(int $address, array|string $messages): array|false
            {
                return false;
            }
        };

        $output = (new I2CBusScanner($driver))->render();

        $this->assertStringContainsString('     0  1  2  3  4  5  6  7  8  9  a  b  c  d  e  f', $output);
        $this->assertStringContainsString('30:', $output);
        $this->assertStringContainsString('3c ', $output);
        $this->assertStringContainsString('38 ', $output);
        $this->assertStringContainsString('-- ', $output);
        $this->assertMatchesRegularExpression('/^00: {9}/m', $output);
    }
}
