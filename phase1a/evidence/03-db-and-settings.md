# Database and Settings Bootstrap

- Date: 2026-02-19
- Operator: Copilot (assisted)
- Branch: upgrade
- Git SHA: 76c9062
- Database created: yes (`admin_panel` via Compose MySQL service)
- SQL import completed: yes (`admin_panel.sql` imported into `adminpanel-phase1a-db`)
- Settings bootstrap completed: yes
- Notes on temporary values and reversibility:
	- `local/admin-panel-settings.php` was updated for bootstrap DB connectivity:
		- `db_address`: `localhost` -> `db`
		- `db_password`: previous value -> `adminpanel`
	- These are temporary Phase 1A values and must be replaced during Phase 1 credential hygiene.
- Notes:
	- Compose DB service host is reachable as `db` from web service network.
