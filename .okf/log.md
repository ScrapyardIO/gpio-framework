# Directory Update Log

## 2026-08-11

* **Docs/CI**: Root README (tests badge, ecosystem docs URL, general-purpose-io subtree table); `.github/workflows/tests.yml` + `phpunit.xml`; website 0.7.x page set restructured (Basics / Components / Diving Deeper).
* **About ICs**: `CircuitServiceProvider` adds Workshop `about` section **Integrated Circuits** — catalog slug → `#[IntegratedCircuit]` option labels (`CircuitCatalogAboutRows`). Not profiles.

## 2026-08-10

* **Profile prompts**: `CircuitProfileParamPrompter` asks driver/device/pins from `#[Pinout]` channels (`CircuitTransport` param prefixes); writer emits full params + `boot_now`.
* **Profile tooling**: Publishable `gpio-circuits-config` Profile Definitions stub; `circuit:make-profile` + `registerProfileCommand` delegation; `#[IntegratedCircuit]`/`#[Pinout]` inspector; ST77xx `st77xx:make-profile` + `st77xx-smoke` sketch.
* **Circuits fluent/profile**: Catalog (`addCircuit`) vs `PendingCircuit` fluent (`ic()->…->make()`) vs named `profile()` recipes with `ic` key. Removed `Circuit::driver` slug==config. Device always explicit. Updated `core/circuits.md`, IC ownership convention, AGENTS.
* **Creation**: Initial `.okf` for gpio-framework 0.7 — Circuits port (registry, taxonomy, contracts), protocols/About note, IC ownership convention, Fabricate Circuits leftover trap.
