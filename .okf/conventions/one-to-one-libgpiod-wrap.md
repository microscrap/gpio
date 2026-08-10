---
type: Convention
title: "1:1 libgpiod wrap"
description: "Keep GPIO API aligned with libgpiod v2 / existing Helpers; helpers stay thin over package facades; no invented APIs."
resource: src/
tags: [convention, bindings, gpio, libgpiod]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:45Z" }
status: draft
sources:
  - id: agents
    resource: AGENTS.md
    title: Agent wrap rules
  - id: readme
    resource: README.md
    title: Package README wrap description and helper API
  - id: helpers-chip
    resource: src/Helpers/gpio-chip.php
    title: Thin helper delegation to Chip
  - id: chip
    resource: src/Chip.php
    title: Chip facade implements libgpiod-style chip ops
---

# Rule

Match the existing public surface; stay aligned with libgpiod v2 / GPIO uAPI v2 patterns already expressed in helpers and facades:[^agents][^readme][^chip]

1. Global helpers use `gpiod_*` names and stay **thin** over facade static methods.[^helpers-chip]
2. Protocol / ioctl logic belongs on facades (`Chip`, `LineRequest`, …) — do not invent a second wrapper or bypass into ad-hoc `ioctl` scripts from helpers.[^chip][^agents]
3. Keep **1:1 coverage** with helpers already in `src/Helpers/`; document drift in README / ecosystem docs.[^agents]
4. Do **not** invent APIs that are not already in `src/` / README — extend only when Angel asks and keep libgpiod alignment.[^agents]
5. State types live under `src/DataObjects/` — do not invent a parallel object model.[^readme]
6. Direction / edge / bias / drive / value / opcode tokens live in backed enums — see [Enums for gpiod](enums-gpiod.md).
7. Prefer `is_null($var)` over `$var === null`.[^agents]
8. No class-level constants in `src/` — use backed enums.[^agents]
9. No ServiceProvider / Chassis / Core / Fabricate wiring in this package.[^readme][^agents]
10. Suggested peer only: `scrapyard-io/gpio-framework` — do not pull framework internals into this package.[^agents]

# Architecture link

Full call-stack diagram: [Helpers → gpiod facades → posix/posi](../architecture/helpers-gpiod-posi.md).

[^agents]: Agent wrap rules
[^readme]: Package README wrap description and helper API
[^helpers-chip]: Thin helper delegation to Chip
[^chip]: Chip facade implements libgpiod-style chip ops
