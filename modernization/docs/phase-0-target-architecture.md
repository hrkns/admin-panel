# Phase 0: Target Architecture

## Purpose
Define the modernization baseline so the project can become runnable again with low risk, then upgraded in controlled phases.

## Decisions (Phase 0)
- **Runtime baseline:** PHP 8.2+.
- **Database baseline:** MySQL 8.0+ or MariaDB 10.11+.
- **Development OS compatibility:** Linux and Windows.
- **Initial deployment target:** Docker Compose (local and server-friendly baseline).
- **Future deployment targets (later phases):** cloud services and/or VM-based deployment.

## Versioning Policy (Applies to All Components)
- In Phase 0, versions with `+` indicate a **minimum supported baseline** (compatibility floor), not an unbounded production target.
- This allows short-term flexibility while restoring runnability across developer machines and environments.
- In implementation phases (starting with Docker setup), images and tool versions must be **pinned to exact tags/versions** for reproducible builds.
- Updates above the floor are intentional and controlled (tested, documented, and promoted through checkpoints), not implicit.

## Target Architecture (Checkpoint)
- `web` service: PHP + web server container serving the application code.
- `db` service: MySQL 8 or MariaDB 10.11 container with persisted data volume.
- `app` configuration: all environment-specific values loaded from environment variables (no hardcoded secrets in code).
- `storage` mapping: bind mounts/volumes for writable paths and database persistence.
- `network`: private Compose network between app and database.

This architecture is intentionally conservative: it prioritizes reproducible startup and easier debugging before framework/code refactors.

## Supported Versions Matrix

| Component | Target | Minimum Supported | Notes |
|---|---|---|---|
| PHP | 8.2 | 8.2.x | Minimum baseline in Phase 0; exact image/tag pinned in implementation phases. |
| MySQL | 8.0 | 8.0.x | Primary DB baseline; pin exact version in Compose during implementation. |
| MariaDB | 10.11 | 10.11.x | Alternative DB baseline; pin exact version in Compose during implementation. |
| OS (Development) | Linux, Windows | Current LTS/maintained releases | Baseline compatibility target; lock team standard images/toolchains as needed. |
| Container Runtime | Docker Engine + Compose v2 | Compose Specification compatible runtime | Baseline capability; pin Compose file and service image tags in implementation phases. |

## Out of Scope for Phase 0
- Laravel/framework major upgrades.
- Code style or architecture refactors.
- Feature changes.
- Cloud-specific IaC.

## Exit Criteria
Phase 0 is complete when:
1. This target architecture and versions matrix are approved.
2. Next phase work uses this baseline as the compatibility contract.
