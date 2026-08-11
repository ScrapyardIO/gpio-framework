---
type: Convention
title: Component subtree packaging
description: Splittable GeneralPurposeIO folders use gpio/* composer names replaced by the umbrella.
tags: [conventions, composer, replace]
generated: { by: cursor-agent/grok-4.5, at: "2026-08-10T22:45:00Z" }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: Umbrella replace
---

# Rule

Each splittable folder under `src/GeneralPurposeIO/{Component}/` needs `composer.json` + `LICENSE.md`. Umbrella `replace` maps `gpio/{component}` at `self.version`, including **`gpio/circuits`**.

# Related

* [Package (0.7)](../orientation/package.md)
