# Modernization Overview

## Purpose

This repository is being modernized in controlled phases so the application stays runnable while platform, configuration, and delivery risks are reduced incrementally.

## Current Status

- Phase 0 through Phase 5 have documented deliverables.
- The current local development baseline is the Docker-backed startup flow exposed through `scripts/startup.ps1`.
- PHP 8.2+ runtime alignment and the historical secret-remediation closure path are complete.
- The lock-screen unlock flow remains a non-blocking backlog item and is tracked in `modernization/docs/known-issues.md`.
- The agreed future roadmap is now defined through Phase 9 in `modernization/roadmap.md`.

## Document Index

- Phase 0 target architecture: `modernization/docs/phase-0-target-architecture.md`
- Phase 1 stabilization runbook: `modernization/docs/phase-1-stabilize-secure-runbook.md`
- Phase 1A bootstrap-first-run runbook: `modernization/docs/phase-1a-bootstrap-first-run.md`
- Phase 2 env and secrets policy: `modernization/docs/phase-2-env-secrets-policy.md`
- Phase 3 runtime decoupling: `modernization/docs/phase-3-runtime-decoupling.md`
- Phase 3B runtime and security closure: `modernization/docs/phase-3b-runtime-and-security-closure.md`
- Phase 4 database portability and data layer hardening: `modernization/docs/phase-4-database-portability-and-data-layer-hardening.md`
- Phase 5 developer experience and automation: `modernization/docs/phase-5-developer-experience-and-automation.md`
- Known issues tracker: `modernization/docs/known-issues.md`
- Full roadmap with planned future phases: `modernization/roadmap.md`

## Completed Checkpoints

- Phase 0 defined the architecture and supported version baseline.
- Phase 1A restored a first runnable local baseline.
- Phase 1 captured stabilization, backup, smoke, and credential-hygiene procedures.
- Phase 2 introduced the environment-driven configuration contract and safer secret handling.
- Phase 3 decoupled runtime assumptions and added Apache plus Nginx/PHP-FPM runtime support.
- Phase 3B closed the PHP 8.2+ runtime target gap and the historical secret-remediation closure path.
- Phase 4 moved schema and baseline data ownership into Laravel migrations and seeders.
- Phase 5 introduced a repeatable developer stack, health endpoints, and startup automation.

## Next Planned Phases

- Phase 6 adds quality gates before refactor work: linting and static analysis, high-value integration tests, and CI automation.
- Phase 7 performs incremental refactor work by domain, replacing generated-style patterns and modularizing large route definitions.
- Phase 8 defines and executes the Laravel framework upgrade path, using intermediary upgrades or a strangler approach if needed.
- Phase 9 finishes rollout, operations, and documentation work for production readiness.

## Versioning Policy

- Values with `+` in modernization documents define minimum supported baselines.
- Exact versions and image tags are pinned inside implementation artifacts where reproducible builds are required.

## Historical Manual Setup Reference

The pre-modernization setup flow is preserved here for context only and should not be used for standard local startup.

1. Clone or extract the repository.
2. Place it under a web server document root.
3. Import `admin_panel.sql` into a MySQL server.
4. Edit the legacy DB settings in `local/admin-panel-settings.php`.
5. Run `composer install` inside `local/`.
6. Open the app and complete the installer flow.
7. Continue to the sign-in screen.