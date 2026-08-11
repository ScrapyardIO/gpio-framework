<?php

namespace DeptOfScrapyardRobotics\Tests\Circuits;

use GeneralPurposeIO\Circuits\CircuitRegistry;
use GeneralPurposeIO\Circuits\Support\CircuitCatalogAboutRows;
use GeneralPurposeIO\Contracts\Circuits\Attributes\IntegratedCircuit as IntegratedCircuitAttribute;
use GeneralPurposeIO\Contracts\Circuits\IntegratedCircuit;
use PHPUnit\Framework\TestCase;

#[IntegratedCircuitAttribute(['SPI', 'DigitalIO'], 'SPI', 'DigitalIO')]
class CircuitCatalogAboutRowsDemoIc implements IntegratedCircuit
{
    public function close(): void {}
}

class CircuitCatalogAboutRowsTest extends TestCase
{
    public function testEmptyCatalogYieldsNoRows(): void
    {
        $this->assertSame([], CircuitCatalogAboutRows::for(new CircuitRegistry));
    }

    public function testCatalogSlugMapsToIntegratedCircuitOptionLabels(): void
    {
        $registry = new CircuitRegistry;
        $registry->addCircuit('demo_panel', CircuitCatalogAboutRowsDemoIc::class);

        $rows = CircuitCatalogAboutRows::for($registry);

        $this->assertArrayHasKey('demo_panel', $rows);

        $json = ($rows['demo_panel'])(true);

        $this->assertSame(['SPI+DigitalIO', 'SPI', 'DigitalIO'], $json);

        $console = ($rows['demo_panel'])(false);

        $this->assertStringContainsString('SPI+DigitalIO', $console);
        $this->assertStringContainsString('SPI', $console);
        $this->assertStringContainsString('DigitalIO', $console);
    }
}
