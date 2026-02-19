# Credential Rotation Report

- Date: 2026-02-19
- Operator: Copilot (assisted)
- Branch: upgrade
- Git SHA: 76c9062
- DB user rotated: yes
- DB old credential revoked: yes
- SMTP credential rotated: yes
- SMTP old credential revoked: partial (local config rotated; external provider revocation not verifiable in this environment)
- Validation of old credentials failing: pass (old DB root credential `adminpanel` rejected)
- Notes:
	- New DB app runtime user configured: `app_adminpanel`.
	- `local/admin-panel-settings.php` updated to use rotated DB app credentials.
	- SMTP values rotated to non-sensitive placeholders (`smtp.invalid`, `no-reply@admin-panel.invalid`).
	- Local-only rotated secret material stored in `phase1/.secret-rotation.local.txt` (must not be committed).
