---
type: Trap
title: Fabricate Circuits leftovers
description: Circuits moved to GeneralPurposeIO — Fabricate Circuits FQCNs are stale for 0.7 gpio + robotics.
tags: [traps, fabricate, circuits]
generated: { by: cursor-agent/grok-4.5, at: "2026-08-10T22:45:00Z" }
status: draft
---

# Trap

Do **not** import:

- `Fabricate\Contracts\Circuits\*`
- `Fabricate\Circuits\DataRegister`
- `Fabricate\NutsAndBolts\MagicAliases\Circuit` / `Fabricate\MagicAliases\Circuit` from Nab defaults

Use:

- `GeneralPurposeIO\Contracts\Circuits\*`
- `GeneralPurposeIO\Circuits\DataRegister`
- `GeneralPurposeIO\Core\MagicAliases\Circuit` (alias `Circuit`)

# Related

* [Circuits](../core/circuits.md)
* [IC package ownership](../conventions/ic-package-ownership.md)
