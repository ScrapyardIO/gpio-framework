# scrapyard-io/gpio-framework (0.7)

[![Tests](https://github.com/scrapyard-io/gpio-framework/actions/workflows/tests.yml/badge.svg)](https://github.com/scrapyard-io/gpio-framework/actions/workflows/tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/scrapyard-io/gpio-framework.svg)](https://packagist.org/packages/scrapyard-io/gpio-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/scrapyard-io/gpio-framework.svg)](https://packagist.org/packages/scrapyard-io/gpio-framework)
[![License](https://img.shields.io/packagist/l/scrapyard-io/gpio-framework.svg)](LICENSE.md)
[![Docs](https://img.shields.io/badge/docs-ScrapyardIO-0ea5e9?logo=readthedocs&logoColor=white)](https://scrapyard-io.projectsaturnstudios.com/ecosystem/scrapyard-io/gpio-framework/0.7.x/overview)

GPIO protocol adapters + **Circuits** registry for ScrapyardIO framework **0.7**.

## Install

```bash
composer require scrapyard-io/gpio-framework:^0.7.0
php workshop vendor:publish --tag=gpio-circuits-config
```

Discovery registers `GPIOServiceProvider` and MagicAliases `GPIO` / `Circuit`.

## Basics

**Protocol connection**

```php
use GeneralPurposeIO\Core\MagicAliases\GPIO;
use GeneralPurposeIO\I2C\I2C;

$slave = I2C::adapter('posix')->device(1)->bus()->slave(0x3C);
// or via GPIO::protocol('i2c') when configuring adapters from gpio.php
```

**Circuit bootstrap**

```php
use GeneralPurposeIO\Core\MagicAliases\Circuit;

Circuit::addCircuit('aht20', \DeptOfScrapyardRobotics\Sensors\AHTx0\AHT20\AHT20::class);

$sensor = Circuit::ic('aht20')
    ->protocol('i2c')
    ->driver('posix')
    ->device(1)
    ->slave(0x38)
    ->make();

// or a named profile from config/circuits.php
$sensor = Circuit::profile('climate_lab');
```

Scaffold profiles from `#[Pinout]`:

```bash
php workshop circuit:make-profile
```

Workshop `about` lists **GPIO** adapter availability and **Integrated Circuits** catalog options (not profiles).

## Component splits

This umbrella replaces the [general-purpose-io](https://github.com/orgs/general-purpose-io/repositories) read-only subtree packages at `self.version`:

| Composer | Subtree |
|---|---|
| `gpio/common` | [general-purpose-io/common](https://github.com/general-purpose-io/common) |
| `gpio/contracts` | [general-purpose-io/contracts](https://github.com/general-purpose-io/contracts) |
| `gpio/digital` | [general-purpose-io/digital](https://github.com/general-purpose-io/digital) |
| `gpio/i2c` | [general-purpose-io/i2c](https://github.com/general-purpose-io/i2c) |
| `gpio/spi` | [general-purpose-io/spi](https://github.com/general-purpose-io/spi) |
| `gpio/uart` | [general-purpose-io/uart](https://github.com/general-purpose-io/uart) |
| `gpio/pwm` | [general-purpose-io/pwm](https://github.com/general-purpose-io/pwm) |
| `gpio/circuits` | Umbrella `replace` (no public subtree yet) |

Analog scaffolding lives under `GeneralPurposeIO\Analog` in this tree; [general-purpose-io/analog](https://github.com/general-purpose-io/analog) is the read-only mirror when split.

Prefer requiring **`scrapyard-io/gpio-framework`**. The subtree repos stay read-only mirrors of `src/GeneralPurposeIO/*`.

## Carriers

| Path | Packages |
|---|---|
| Native | `microscrap/posix` + `gpio` / `i2c` / `spi` / `uart` + `ext-posi` |
| USB | `microscrap/ftdi` → `mpsse` + `ext-ftdi` |

All `^0.7.0`.

## Tests

```bash
composer update
vendor/bin/phpunit
```

## License

MIT
