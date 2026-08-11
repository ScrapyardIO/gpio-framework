---
type: Convention
title: Metapackage replace
description: "Prefer scrapyard-io/gpio-framework ^0.7; it replaces gpio/digital at the same version. Split require is advanced."
resource: composer.json
tags: [convention, composer, replace, metapackage, gpio-framework]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:52:00Z" }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: suggest scrapyard-io/gpio-framework
  - id: readme
    resource: README.md
    title: Prefer metapackage; split require advanced
  - id: meta
    resource: ../../../../composer.json
    title: scrapyard-io/gpio-framework replace gpio/digital self.version
---

# Prefer the kitchen-sink

Default install path:[^readme]

```bash
composer require scrapyard-io/gpio-framework:^0.7.0
```

`scrapyard-io/gpio-framework` **replaces** `gpio/digital` (and sibling protocol packages) at `self.version`, so Composer satisfies `gpio/digital` from the metapackage tree rather than a separate Packagist install.[^meta][^readme]

This package’s own `suggest` lists `scrapyard-io/gpio-framework` `^0.7` for that kitchen-sink path.[^composer]

# Split require (advanced)

```bash
composer require gpio/digital:^0.7.0
```

Use when you intentionally want only Digital IO (plus its `gpio/common` + `gpio/contracts` requires) without the full gpio-framework surface.[^readme]

# Agent guidance

- When advising app installs, **prefer the metapackage** unless the caller explicitly wants a split protocol package.
- Do not document split require as the default path.
- Version alignment: Digital **0.7.0** matches the metapackage replace line at the same version.[^meta][^composer]

# Related

* [Package (0.7)](../orientation/package.md)
* [Ecosystem docs](../orientation/ecosystem-docs.md)

[^composer]: suggest scrapyard-io/gpio-framework
[^readme]: Prefer metapackage; split require advanced
[^meta]: scrapyard-io/gpio-framework replace gpio/digital self.version
