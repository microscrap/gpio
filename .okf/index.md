---
okf_version: "0.2"
---

# microscrap/gpio Knowledge Bundle

Package knowledge for `microscrap/gpio` (bindings-only libgpiod v2 / GPIO uAPI v2 helpers over **ext-posi** + `microscrap/posix`, v0.7.0).
Read this index first; open only the concepts needed for the task.

**Trust rule:** Prefer `status: stable`. Treat `deprecated` as historical only. New agent-written concepts stay `status: draft` until a human verifies them.
**Placement:** This bundle lives at the **package root** only — never under `src/`.
**Links:** Concept cross-links use paths relative to each file.
**Scope:** Document the bindings-only package (helpers + facades + enums + DataObjects). Do **not** invent ServiceProviders, Chassis/Core coupling, or Fabricate remaps here — higher orchestration belongs in `scrapyard-io/gpio-framework`.
**Dist note:** `.okf/` and root `AGENTS.md` are `export-ignore` in `.gitattributes` so Composer dist packages do not ship this bundle.

# Orientation

* [Package (0.7)](orientation/package.md) - Composer identity, namespace, helpers over libgpiod / posi.
* [Ecosystem docs](orientation/ecosystem-docs.md) - Published 0.7.x overview and docs site entrypoint.
* [Pair with protocol peers](orientation/pairing-protocol-peers.md) - posix below; uart / i2c / spi beside; gpio-framework above.

# Architecture

* [Helpers → gpiod facades → posix/posi](architecture/helpers-gpiod-posi.md) - Call stack: `gpiod_*` helpers → facade classes → posix / Posi\System.

# Conventions

* [1:1 libgpiod wrap](conventions/one-to-one-libgpiod-wrap.md) - Keep wrap aligned with libgpiod v2 / existing Helpers surface; helpers thin over facades.
* [Enums for gpiod](conventions/enums-gpiod.md) - Int-backed enums; FULLY UPPERCASE cases; no class constants.

# Traps

* [GPIO device permissions](traps/gpio-device-permissions.md) - `/dev/gpiochip*` access, group membership, exclusive line claims.
* [`function_exists` load order](traps/function-exists-load-order.md) - Autoload order; first definition wins.

# Log

* [Directory update log](log.md)
