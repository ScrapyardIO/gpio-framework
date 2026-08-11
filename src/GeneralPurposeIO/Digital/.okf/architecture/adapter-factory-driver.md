---
type: Architecture
title: Adapter → factory → driver
description: "Call stack: DigitalIO MagicAlias → gpio.digital-io manager → adapter → factory → bus/driver/pins."
resource: DigitalIO.php
tags: [architecture, digital-io, adapter, factory, driver, fabricates]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:52:00Z" }
status: draft
sources:
  - id: digital-io
    resource: DigitalIO.php
    title: MagicAlias accessor gpio.digital-io
  - id: provider
    resource: DigitalIOServiceProvider.php
    title: Singleton register + adapter boot + GPIO::extend
  - id: manager
    resource: DigitalIOAdapterManager.php
    title: adapter() resolves drivers from config default
  - id: posix-adapter
    resource: Adapters/PosixDigitalIOAdapter.php
    title: device(int) → PosixDigitalIOFactory
  - id: mpsse-adapter
    resource: Adapters/MpsseDigitalIOAdapter.php
    title: device(...) → MpsseDigitalIOFactory
  - id: posix-factory
    resource: Factory/PosixDigitalIOFactory.php
    title: bus() / driver() open gpiochip via gpiod_*
  - id: bus
    resource: Bus/PosixDigitalIOBus.php
    title: input() / output() pin factories
  - id: readme
    resource: README.md
    title: Usage sketch and package layout
---

# Call stack

```
DigitalIO (MagicAlias)
  └─ app('gpio.digital-io')  →  DigitalIOAdapterManager
       └─ adapter($name)     →  PosixDigitalIOAdapter | MpsseDigitalIOAdapter
            └─ device(...)   →  PosixDigitalIOFactory | MpsseDigitalIOFactory
                 ├─ driver() →  PosixDigitalIODriver | UsbDigitalIODriver
                 └─ bus()    →  PosixDigitalIOBus | UsbDigitalIOBus
                      ├─ input($pin, ...)  → DigitalInputPin
                      └─ output($pin)      → DigitalOutputPin
```

Container registration:[^provider][^digital-io]

| Key | Binding |
|-----|---------|
| `gpio.digital-io` | Singleton `DigitalIOAdapterManager` |
| Alias | `DigitalIOAdapterManager::class` |
| MagicAlias | `DigitalIO` → `gpio.digital-io` |
| Top-level GPIO | `GPIO::extend('digital-io', …)` in provider `boot()` |

# Config hooks

- Default adapter: `config('gpio.protocols.digital-io.default')`[^manager]
- Adapter map: `config('gpio.protocols.digital-io.adapters')` — provider `boot()` calls `DigitalIO::extend($name, fn () => new $class())` for each entry[^provider]

Unresolved adapter names throw `GPIOException` asking whether the name is defined in that config map.[^manager]

# Adapter → factory

| Adapter | `device(...)` | Factory |
|---------|---------------|---------|
| `PosixDigitalIOAdapter` | `int` chip index; requires `/dev/gpiochip{N}` | `PosixDigitalIOFactory`[^posix-adapter] |
| `MpsseDigitalIOAdapter` | `string\|MpsseSupportedDevice` | `MpsseDigitalIOFactory`[^mpsse-adapter] |

Factories expose `driver()` and `bus()`. Pin I/O is created on the bus (`input` / `output`), not as top-level factory shortcuts in source.[^posix-factory][^bus]

# Example (POSIX)

```php
use GeneralPurposeIO\Digital\DigitalIO;

$factory = DigitalIO::adapter('posix')->device(0); // /dev/gpiochip0
$output = $factory->bus()->output(17);
$output->write(true);
```

README may show a shorter `$factory->output(17)` sketch; the implemented path is `bus()->output(...)`.[^readme][^bus]

# Related

* [Package (0.7)](../orientation/package.md)
* [Pair with microscrap](../orientation/pairing-microscrap.md)
* [Missing microscrap runtime](../traps/missing-microscrap-runtime.md)

[^digital-io]: MagicAlias accessor gpio.digital-io
[^provider]: Singleton register + adapter boot + GPIO::extend
[^manager]: adapter() resolves drivers from config default
[^posix-adapter]: device(int) → PosixDigitalIOFactory
[^mpsse-adapter]: device(...) → MpsseDigitalIOFactory
[^posix-factory]: bus() / driver() open gpiochip via gpiod_*
[^bus]: input() / output() pin factories
[^readme]: Usage sketch and package layout
