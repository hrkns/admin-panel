# Current Failure Baseline

- Date: 2026-02-19
- Operator: Copilot (assisted)
- Branch: upgrade
- Git SHA: 76c9062
- Attempted startup method: local CLI capability check for PHP/Composer/MySQL clients.
- Observed error(s):
	- `php` not found in PATH.
	- `mysql` not found in PATH.
	- `mysqldump` not found in PATH.
	- `mariadb` not found in PATH.
	- Docker and Docker Compose are available.
- Logs/screenshots reference: terminal output (2026-02-19) from tooling check command.
- Notes: bootstrap path will use Docker first because host runtime tooling is not installed.
