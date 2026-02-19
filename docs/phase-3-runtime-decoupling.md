# Phase 3 Runbook: Runtime Decoupling (Apache/MySQL Tight Coupling)

## Objective
Decouple runtime assumptions so the same codebase can run behind different web-server frontends without relying on `$_SERVER["DOCUMENT_ROOT"]` or filesystem traversal hacks.

## Implemented Changes

### 1) Entry/runtime path decoupling in app helpers
- Updated `local/app/helpers.php` constants bootstrap to:
  - Stop deriving project paths from `$_SERVER["DOCUMENT_ROOT"]`.
  - Build filesystem paths from framework/application roots (`base_path`, `storage_path`, `resource_path`) with safe fallback normalization.
  - Resolve web base path from `APP_WEB_ROOT` (if provided) or `$_SERVER['SCRIPT_NAME']`.
- Installer UI path hints updated to print framework-root paths (`PROJECT_SYSTEM_ROOT`) instead of document-root-derived paths.

### 2) Local built-in server front-controller fix
- Updated `local/server.php` to route requests against project root and dispatch to root `index.php`.
- This removes stale assumptions about a missing `local/public` folder.

### 3) Dual web-server runtime setup
- Added `docker-compose.phase3.yml` with profiles:
  - `apache`: Apache + mod_php stack (`web_apache`)
  - `nginx`: Nginx + PHP-FPM stack (`web_nginx` + `php_fpm`)
- Added runtime files:
  - `docker/php/Dockerfile.phase3-fpm`
  - `docker/nginx/nginx.phase3.conf`

## How To Run

### Apache profile
```bash
docker compose -f docker-compose.phase3.yml --profile apache up --build
```
- Default URL: `http://localhost:8081`
- Wait until `adminpanel-phase3-db` is healthy before first browser request (initial startup can take ~20-60s depending on machine/disk).
- If restarting from Docker Desktop UI after code changes, recreate containers (`docker compose -f docker-compose.phase3.yml --profile apache up --build -d --force-recreate`) to avoid stale runtime state.

### Nginx + PHP-FPM profile
```bash
docker compose -f docker-compose.phase3.yml --profile nginx up --build
```
- Default URL: `http://localhost:8082`
- Wait until `adminpanel-phase3-db` is healthy before first browser request.

### Optional environment overrides
- `APP_PORT_APACHE` (default `8081`)
- `APP_PORT_NGINX` (default `8082`)
- `DB_PORT` (default `33061`)
- `DB_HOST` (default `db`)
- `DB_DATABASE` (default `admin_panel`)
- `DB_USERNAME` (default `app_adminpanel`)
- `DB_PASSWORD` (default `adminpanel`)
- `DB_ROOT_PASSWORD` (default `adminpanel`)
- `APP_WEB_ROOT` (optional web base path when app is mounted under a subdirectory)

Credential consistency note:
- `docker-compose.phase3.yml` now uses the same `DB_*` values for MySQL user provisioning and for PHP app runtime env, avoiding `SQLSTATE[HY000] [1045] Access denied` mismatch errors.
- Initial schema/data are auto-imported from `admin_panel.sql` via `/docker-entrypoint-initdb.d/` during first DB initialization.
- If you change any `DB_*` credential values, recreate the DB volume so MySQL can reinitialize users:

```bash
docker compose -f docker-compose.phase3.yml --profile apache down -v --remove-orphans
```

- If you see missing-table errors (for example `Table 'admin_panel.ap_user' doesn't exist`), also recreate the volume so the SQL dump runs again.

## Exit Criteria Mapping
- Same app runs behind at least two server setups: **implemented** (`apache` and `nginx+php-fpm` profiles).
- Runtime path bootstrap no longer tied to `DOCUMENT_ROOT`: **implemented**.
- Path constants normalized through framework helpers where available: **implemented**.

## Notes
- This phase intentionally keeps legacy framework/runtime behavior stable while removing web-server coupling.
- PHP 8.2+ target alignment remains a separate modernization stream tracked in known issues.
