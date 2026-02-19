# Runtime Setup Record

- Date: 2026-02-19
- Operator: Copilot (assisted)
- Branch: upgrade
- Git SHA: 76c9062
- PHP version: 7.4.33 (container runtime for bootstrap compatibility)
- PHP extensions enabled: `pdo_mysql`, `mbstring` (plus default core modules)
- DB engine/version: MySQL 8.0.45 (container)
- Web server/runtime mode: Apache in `php:7.4-apache` container
- Setup commands used:
	- `docker compose -f docker-compose.phase1a.yml up -d db`
	- `docker compose -f docker-compose.phase1a.yml up -d --build web`
- Notes:
	- Host machine had no `php`, `mysql`, or `mysqldump` in PATH.
	- Docker/Compose path used for full bootstrap.
	- PHP 8.2 runtime attempt failed due Laravel 5.1 compatibility issues; temporary PHP 7.4 runtime used for first runnable checkpoint.
