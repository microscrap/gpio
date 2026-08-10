---
type: Trap
title: "`function_exists` load order"
description: "Helpers skip definition when the name exists; first autoloaded definition wins."
resource: src/Helpers/gpio-chip.php
tags: [trap, autoload, helpers, gpio, gpiod]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:45Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: README note on function_exists guards
  - id: helpers-chip
    resource: src/Helpers/gpio-chip.php
    title: function_exists guard pattern
  - id: composer
    resource: composer.json
    title: Autoload files entries
---

# Symptom

A `gpiod_*` call behaves unlike this package’s wrap — or a redefinition / “missing helper” confusion appears when multiple packages define the same global name.

# Cause

Every helper is defined only when the name is free:[^helpers-chip][^readme]

```php
if (! function_exists('gpiod_chip_open')) {
    function gpiod_chip_open(string $path): ?GPIOChip
    {
        return Chip::gpioChipOpen($path);
    }
}
```

Under the guard, **whichever package’s autoload files run first keeps the definition**. Composer `autoload.files` order depends on require graph / install order.[^composer]

# Mitigation

- Treat this package as the **canonical** `gpiod_*` helper source when present.[^readme]
- Avoid defining overlapping global `gpiod_*` helpers in application code or peer packages.
- Prefer calling facade classes directly (`Microscrap\Bindings\GPIO\Chip::*`, `LineRequest::*`, …) when you must avoid global-name collisions entirely (same underlying API).[^helpers-chip]

# Related

* [Helpers → gpiod facades → posix/posi](../architecture/helpers-gpiod-posi.md)
* [1:1 libgpiod wrap](../conventions/one-to-one-libgpiod-wrap.md)

[^readme]: README note on function_exists guards
[^helpers-chip]: function_exists guard pattern
[^composer]: Autoload files entries
