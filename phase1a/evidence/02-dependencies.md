# Dependency Installation Record

- Date: 2026-02-19
- Operator: Copilot (assisted)
- Branch: upgrade
- Git SHA: 76c9062
- Composer command used:
	- Attempt 1 (failed): `docker run --rm -v "${PWD}/local:/app" -w /app composer:2 composer install --no-interaction`
	- Attempt 2 (partial, failed on scripts): `docker run --rm -v "${PWD}/local:/app" -w /app composer:2 composer install --no-interaction --no-dev --ignore-platform-reqs`
	- Final (success): `docker run --rm -v "${PWD}/local:/app" -w /app composer:2 composer install --no-interaction --no-dev --ignore-platform-reqs --no-scripts`
- Result: pass (runtime dependencies installed)
- Blockers encountered:
	- Legacy lockfile dev packages incompatible with Composer image PHP 8.5 runtime.
	- Post-install artisan scripts fail in CLI due legacy helper assumptions around `DOCUMENT_ROOT`.
- Workarounds applied:
	- Use `--no-dev --ignore-platform-reqs --no-scripts` for bootstrap-only dependency install.
- Notes:
	- This is a Phase 1A bootstrap workaround, not a long-term dependency strategy.
