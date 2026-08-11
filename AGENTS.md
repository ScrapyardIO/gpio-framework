# Agent guidelines — scrapyard-io/gpio-framework

## Knowledge Bundle (OKF)

This package ships an Open Knowledge Format bundle at [`.okf/`](.okf/) (excluded from Composer dist via `.gitattributes` `export-ignore`).

Before changing gpio-framework code or advising on GPIO / Circuits architecture **for this package**:

1. Read [`.okf/index.md`](.okf/index.md) first (progressive disclosure).
2. Open only the linked concepts needed for the task.
3. Prefer `status: stable` concepts; treat `deprecated` as historical only. New/changed concepts stay `status: draft` until a human verifies them.
4. When you learn something durable about **this package**, update the affected `.okf` concept(s) and append `.okf/log.md`.
5. Keep the `.okf` bundle at the **package root** only — do not nest extra `.okf` folders under `src/GeneralPurposeIO/*`.
6. Framework, tubes, waveforms, microscrap bindings, and dept-of-scrapyard-robotics IC packages keep their own docs / `.okf` bundles.

## Package rules (quick) — 0.7.x

- Composer: `scrapyard-io/gpio-framework` **0.7.0**. PHP `^8.4|^8.5|^8.6`. Namespace `GeneralPurposeIO\` → `src/GeneralPurposeIO/`.
- Discovery: `extra.scrapyard-io.providers` → `GPIOServiceProvider` (aggregates Circuits + protocol providers). Aliases: `GPIO`, `Circuit`.
- Protocols: Digital / I2C / SPI / UART / PWM / Analog under config key `gpio.protocols.*`.
- Circuits: `CircuitRegistry` bound as `circuit`. Catalog: `Circuit::addCircuit($slug, $class)`. Fluent: `Circuit::ic($slug)->protocol()->driver()->device()->…->make()`. Profiles: arbitrary keys in `config/circuits.php` with `ic` + `protocol` + `params`; `Circuit::profile($name)` returns a live instance. Taxonomy markers: `SensorIC`, `Actuator`, `DisplayPanel`, `AnalogIOCircuit`, `SecurityChip`, `StorageDevice`.
- Publish profiles config: `php workshop vendor:publish --tag=gpio-circuits-config`. Scaffold: `php workshop circuit:make-profile` (delegates via `Circuit::registerProfileCommand` to package commands such as `st77xx:make-profile`). Uses `#[IntegratedCircuit]` / `#[Pinout]` — prompts adapter/device/pins per channel; always sets `params.boot_now => true`.
- Contracts for Circuits live under `GeneralPurposeIO\Contracts\Circuits` (not Fabricate).
- Suggests microscrap `^0.7` bindings; native arm64 vs USB ftdi→mpsse.
- Do not put chip drivers in this package — those belong in `dept-of-scrapyard-robotics/*`.
