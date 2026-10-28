## 2026-10-28

* **Version**: 0.11.0 with the Venusian 0.11 ecosystem: `version` 0.11.0, `dev-main` → `0.11.x-dev`, `ext-posi` `^0.11.0`, PHP `~8.4.27||^8.5.12`, docs URLs `0.11.x`, `scrapyard-io/framework` `^0.11.0`. `gpioMiscAPIVersion()` returns 0.11.0. README, AGENTS and the bundle relabelled; CI installs `posi` `^0.11` where it has a workflow. No API change. 5 passed on Homebrew PHP 8.4 NTS and ZTS and on the Pi (8.4 ZTS).

## 2026-10-03
* **Update**: 0.10.0 port. ext-posi 0.10 is a C rewrite exposing global functions; `Posi\System` is gone and `microscrap/posix` is absorbed. `Chip` and `LineRequest` call `posix_open` / `posix_close` / `posix_read` / `posix_ppoll` directly; chips open with `O_RDWR | O_CLOEXEC` constants. Requires only `ext-posi` `^0.10.0`, version 0.10.0, branch alias `0.10.x-dev`, suggest `scrapyard-io/framework` `^0.10.0`, docs URLs `0.10.x`. README, AGENTS, [Package (0.10)](/orientation/package.md), [Ecosystem docs](/orientation/ecosystem-docs.md), [Pair with protocol peers](/orientation/pairing-protocol-peers.md), [Enums for gpiod](/conventions/enums-gpiod.md), [Helpers → gpiod facades → posi](/architecture/helpers-gpiod-posi.md) relabelled. Package enums unchanged. No public helper change.

## 2026-09-14
* **Update**: relabeled 0.7.0 → 0.8.0 with `ext-posi` / `ext-ftdi` 0.8.0. No code change.

# Log

## 2026-08-10

* **Creation**: Initial OKF v0.2 bundle for `microscrap/gpio` 0.7.0 from package sources + `okf/SPEC.md` (GoogleCloudPlatform/knowledge-catalog).
* **Creation**: [Package (0.7)](/orientation/package.md), [Ecosystem docs](/orientation/ecosystem-docs.md), [Pair with protocol peers](/orientation/pairing-protocol-peers.md).
* **Creation**: [Helpers → gpiod facades → posix/posi](/architecture/helpers-gpiod-posi.md) — helpers → package facades (`Chip`, `LineRequest`, …) → `posix_*` / `Posi\System` ioctl path.
* **Creation**: Conventions — [1:1 libgpiod wrap](/conventions/one-to-one-libgpiod-wrap.md), [Enums for gpiod](/conventions/enums-gpiod.md).
* **Creation**: Traps — [GPIO device permissions](/traps/gpio-device-permissions.md), [`function_exists` load order](/traps/function-exists-load-order.md).
* **Creation**: Subdirectory indexes under `orientation/`, `architecture/`, `conventions/`, `traps/`; root [index.md](/index.md).
* Pattern mirrored from `microscrap/posix` / `microscrap/mpsse` / `microscrap/open-gl` OKF layout; adapted for libgpiod-style facades + DataObjects over posix/posi.
* Root `AGENTS.md` already present with Quick OKF map — left in place; concept paths match that map.
* All new concepts left `status: draft` pending Angel human verification.

## 2026-09-24
* **Update**: 0.9.0 line. `composer.json` version, `ext-posi` / `microscrap/posix` requires `^0.9.0`, branch alias `0.9.x-dev`, suggest renamed to `scrapyard-io/framework` `^0.9.0`; README and AGENTS version references; [Package (0.9)](/orientation/package.md), [Ecosystem docs](/orientation/ecosystem-docs.md), [Pair with protocol peers](/orientation/pairing-protocol-peers.md), [Enums for gpiod](/conventions/enums-gpiod.md), [Helpers → gpiod facades → posix/posi](/architecture/helpers-gpiod-posi.md) relabelled. No API change. Needed by `microscrap/scrapyard-linux` 0.9.0, whose Posix DigitalIO driver calls the `gpiod_*` helpers.
