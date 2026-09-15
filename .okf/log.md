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
