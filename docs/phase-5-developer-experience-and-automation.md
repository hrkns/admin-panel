# Phase 5 Runbook: Developer Experience & Automation

## Objective
Improve local onboarding speed and consistency so a new developer can run the project in under 15 minutes.

## Deliverable Scope

### 1) Unified developer compose stack
- Added `docker-compose.phase5.yml` with:
  - `app` (Apache + PHP runtime),
  - `db` (MySQL 8.0 with healthcheck),
  - `maildev` (Mailpit SMTP + web UI for local email testing),
  - `composer` helper service for dependency installation.

### 2) One-command setup automation
- Added `scripts/setup-phase5.ps1`.
- Script performs:
  - local `.env` bootstrap from `.env.example` when missing,
  - local-safe env defaults for DB and mail routing,
  - optional SMTP mode selection (`Mailpit defaults` vs `keep existing SMTP`),
  - compose startup for db/mail/app,
  - composer dependency installation (`--no-dev` for legacy lock compatibility),
  - `php artisan key:generate`,
  - `php artisan migrate --seed --force`,
  - startup checks against health endpoints.

### 3) Health endpoints and startup checks
- Added two lightweight health routes:
  - `GET /health/live` for liveness.
  - `GET /health/ready` for readiness (DB connectivity + storage writability).
- Health routes are short-circuited before legacy heavy route bootstrap code, reducing false negatives from unrelated app-route initialization paths.

### 4) Mailpit compatibility defaults
- The legacy app email helper (`sendEmail`) uses compatibility settings from `globalSettings` and enables SMTP auth/SSL when `AP_SMTP_SECURE` is true.
- Phase 5 setup sets `AP_SMTP_SECURE=0`, `MAIL_HOST=maildev`, `MAIL_PORT=1025`, and empty `MAIL_ENCRYPTION` to route local mails to Mailpit.
- This does **not** remove the legacy capability to send through external SMTP; it only changes local `.env` defaults during setup.
- New switch: `-KeepExistingSmtp` preserves your current SMTP values in `local/.env` and skips Mailpit SMTP overwrite.

## How To Run (Phase 5)

From repository root in PowerShell:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\setup-phase5.ps1
```

Optional flags:

```powershell
# Rebuild app image before startup
powershell -ExecutionPolicy Bypass -File .\scripts\setup-phase5.ps1 -Rebuild

# Recreate DB volume (clean DB) before setup
powershell -ExecutionPolicy Bypass -File .\scripts\setup-phase5.ps1 -ResetDb

# Keep existing SMTP settings from local/.env (external SMTP)
powershell -ExecutionPolicy Bypass -File .\scripts\setup-phase5.ps1 -KeepExistingSmtp

# Combine flags (example)
powershell -ExecutionPolicy Bypass -File .\scripts\setup-phase5.ps1 -Rebuild -KeepExistingSmtp
```

### SMTP mode behavior
- Default mode (no switch): setup writes Mailpit-compatible SMTP values into `local/.env`.
- `-KeepExistingSmtp`: setup leaves SMTP values untouched in `local/.env`.
- Recommended for external SMTP: set your provider values in `local/.env` first, then run setup with `-KeepExistingSmtp`.

## Endpoints

- App: `http://localhost:8081`
- Health (live): `http://localhost:8081/health/live`
- Health (ready): `http://localhost:8081/health/ready`
- Mail UI (Mailpit): `http://localhost:8025`

## Verification checklist

- `setup-phase5.ps1` completes without errors.
- `/health/live` returns HTTP 200 with JSON status.
- `/health/ready` returns HTTP 200 after migration+seed.
- Sign-in page loads on `http://localhost:8081`.
- Outbound app email appears in Mailpit UI.
