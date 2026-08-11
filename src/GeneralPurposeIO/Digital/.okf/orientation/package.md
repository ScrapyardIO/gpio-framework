---
type: Orientation
title: Package (0.7)
description: "gpio/digital 0.7.0 — Fabricate-aware Digital IO protocol (MagicAlias, ServiceProvider, adapters); not microscrap/gpio bindings."
resource: .
tags: [orientation, gpio, digital-io, general-purpose-io, 0.7]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:52:00Z" }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: Package name, version, requires, suggest, namespace
  - id: readme
    resource: README.md
    title: Package README (role, layout, usage)
  - id: agents
    resource: AGENTS.md
    title: Agent rules for this package
  - id: digital-io
    resource: DigitalIO.php
    title: MagicAlias accessor gpio.digital-io
  - id: provider
    resource: DigitalIOServiceProvider.php
    title: Registers manager and boots adapters
---

# What it is

Composer package `gpio/digital` at **0.7.0** — Fabricate-aware **Digital IO** protocol for ScrapyardIO under `GeneralPurposeIO\Digital\`. Ships MagicAlias `DigitalIO`, `DigitalIOServiceProvider`, adapter manager, POSIX / MPSSE adapters, factories, buses, drivers, and pin objects.[^readme][^composer][^agents]

| Field | Value |
|-------|-------|
| Name | `gpio/digital`[^composer] |
| Version | `0.7.0`[^composer] |
| PHP | `^8.4\|^8.5\|^8.6`[^composer] |
| Namespace | `GeneralPurposeIO\Digital\` → package root[^composer] |
| Require | `gpio/common` `0.7.0`, `gpio/contracts` `0.7.0`[^composer] |
| Suggest | `microscrap/gpio` `^0.7`, `microscrap/mpsse` `^0.7`, `scrapyard-io/gpio-framework` `^0.7`[^composer] |
| Homepage / docs | gpio-framework ecosystem overview (see [Ecosystem docs](ecosystem-docs.md))[^readme][^composer] |
| Container key | `gpio.digital-io` → `DigitalIOAdapterManager`[^digital-io][^provider] |
| Role | Protocol layer (alias + provider + adapters / factories / drivers / buses / pins)[^agents][^readme] |

Usually installed via [`scrapyard-io/gpio-framework`](https://github.com/scrapyard-io/gpio-framework), which **replaces** `gpio/digital` at the same version.[^readme] See [Metapackage replace](../conventions/metapackage-replace.md).

# What it is not

- **Not** [`microscrap/gpio`](https://github.com/microscrap/gpio) — that package is libgpiod-shaped **bindings** (`gpiod_*` helpers). Digital *uses* those helpers when the Posix adapter runs.[^agents][^readme]
- Not a place to invent parallel Microscrap APIs or reshape this tree into a gpiod helper library.[^agents]
- Not a standalone ecosystem docs family yet — published narrative docs ride on **gpio-framework** (see [Ecosystem docs](ecosystem-docs.md)).

# Public surface (summary)

| Area | Role |
|------|------|
| `DigitalIO` | MagicAlias → `gpio.digital-io`[^digital-io] |
| `DigitalIOServiceProvider` | Registers manager; boots adapters from `config('gpio.protocols.digital-io.adapters')`; extends top-level `GPIO` with `digital-io`[^provider] |
| `Adapters/` | `PosixDigitalIOAdapter`, `MpsseDigitalIOAdapter` |
| `Factory/` | Chip / device factories |
| `Drivers/` | Line read / write / edge listen |
| `Bus/` | Digital IO buses (`input` / `output` pin factories) |
| `DigitalInputPin` / `DigitalOutputPin` | Pin objects |

# Related

| Topic | Concept |
|-------|---------|
| Call stack | [Adapter → factory → driver](../architecture/adapter-factory-driver.md) |
| Stack vs microscrap | [Pair with microscrap](pairing-microscrap.md) |
| Install path | [Metapackage replace](../conventions/metapackage-replace.md) |
| Runtime deps | [Missing microscrap runtime](../traps/missing-microscrap-runtime.md) |
| Docs site | [Ecosystem docs](ecosystem-docs.md) |

[^composer]: Package name, version, requires, suggest, namespace
[^readme]: Package README (role, layout, usage)
[^agents]: Agent rules for this package
[^digital-io]: MagicAlias accessor gpio.digital-io
[^provider]: Registers manager and boots adapters
