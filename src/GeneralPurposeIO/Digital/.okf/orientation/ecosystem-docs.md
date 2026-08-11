---
type: Reference
title: Ecosystem docs
description: "Published ScrapyardIO docs for Digital IO ride on gpio-framework 0.6.x overview; no standalone digital slug yet."
resource: "https://scrapyard-io.projectsaturnstudios.com/ecosystem/scrapyard-io/gpio-framework/0.6.x/overview"
tags: [orientation, docs, ecosystem, gpio-framework]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T21:52:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: README production docs link and badges
  - id: composer
    resource: composer.json
    title: homepage and support.docs URLs
  - id: overview
    resource: "https://scrapyard-io.projectsaturnstudios.com/ecosystem/scrapyard-io/gpio-framework/0.6.x/overview"
    title: gpio-framework ecosystem overview page
---

# Entrypoint

Human-facing docs for this package currently live on the ScrapyardIO ecosystem site under **gpio-framework** (Digital has no standalone ecosystem slug yet):[^overview][^readme][^composer]

[https://scrapyard-io.projectsaturnstudios.com/ecosystem/scrapyard-io/gpio-framework/0.6.x/overview](https://scrapyard-io.projectsaturnstudios.com/ecosystem/scrapyard-io/gpio-framework/0.6.x/overview)

README badges, the production docs banner, and `composer.json` `homepage` / `support.docs` point at that overview.[^readme][^composer]

# How agents should use it

- Prefer this OKF bundle for **in-repo** agent rules (protocol role, adapter stack, metapackage replace, microscrap runtime traps).
- Prefer the ecosystem site for **published** narrative docs aimed at humans (hosted under gpio-framework until a digital-specific slug exists).
- When either drifts from sources or README, update the stale side and note it in [log.md](../log.md).

# Related

* [Package (0.7)](package.md)
* [Metapackage replace](../conventions/metapackage-replace.md)

[^readme]: README production docs link and badges
[^composer]: homepage and support.docs URLs
[^overview]: gpio-framework ecosystem overview page
