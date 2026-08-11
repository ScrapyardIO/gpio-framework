---
type: Convention
title: IC package ownership
description: dept-of-scrapyard-robotics packages own chip drivers; extend Circuits taxonomy; register catalog types with Circuit::addCircuit.
tags: [conventions, robotics, ic]
generated: { by: cursor-agent/grok-4.5, at: "2026-08-10T23:40:00Z" }
status: draft
---

# Rule

- Chip drivers → `dept-of-scrapyard-robotics/*` (not gpio-framework).
- On 0.7: extend `GeneralPurposeIO\Circuits\{SensorIC|Actuator|DisplayPanel|…}`.
- Register **catalog types only**: `Circuit::addCircuit($slug, $class)` from the package service provider.
- Optionally register a profile maker: `Circuit::registerProfileCommand($slug, 'pkg:make-profile')` so `circuit:make-profile` can delegate.
- Annotate IC classes with `#[IntegratedCircuit(...)]` protocol options and matching `#[Pinout(...)]` (index-aligned). Pinout roles: `driver`, `device`, `chip_select`, `slave`, and pin names (`dc`, `rst`, …) so `circuit:make-profile` can generate full params.
- Apps / case kits own `config/circuits.php` profiles (`ic` + `protocol` + `params`); do not conflate profile names with catalog slugs.
- Build: `Circuit::ic($slug)->…->make()` or `Circuit::profile($name)`.
- Use `GeneralPurposeIO\Contracts\Circuits\*` — never `Fabricate\Contracts\Circuits\*`.
- Dual-purpose discrimination attributes will ship later on `scrapyard-io/waveforms`.
- Tubes PanelIC / FullColorDisplay integration for display drivers is a separate pass after IC packages are version-promoted.

# Related

* [Circuits](../core/circuits.md)
