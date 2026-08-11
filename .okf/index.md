---
okf_version: "0.2"
---

# scrapyard-io/gpio-framework Knowledge Bundle

Package knowledge for `scrapyard-io/gpio-framework` (GeneralPurposeIO, v0.7.x).
Read this index first; open only the concepts needed for the task.

**Trust rule:** Prefer `status: stable`. Treat `deprecated` as historical only. New agent-written concepts stay `status: draft` until a human verifies them.
**Placement:** Package-root `.okf/` only — never under `src/GeneralPurposeIO/*`.
**Links:** Concept cross-links use paths relative to each file.
**Scope:** Protocol transports + Circuits registry/taxonomy. Chip drivers live in `dept-of-scrapyard-robotics/*`. Waveforms attributes for dual-purpose IC discrimination are deferred to `scrapyard-io/waveforms`.
**Dist note:** `.okf/` and root `AGENTS.md` are `export-ignore` in `.gitattributes`.

# Orientation

* [Package (0.7)](orientation/package.md) - Composer identity, namespaces, discovery.
* [Ecosystem docs](orientation/ecosystem-docs.md) - Published 0.7.x site entrypoint.

# Core

* [Circuits](core/circuits.md) - Catalog, PendingCircuit fluent, named profiles, taxonomy.
* [GPIO protocols](core/gpio-protocols.md) - Protocol managers + About inventory.

# Conventions

* [IC package ownership](conventions/ic-package-ownership.md) - Robotics ICs extend taxonomy; register via Circuit::addCircuit.
* [Component subtree packaging](conventions/component-subtree-packaging.md) - gpio/* replace map.

# Traps

* [Fabricate Circuits leftovers](traps/fabricate-circuits-leftovers.md) - Do not import Fabricate\Contracts\Circuits or Fabricate\Circuits\DataRegister.

# Log

* [Directory update log](log.md)
