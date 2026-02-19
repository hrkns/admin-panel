# First Startup Validation

- Date: 2026-02-19
- Operator: Copilot (assisted)
- Branch: upgrade
- Git SHA: 76c9062
- Root route reachable: yes (`HTTP 200` at `http://localhost:8080/`)
- Login screen rendered: yes (login view markers found in response)
- Installer behavior observed: not validated in this step (deferred to Phase 1 smoke checks)
- Iterations required: yes
	- Initial PHP 8.2 container run produced Laravel 5.1 incompatibility fatals.
	- Switched Phase 1A runtime to PHP 7.4 compatibility image and reached runnable state.
- Final startup command(s):
	- `docker compose -f docker-compose.phase1a.yml up -d db`
	- `docker compose -f docker-compose.phase1a.yml up -d --build web`
- Notes:
	- First runnable checkpoint achieved.
