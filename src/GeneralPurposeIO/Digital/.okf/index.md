---
okf_version: "0.2"
---

# gpio/digital Knowledge Bundle

Package knowledge for `gpio/digital` (GeneralPurposeIO Digital IO protocol, v0.7.0).
Read this index first; open only the concepts needed for the task.

**Trust rule:** Prefer `status: stable`. Treat `deprecated` as historical only. New agent-written concepts stay `status: draft` until a human verifies them.
**Placement:** This bundle lives at the **package root** only — never nested under `Adapters/`, `Factory/`, or other component folders.
**Links:** Concept cross-links use paths relative to each file.
**Scope:** Document the Fabricate-aware Digital IO protocol (`DigitalIO` MagicAlias, ServiceProvider, adapters / factories / drivers / buses / pins). Do **not** reshape this into a `microscrap/gpio` gpiod-helpers OKF.
**Dist note:** `.okf/` and root `AGENTS.md` are `export-ignore` in `.gitattributes` so Composer dist packages do not ship this bundle.

# Orientation

* [Package (0.7)](orientation/package.md) - Composer identity, namespace, role vs microscrap bindings.
* [Ecosystem docs](orientation/ecosystem-docs.md) - Docs via gpio-framework overview (no standalone digital slug yet).
* [Pair with microscrap](orientation/pairing-microscrap.md) - Protocol above; microscrap/gpio and mpsse below at runtime.

# Architecture

* [Adapter → factory → driver](architecture/adapter-factory-driver.md) - `DigitalIO` → manager → adapter → factory → bus/driver/pins; container key `gpio.digital-io`.

# Conventions

* [Metapackage replace](conventions/metapackage-replace.md) - Prefer `scrapyard-io/gpio-framework`; split `gpio/digital` is advanced.

# Traps

* [Missing microscrap runtime](traps/missing-microscrap-runtime.md) - Posix adapter needs `gpiod_*` from `microscrap/gpio`; MPSSE needs `microscrap/mpsse`.

# Log

* [Directory update log](log.md)
