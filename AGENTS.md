# AGENTS.md — microscrap/gpio

**Always read `.okf/index.md` first** before changing this package. Open only the concepts needed for the task; prefer `status: stable` when present. When you learn a durable package fact, update `.okf/` and append `.okf/log.md`.

## Role

Bindings-only Composer package over **ext-posi** + `microscrap/posix` (`^0.8.0`). Global `gpiod_*` helpers, facades under `Microscrap\Bindings\GPIO`, enums, and data objects mirroring libgpiod v2 / GPIO uAPI v2. No ServiceProvider, no Chassis/Core coupling, no Fabricate remaps.

## Rules

* Helpers delegate to package facades (`Chip`, `LineRequest`, …) which use posix / posi for FD and ioctl work — do not invent parallel APIs.
* Keep 1:1 coverage with helpers already in `src/Helpers/`; document drift in README / ecosystem docs.
* Enums in `src/Enums/*` are int-backed with **FULLY UPPERCASE** cases.
* Prefer `is_null($var)` over `$var === null`.
* No class-level constants; no Fabricate remaps in this package.
* Suggested peer only: `scrapyard-io/gpio-framework` — do not pull framework internals into this package.

## Quick OKF map

| Need | Concept |
|------|---------|
| Identity / scope | `.okf/orientation/package.md` |
| Docs site | `.okf/orientation/ecosystem-docs.md` |
| Call stack | `.okf/architecture/helpers-gpiod-posi.md` |
| Wrap rules | `.okf/conventions/one-to-one-libgpiod-wrap.md` |
| Enums | `.okf/conventions/enums-gpiod.md` |
| Peer stack | `.okf/orientation/pairing-protocol-peers.md` |
| Permissions | `.okf/traps/gpio-device-permissions.md` |
| Helper clash | `.okf/traps/function-exists-load-order.md` |
