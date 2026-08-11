---
type: Trap
title: Missing microscrap runtime
description: "PosixDigitalIOAdapter requires gpiod_* (microscrap/gpio); MpsseDigitalIOAdapter requires mpsse_open + ext-ftdi."
resource: Adapters/PosixDigitalIOAdapter.php
tags: [trap, microscrap, runtime, posix, mpsse]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:52:00Z" }
status: draft
sources:
  - id: posix-adapter
    resource: Adapters/PosixDigitalIOAdapter.php
    title: confirmDependencies checks gpiod_chip_open
  - id: mpsse-adapter
    resource: Adapters/MpsseDigitalIOAdapter.php
    title: confirmDependencies checks ext-ftdi and mpsse_open
  - id: composer
    resource: composer.json
    title: microscrap packages are suggest, not require
  - id: agents
    resource: AGENTS.md
    title: POSIX/MPSSE runtime dependency notes
  - id: readme
    resource: README.md
    title: Runtime requirements for adapters
---

# Symptom

Using the Posix or MPSSE Digital IO adapter fails at dependency confirmation even though `gpio/digital` (or gpio-framework) is installed.[^posix-adapter][^mpsse-adapter]

# Why

`microscrap/gpio` and `microscrap/mpsse` are **Composer suggests**, not hard requires.[^composer] Adapters probe for native/helper entrypoints at runtime:

| Adapter | Check | Failure message (essence) |
|---------|-------|---------------------------|
| Posix | `function_exists('gpiod_chip_open')` | Require `microscrap/gpio`[^posix-adapter] |
| Posix | Also `ConfirmPOSIXDependencies::run('DigitalIO')` | POSIX stack incomplete[^posix-adapter] |
| MPSSE | `extension_loaded('ftdi')` | Install `php-io-extension/ftdi` (ext-ftdi)[^mpsse-adapter] |
| MPSSE | `function_exists('mpsse_open')` | Require `microscrap/mpsse`[^mpsse-adapter] |

# Fix

```bash
# POSIX Digital IO
composer require microscrap/gpio:^0.7

# USB MPSSE Digital IO
composer require microscrap/mpsse:^0.7
# plus ext-ftdi (e.g. pie install php-io-extension/ftdi)
```

Also ensure `/dev/gpiochip*` exists for Posix `device($n)` and that the process can open it.[^readme]

# Do not

- Do not copy or reimplement `gpiod_*` helpers inside this package — that belongs in `microscrap/gpio`.[^agents]
- Do not treat a successful Composer install of `gpio/digital` alone as proof the Posix adapter can run.

# Related

* [Pair with microscrap](../orientation/pairing-microscrap.md)
* [Adapter → factory → driver](../architecture/adapter-factory-driver.md)

[^posix-adapter]: confirmDependencies checks gpiod_chip_open
[^mpsse-adapter]: confirmDependencies checks ext-ftdi and mpsse_open
[^composer]: microscrap packages are suggest, not require
[^agents]: POSIX/MPSSE runtime dependency notes
[^readme]: Runtime requirements for adapters
