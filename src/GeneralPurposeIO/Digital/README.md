# gpio/digital — GeneralPurposeIO Digital IO

> **Docs (production):** [ScrapyardIO · gpio-framework · Digital](https://scrapyard-io.projectsaturnstudios.com/ecosystem/scrapyard-io/gpio-framework/0.7.x/digital)

[![Docs](https://img.shields.io/badge/docs-ScrapyardIO-0ea5e9?logo=readthedocs&logoColor=white)](https://scrapyard-io.projectsaturnstudios.com/ecosystem/scrapyard-io/gpio-framework/0.7.x/digital)
[![License: MIT](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE.md)

Fabricate-aware **Digital IO** protocol package for ScrapyardIO: magic alias `DigitalIO`, adapter manager, POSIX / MPSSE factories, buses, and pin objects under `GeneralPurposeIO\Digital\`.

This is **not** the Microscrap gpiod bindings package (`microscrap/gpio`). Digital sits in **gpio-framework** and *uses* Microscrap adapters when present.

Composer: **`gpio/digital` `0.7.0`**. Usually installed via [`scrapyard-io/gpio-framework`](https://github.com/scrapyard-io/gpio-framework) (`replace` → `gpio/digital` at the same version).

## Requirements

* PHP `^8.4|^8.5|^8.6`
* **gpio/common** `0.7.0`
* **gpio/contracts** `0.7.0`
* For POSIX adapter runtime: **microscrap/gpio** `^0.7` (+ **ext-posi** / **microscrap/posix**)
* For MPSSE/USB adapter runtime: **microscrap/mpsse** `^0.7`

## Installation

Prefer the metapackage:

```bash
composer require scrapyard-io/gpio-framework:^0.7.0
```

Split require (advanced):

```bash
composer require gpio/digital:^0.7.0
```

## Usage

```php
use GeneralPurposeIO\Digital\DigitalIO;

// Adapter name from config/gpio.php (default often "posix")
$factory = DigitalIO::adapter('posix')->device(0); // /dev/gpiochip0
$output = $factory->output(17);
$output->write(true);
```

Providers register `gpio.digital-io` and extend the top-level `GPIO` magic alias with `digital-io`.

## Package layout

| Area | Role |
|------|------|
| `DigitalIO` | Magic alias → `gpio.digital-io` |
| `DigitalIOServiceProvider` | Registers manager; boots adapters from config |
| `Adapters/` | `PosixDigitalIOAdapter`, `MpsseDigitalIOAdapter` |
| `Factory/` | Chip/device factories |
| `Drivers/` | Line read/write / edge listen |
| `Bus/` | Digital IO buses |
| `DigitalInputPin` / `DigitalOutputPin` | Pin objects |

## Related

* [`scrapyard-io/gpio-framework`](https://scrapyard-io.projectsaturnstudios.com/ecosystem/scrapyard-io/gpio-framework/0.6.x/overview) — kitchen-sink host
* [`microscrap/gpio`](https://scrapyard-io.projectsaturnstudios.com/ecosystem/microscrap/gpio/0.7.x/overview) — POSIX gpiod bindings
* [`microscrap/mpsse`](https://scrapyard-io.projectsaturnstudios.com/ecosystem/microscrap/mpsse) — USB MPSSE helpers

## License

MIT. See [LICENSE.md](LICENSE.md).
