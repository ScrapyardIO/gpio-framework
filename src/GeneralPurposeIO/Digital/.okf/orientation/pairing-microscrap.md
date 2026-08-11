---
type: Orientation
title: Pair with microscrap
description: "gpio/digital is protocol above; microscrap/gpio and microscrap/mpsse supply runtime bindings below — do not merge the layers."
resource: .
tags: [orientation, gpio, digital-io, microscrap, composition]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:52:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Not microscrap/gpio; uses Microscrap adapters when present
  - id: agents
    resource: AGENTS.md
    title: Boundary vs bindings; Posix/MPSSE runtime deps
  - id: composer
    resource: composer.json
    title: require gpio/*; suggest microscrap packages
  - id: posix-adapter
    resource: Adapters/PosixDigitalIOAdapter.php
    title: Requires gpiod_chip_open from microscrap/gpio
  - id: mpsse-adapter
    resource: Adapters/MpsseDigitalIOAdapter.php
    title: Requires mpsse_open and ext-ftdi
---

# Composition boundary

`gpio/digital` is the **Fabricate Digital IO protocol** — MagicAlias, ServiceProvider, adapters, factories, drivers, buses, pins. It is **not** the Microscrap bindings package.[^agents][^readme]

| Concern | Package |
|---------|---------|
| Shared GPIO helpers / ConfirmPOSIX | `gpio/common` `0.7.0` (**required**)[^composer] |
| Contracts / exceptions / enums | `gpio/contracts` `0.7.0` (**required**)[^composer] |
| Digital IO protocol (this package) | `gpio/digital` `0.7.0` |
| Kitchen-sink host | `scrapyard-io/gpio-framework` `^0.7` (**replaces** this package) |
| POSIX gpiod bindings | `microscrap/gpio` `^0.7` (**below** — suggested; Posix adapter runtime)[^composer][^posix-adapter] |
| USB MPSSE helpers | `microscrap/mpsse` `^0.7` (**below** — suggested; MPSSE adapter runtime)[^composer][^mpsse-adapter] |
| Native FTDI | `ext-ftdi` / `php-io-extensions/ftdi` (MPSSE path)[^mpsse-adapter] |

# Typical flow

1. Prefer `composer require scrapyard-io/gpio-framework:^0.7.0` (or split-require this package for advanced installs).
2. Ensure the chosen adapter’s Microscrap peer is present (`microscrap/gpio` for Posix; `microscrap/mpsse` + `ext-ftdi` for USB).
3. Resolve via `DigitalIO::adapter(...)` → `device(...)` → factory → `bus()` / `driver()` → pins — do not invent `gpiod_*` helpers inside this package.[^agents]

# Caveats

- Missing Microscrap at runtime fails loudly in adapter `confirmDependencies()` — see [Missing microscrap runtime](../traps/missing-microscrap-runtime.md).
- Do not document or implement this package as if it were `microscrap/gpio`.[^agents]

# Related

* [Package (0.7)](package.md)
* [Adapter → factory → driver](../architecture/adapter-factory-driver.md)
* [Metapackage replace](../conventions/metapackage-replace.md)

[^readme]: Not microscrap/gpio; uses Microscrap adapters when present
[^agents]: Boundary vs bindings; Posix/MPSSE runtime deps
[^composer]: require gpio/*; suggest microscrap packages
[^posix-adapter]: Requires gpiod_chip_open from microscrap/gpio
[^mpsse-adapter]: Requires mpsse_open and ext-ftdi
