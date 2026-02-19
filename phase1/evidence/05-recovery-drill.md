# Recovery Drill Report

- Date: 2026-02-19
- Operator: Copilot (assisted)
- Branch: upgrade
- Git SHA: 537003a
- Throwaway DB provisioned: yes (`admin_panel_recovery_phase1`)
- SQL restore completed: yes
- Settings snapshot applied: no (recovery DB validation step done first)
- Login reachable after restore: yes (`GET /` 200 and Sign-In form rendered)
- Reduced smoke subset passed: yes (`/`, `/installer`, `/session-monitor` each returned 200)
- Notes:
	- Restore executed from baseline dump `phase1/artifacts/20260219-113119/admin-panel-phase1-baseline.sql`.
	- Table-count parity validated after restore: `admin_panel=300`, `admin_panel_recovery_phase1=300`.
	- Docker backend outage from earlier attempt is resolved for this pass.
