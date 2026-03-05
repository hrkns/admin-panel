# Phase 4 Runbook: Database Portability & Data Layer Hardening

## Objective
Replace dump-only bootstrap with a deterministic application-owned database path (`migrate + seed`) and isolate vendor-specific behavior.

## What Changed

### 1) Schema ownership moved into migrations
- Added migration `local/database/migrations/2026_02_19_000000_phase4_schema_bootstrap.php`.
- On MySQL connections, schema DDL is imported from `admin_panel.sql` through a parser that keeps only schema statements (`CREATE TABLE`, `ALTER TABLE`).
- On non-MySQL connections, a portable core schema (auth/session/roles/menu/status tables) is created via Laravel `Schema` builder.

### 2) Vendor-specific SQL isolated
- Added SQL parsing helper `local/app/Support/Database/LegacySqlSnapshot.php`.
- MySQL-specific snapshot handling is now contained in migration code instead of Docker DB init hooks.

### 3) Deterministic seed strategy introduced
- Added `local/database/seeds/Phase4CoreDefaultsSeeder.php`.
- `DatabaseSeeder` now executes `Phase4CoreDefaultsSeeder`.
- Seeder provides idempotent default baseline for:
  - statuses and languages,
  - admin actions, roles, and key sections,
  - default users (`admin`, `guest`, `developer`), preferences, and role assignments.
- Current Phase 4 state restores legacy-wide panel/menu ACL data from `admin_panel.sql` when available on MySQL, while keeping deterministic seeding behavior.

### 4) Runtime no longer requires SQL file mount for first init
- Added `docker-compose.phase4.yml`.
- Phase 4 compose intentionally does **not** mount `admin_panel.sql` into `/docker-entrypoint-initdb.d/`.
- Database structure and defaults are initialized from the app layer via Artisan.

## Inventory Summary (Phase 4 baseline)

### Raw SQL / MySQL-specific coupling found
- Full runtime bootstrap depended on `admin_panel.sql` dump import in previous compose profile.
- Dump contains MySQL-specific constructs (`ENGINE=InnoDB`, `AUTO_INCREMENT`, MySQL enum/tinyint usage).

### App-layer SQL usage
- Current PHP codebase is mostly Eloquent-driven; direct raw SQL usage in controllers is minimal.
- Primary lock-in source was bootstrap-time schema/data import strategy, not query-builder usage.

## How To Run (Phase 4 baseline)

### 1) Start stack
#### Apache profile
```bash
docker compose -f docker-compose.phase4.yml --profile apache up --build -d
```

#### Nginx profile
```bash
docker compose -f docker-compose.phase4.yml --profile nginx up --build -d
```

### 2) Run deterministic schema + seed
#### Apache profile
```bash
docker compose -f docker-compose.phase4.yml --profile apache exec -T web_apache bash -lc "cd /var/www/html/local && php artisan migrate --seed --force"
```

#### Nginx profile
```bash
docker compose -f docker-compose.phase4.yml --profile nginx exec -T php_fpm bash -lc "cd /var/www/html/local && php artisan migrate --seed --force"
```

> Note: do not run Artisan in `web_nginx`; that container is Nginx-only and does not include PHP CLI.

### 3) Open app
- Apache profile URL: `http://localhost:8081`
- Nginx profile URL: `http://localhost:8082`
- Database service is internal to Docker network in Phase 4 (no host DB port publish required).

### 4) Quick checks if `http://localhost:8082` is not reachable
```bash
docker compose -f docker-compose.phase4.yml --profile nginx ps
docker compose -f docker-compose.phase4.yml --profile nginx logs --tail=100 web_nginx
docker compose -f docker-compose.phase4.yml --profile nginx logs --tail=100 php_fpm
```

## Verification Checklist
- `php artisan migrate:status` shows Phase 4 migration as applied.
- `php artisan db:seed --class=Phase4CoreDefaultsSeeder --force` runs without duplicate/constraint errors.
- Login works with seeded defaults (`admin`, `guest`, `developer`).

## Notes and Scope
- MySQL remains the full-coverage schema target for this legacy codebase in Phase 4 baseline.
- Non-MySQL support in this phase is intentionally scoped to a portable **core** dataset for incremental decoupling.
- Next increment should progressively replace MySQL snapshot DDL with native Laravel migrations table-by-table.
