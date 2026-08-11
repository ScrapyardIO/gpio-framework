# AGENTS.md — gpio/digital

**Always read `.okf/index.md` first** before changing this package. Open only the concepts needed for the task; prefer `status: stable` when present. When you learn a durable package fact, update `.okf/` and append `.okf/log.md`.

## Role

GeneralPurposeIO **Digital IO** protocol package (`gpio/digital`). Magic alias + ServiceProvider + adapters/factories/drivers/buses. Replaced by `scrapyard-io/gpio-framework` at the same version when the metapackage is installed.

**Not** `microscrap/gpio` (bindings). Do not reshape this package into a gpiod helper library.

## Rules

* Keep Fabricate 0.7 namespace imports aligned with sibling gpio-framework components (`MagicAlias`, `DeferrableProvider`, `$this->container`, …).
* POSIX adapter depends on `microscrap/gpio` helpers at runtime; MPSSE adapter depends on `microscrap/mpsse`.
* Prefer `is_null($var)` over `$var === null`.
* No class-level constants; use backed enums (FULLY UPPERCASE) in Contracts when adding new ones.
* Do not invent parallel Microscrap APIs here.

## Quick OKF map

| Need | Concept |
|------|---------|
| Identity / scope | `.okf/orientation/package.md` |
| Docs site | `.okf/orientation/ecosystem-docs.md` |
| Stack vs microscrap | `.okf/orientation/pairing-microscrap.md` |
| Call stack | `.okf/architecture/adapter-factory-driver.md` |
| Metapackage replace | `.okf/conventions/metapackage-replace.md` |
| Missing microscrap | `.okf/traps/missing-microscrap-runtime.md` |
