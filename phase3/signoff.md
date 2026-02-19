# Phase 3 Sign-Off (Runtime Decoupling)

- Date: 2026-02-19
- Approver(s): pending human approval
- Branch: upgrade
- Git SHA: 9e7c65d
- Runtime path coupling to `$_SERVER["DOCUMENT_ROOT"]` removed from app bootstrap constants: yes (`local/app/helpers.php`)
- Legacy root-marker traversal logic for path discovery removed: yes (`local/app/helpers.php`)
- Filesystem paths normalized from framework/project roots: yes
- Local PHP built-in server entrypoint aligned to project root index: yes (`local/server.php`)
- Apache profile runnable: yes (`docker-compose.phase3.yml --profile apache`)
- Nginx + PHP-FPM profile runnable: yes (`docker-compose.phase3.yml --profile nginx`)
- DB credential/runtime alignment for containers implemented: yes (`DB_*` env contract in `docker-compose.phase3.yml`)
- First-init schema/data auto-import implemented: yes (`admin_panel.sql` mounted into `/docker-entrypoint-initdb.d/`)
- Dual-server exit criterion met (same app behind 2 server setups): yes
- Ready for next phase: yes

## Validation Summary

- Compose config validation completed for both profiles.
- Browser smoke checks reached HTTP 200 on:
  - Apache profile (`http://localhost:8081`)
  - Nginx profile (`http://localhost:8082`)
- Database bootstrap validation confirmed required table presence (`ap_user`) after first-init SQL import.

## Deferred / Out-of-Scope Items

- PHP baseline target alignment (Phase 0 target PHP 8.2+) remains pending; current runtime remains compatibility-oriented.
- Lock-screen unlock flow issue remains tracked in known issues.
- Historical secret-exposure remediation remains an ongoing security operations stream.
