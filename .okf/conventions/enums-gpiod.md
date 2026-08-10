---
type: Convention
title: Enums for gpiod
description: "GPIO direction/edge/bias/drive/clock/value and ioctl enums are int-backed with FULLY UPPERCASE cases; no class constants."
resource: src/Enums/
tags: [convention, enums, gpio, gpiod]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:45Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Enum table and UPPERCASE note
  - id: direction
    resource: src/Enums/LineDirection.php
    title: LineDirection enum
  - id: edge
    resource: src/Enums/LineEdge.php
    title: LineEdge enum
  - id: bias
    resource: src/Enums/LineBias.php
    title: LineBias enum
  - id: drive
    resource: src/Enums/LineDrive.php
    title: LineDrive enum
  - id: clock
    resource: src/Enums/LineClock.php
    title: LineClock enum
  - id: value
    resource: src/Enums/LineValue.php
    title: LineValue enum
  - id: info-event
    resource: src/Enums/InfoEventType.php
    title: InfoEventType enum
  - id: edge-event
    resource: src/Enums/EdgeEventType.php
    title: EdgeEventType enum
  - id: opcode
    resource: src/Enums/GPIOOpCode.php
    title: GPIOOpCode enum
  - id: flags
    resource: src/Enums/GPIOV2LineFlag.php
    title: GPIOV2LineFlag enum
  - id: attr-id
    resource: src/Enums/GPIOV2LineAttrId.php
    title: GPIOV2LineAttrId enum
  - id: stat-mode
    resource: src/Enums/StatMode.php
    title: StatMode enum
  - id: agents
    resource: AGENTS.md
    title: Enum case naming rule
---

# Why enums live here

Typed tokens for libgpiod-style line settings, event types, kernel ioctl opcodes, and `gpio_v2_line_flag` bitmasks. Keep new constants as enum cases — never class-level `const`.[^readme][^agents]

# Rules

- Use **int-backed** enums under `Microscrap\Bindings\GPIO\Enums\`.[^direction][^opcode]
- Case names are **FULLY UPPERCASE** (e.g. `LineDirection::OUTPUT`, `LineEdge::RISING`).[^agents][^readme]
- No class-level constants in `src/`.[^agents]
- Pass enum instances (or `->value` where a raw `int` is required) into typed helper / facade APIs.[^readme]

# Enum inventory (0.7.0)

| Enum | Backing | Cases (summary) |
|------|---------|-----------------|
| `LineDirection` | int | `AS_IS`, `INPUT`, `OUTPUT`[^direction] |
| `LineEdge` | int | `NONE`, `RISING`, `FALLING`, `BOTH`[^edge] |
| `LineBias` | int | `AS_IS`, `UNKNOWN`, `DISABLED`, `PULL_UP`, `PULL_DOWN`[^bias] |
| `LineDrive` | int | `PUSH_PULL`, `OPEN_DRAIN`, `OPEN_SOURCE`[^drive] |
| `LineClock` | int | `MONOTONIC`, `REALTIME`, `HTE`[^clock] |
| `LineValue` | int | `ERROR` (-1), `INACTIVE` (0), `ACTIVE` (1)[^value] |
| `InfoEventType` | int | `LINE_REQUESTED`, `LINE_RELEASED`, `LINE_CONFIG_CHANGED`[^info-event] |
| `EdgeEventType` | int | `RISING_EDGE`, `FALLING_EDGE`[^edge-event] |
| `GPIOOpCode` | int | GPIO ioctl opcodes from `linux/gpio.h` macros[^opcode] |
| `GPIOV2LineFlag` | int | 13 `gpio_v2_line_flag` bitmask values[^flags] |
| `GPIOV2LineAttrId` | int | `FLAGS`, `OUTPUT_VALUES`, `DEBOUNCE`[^attr-id] |
| `StatMode` | int | `S_IFMT`, `S_IFLNK`, `S_IFCHR` (device-node checks)[^stat-mode] |

# Related

* [1:1 libgpiod wrap](one-to-one-libgpiod-wrap.md)
* [Package (0.7)](../orientation/package.md)
* [Helpers → gpiod facades → posix/posi](../architecture/helpers-gpiod-posi.md)

[^readme]: Enum table and UPPERCASE note
[^direction]: LineDirection enum
[^edge]: LineEdge enum
[^bias]: LineBias enum
[^drive]: LineDrive enum
[^clock]: LineClock enum
[^value]: LineValue enum
[^info-event]: InfoEventType enum
[^edge-event]: EdgeEventType enum
[^opcode]: GPIOOpCode enum
[^flags]: GPIOV2LineFlag enum
[^attr-id]: GPIOV2LineAttrId enum
[^stat-mode]: StatMode enum
[^agents]: Enum case naming rule
