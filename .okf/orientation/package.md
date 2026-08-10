---
type: Orientation
title: Package (0.7)
description: "microscrap/gpio 0.7.0 — bindings-only libgpiod v2 / GPIO uAPI v2 helpers; helpers → facades → posix/posi; no ServiceProvider."
resource: .
tags: [orientation, gpio, microscrap, bindings, gpiod, 0.7]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:45Z" }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: Package name, version, PHP, require, suggest, autoload helpers
  - id: readme
    resource: README.md
    title: Package README
  - id: helpers-chip
    resource: src/Helpers/gpio-chip.php
    title: Global gpiod_chip_* helpers
  - id: chip
    resource: src/Chip.php
    title: Chip facade
  - id: agents
    resource: AGENTS.md
    title: Agent rules for this package
---

# What it is

Composer package `microscrap/gpio` at **0.7.0** — pure-PHP bindings that mirror [libgpiod v2](https://git.kernel.org/pub/scm/libs/libgpiod/libgpiod.git/) over Linux GPIO character-device uAPI v2, built on **ext-posi** and [`microscrap/posix`](https://github.com/microscrap/posix).[^composer][^readme]

| Field | Value |
|-------|-------|
| Name | `microscrap/gpio` |
| Version | `0.7.0` |
| PHP | `^8.4\|^8.5\|^8.6`[^composer] |
| Namespace | `Microscrap\Bindings\GPIO\` → `src/`[^composer] |
| Require | `ext-posi` `^0.7.0`, `microscrap/posix` `^0.7.0`[^composer] |
| Suggest | `scrapyard-io/gpio-framework` `^0.7`[^composer] |
| Homepage | Ecosystem docs overview (see [Ecosystem docs](ecosystem-docs.md))[^composer] |
| Discovery | **None** — no provider / Chassis registration in this package[^readme][^agents] |
| Role | Bindings layer only (helpers + facades + enums + DataObjects)[^helpers-chip][^chip][^readme] |

Autoloads helper files under `src/Helpers/` registering global `gpiod_*` functions, each guarded with `function_exists`.[^composer]

# What it is not

- Not the native **ext-posi** extension — that is `php-io-extensions/posi`.[^readme]
- Not `microscrap/posix` — that package is the thinner FD / syscall helper layer; this package **depends on** it and adds GPIO chip / line APIs.[^composer]
- Not higher GPIO orchestration / chip drivers — those belong in `scrapyard-io/gpio-framework` (suggested peer).[^composer][^agents]
- Not a ServiceProvider package — no Chassis/Core/Fabricate/Machine coupling.[^readme][^agents]

# Public surface (summary)

| Layer | Location | Role |
|-------|----------|------|
| Helpers | `src/Helpers/gpio-*.php` | Thin globals (`gpiod_chip_open`, `gpiod_line_request_set_value`, …) over facades |
| Facades | `src/Chip.php`, `LineRequest.php`, `LineSettings.php`, … | Static API implementing libgpiod-style operations |
| DataObjects | `src/DataObjects/*` | Chip / line / request / event / uAPI structs |
| Enums | `src/Enums/*` | Direction, edge, bias, drive, clock, value, ioctl opcodes, flags |
| Posix peer | `posix_*` / `Posi\System` | FD open/close and ioctl underneath facades |

# Related

| Topic | Concept |
|-------|---------|
| Call stack | [Helpers → gpiod facades → posix/posi](../architecture/helpers-gpiod-posi.md) |
| Wrap rules | [1:1 libgpiod wrap](../conventions/one-to-one-libgpiod-wrap.md) |
| Enums | [Enums for gpiod](../conventions/enums-gpiod.md) |
| Peer stack | [Pair with protocol peers](pairing-protocol-peers.md) |
| Docs site | [Ecosystem docs](ecosystem-docs.md) |
| Peer | `microscrap/posix` 0.7.0 |

[^composer]: Package name, version, PHP, require, suggest, autoload helpers
[^readme]: Package README
[^helpers-chip]: Global gpiod_chip_* helpers
[^chip]: Chip facade
[^agents]: Agent rules for this package
