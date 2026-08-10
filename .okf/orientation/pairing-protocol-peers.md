---
type: Orientation
title: Pair with protocol peers
description: "posix/posi below; uart / i2c / spi beside; gpio-framework sits above — this package is GPIO bindings only."
resource: .
tags: [orientation, gpio, posix, uart, i2c, spi, composition]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:45Z" }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: Require posix/posi; suggest gpio-framework
  - id: readme
    resource: README.md
    title: Bindings role and requirements
  - id: agents
    resource: AGENTS.md
    title: Suggested peer only; no framework internals
  - id: chip
    resource: src/Chip.php
    title: Chip open uses posix_open / FileControlFlag
---

# Composition boundary

`microscrap/gpio` is **bindings only** — libgpiod-style chip / line / edge helpers over character devices. It does not register Chassis providers, invent chip board drivers, or own application GPIO orchestration.[^readme][^agents]

| Concern | Package |
|---------|---------|
| POSIX FD / syscall helpers | `microscrap/posix` `^0.7` (required peer) |
| Native FD / ioctl | **ext-posi** `^0.7.0` (`Posi\System`) |
| GPIO chip / line uAPI v2 | `microscrap/gpio` (this package) |
| UART | `microscrap/uart` `^0.7` (sibling protocol) |
| I2C | `microscrap/i2c` `^0.7` (sibling protocol) |
| SPI | `microscrap/spi` `^0.7` (sibling protocol) |
| USB MPSSE / FTDI | `microscrap/ftdi` / `microscrap/mpsse` (beside — USB path, not a gpio child) |
| Higher GPIO orchestration | `scrapyard-io/gpio-framework` `^0.7` (suggested; above this package)[^composer] |

# Typical flow

1. Depend on this package (pulls **ext-posi** + `microscrap/posix` `^0.7.0`).[^composer]
2. Open `/dev/gpiochipN`, configure lines, request ownership, read/write / edge-wait via `gpiod_*` helpers.[^readme][^chip]
3. Application / `gpio-framework` composes this peer — do not invent framework providers inside this package.[^agents]

# Caveats

- Host permissions on `/dev/gpiochip*` matter — see [GPIO device permissions](../traps/gpio-device-permissions.md).
- Helper globals use `function_exists` guards — see [`function_exists` load order](../traps/function-exists-load-order.md).

# Related

* [Package (0.7)](package.md)
* [Helpers → gpiod facades → posix/posi](../architecture/helpers-gpiod-posi.md)

[^composer]: Require posix/posi; suggest gpio-framework
[^readme]: Bindings role and requirements
[^agents]: Suggested peer only; no framework internals
[^chip]: Chip open uses posix_open / FileControlFlag
