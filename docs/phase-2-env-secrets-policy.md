# Phase 2 Env & Secret Policy

## Scope

This policy defines how configuration and secrets are handled after Phase 2 configuration overhaul.

## Rules

- Secrets must be sourced from environment variables at runtime.
- Secret-bearing local files must not be tracked by git.
- `local/.env.example` is a template and must only contain placeholders.
- `local/storage/admin-panel/runtime-settings.json` may persist mutable non-secret settings only.
- Sensitive contract keys (`db_user`, `db_password`, `smtp_email_from`, `smtp_password_from`) are env-only and required.
- `APP_KEY` must be provided via env and must not have a hardcoded fallback in code.
- Historical secret exposure findings (if any) must be handled as formal security incidents (rotation/revocation and documented remediation), independent of current tracked-file cleanliness.

## Tracked/Untracked Expectations

- Tracked: docs, templates, non-secret defaults, code.
- Untracked: `local/.env`, `local/storage/admin-panel/legacy-settings.snapshot.php`.

## Verification Checklist

- `git ls-files -- local/.env local/storage/admin-panel/legacy-settings.snapshot.php` returns no results.
- Secret scan over tracked files returns no active credentials.
- App boots with env-provided values and passes baseline health check.
