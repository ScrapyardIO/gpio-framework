---
type: Module
title: GPIO protocols
description: Protocol adapter managers under gpio.protocols and Workshop about GPIO section.
resource: src/GeneralPurposeIO/Core/GPIOServiceProvider.php
tags: [gpio, protocols, about]
generated: { by: cursor-agent/grok-4.5, at: "2026-08-10T22:45:00Z" }
status: draft
sources:
  - id: provider
    resource: src/GeneralPurposeIO/Core/GPIOServiceProvider.php
    title: GPIOServiceProvider
  - id: config
    resource: config/gpio.php
    title: gpio.php
---

# Role

Protocol transports (digital-io, i2c, spi, uart, pwm, analog) live beside Circuits. `GPIOServiceProvider` aggregates protocol providers **and** `CircuitServiceProvider`.

# About

`GPIOServiceProvider` contributes Workshop `about` section **GPIO** via microscrap `InstalledVersions` probes + `/sys/class/pwm` for native PWM.

Catalog ICs are a separate About section owned by Circuits — see [Circuits](circuits.md)#about.

# Related

* [Circuits](circuits.md)
* [Package (0.7)](../orientation/package.md)
