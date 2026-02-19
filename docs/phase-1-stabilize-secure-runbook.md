# Phase 1 Runbook: Stabilize & Secure Current State

## Objective
Establish a **known-good legacy baseline** that is reproducible and safer to operate before framework/code modernization.

This phase does **not** refactor the application. It only:
- Freezes current behavior into reproducible snapshots.
- Removes exposed credentials from active use.
- Verifies no behavior drift with smoke checks.

If the app is not currently runnable, complete **Phase 1A** first:
- `docs/phase-1a-bootstrap-first-run.md`

If Phase 1A reached runnability using temporary PHP 7.4 compatibility runtime, carry that caveat forward explicitly in all Phase 1 evidence/sign-off documents.

## Scope
### In scope
- Reproducible snapshot of database and app settings.
- Credential rotation (DB + SMTP) and operational hardening.
- Legacy smoke checklist execution and evidence capture.
- Baseline handoff package for Phase 2.

### Out of scope
- Laravel major upgrades.
- Architectural/code cleanup.
- Feature changes.
- Cloud/IaC migration.

## Preconditions
- Access to a currently runnable environment.
- Known runtime caveats from Phase 1A are documented (if applicable).
- Privileged access to DB server and SMTP account used by app.
- Access to a secure storage location (vault/private artifact store).
- Team agreement on a short freeze window during snapshot + rotation.

If no runnable environment exists, this runbook is blocked by design until Phase 1A is completed.

## Repository Scaffolding
This repository includes a pre-created Phase 1 workspace:
- `phase1/README.md`
- `phase1/evidence/*.md`
- `phase1/manifest/sha256.txt`
- `phase1/signoff.md`

Use these files as living execution records while running this runbook.

---

## Phase 1 Execution Plan

### Step 0 — Open a Controlled Freeze Window
Record and freeze the baseline moment:
- Git commit SHA.
- Runtime info (`php -v`, loaded extensions, DB engine version).
- Start/end timestamp of freeze window.
- Operator name.

**Evidence to capture**
- `phase1/evidence/00-runtime-fingerprint.md`
- Screenshot or console output snippets for runtime versions.

### Step 1 — Capture Reproducible Snapshot

#### 1.1 Database dump
Use a consistent dump from the active production-like source.

**MySQL/MariaDB dump template (Linux/macOS shell):**
```bash
mysqldump \
  --host="${DB_HOST}" \
  --port="${DB_PORT:-3306}" \
  --user="${DB_USER}" \
  --password="${DB_PASSWORD}" \
  --single-transaction \
  --routines --triggers --events \
  --default-character-set=utf8mb4 \
  "${DB_NAME}" > admin-panel-phase1-baseline.sql
```

**PowerShell template (Windows):**
```powershell
mysqldump `
  --host="$env:DB_HOST" `
  --port="$(if ($env:DB_PORT) { $env:DB_PORT } else { 3306 })" `
  --user="$env:DB_USER" `
  --password="$env:DB_PASSWORD" `
  --single-transaction `
  --routines --triggers --events `
  --default-character-set=utf8mb4 `
  "$env:DB_NAME" | Out-File -Encoding utf8 "admin-panel-phase1-baseline.sql"
```

#### 1.2 App settings snapshot
Capture the exact active settings file as an artifact:
- Source file: `local/admin-panel-settings.php`
- Artifact name: `admin-panel-settings.phase1.snapshot.php`

#### 1.3 Hash and manifest
Generate SHA-256 checksums for all artifacts and store in:
- `phase1/manifest/sha256.txt`

**Example:**
```text
<sha256>  admin-panel-phase1-baseline.sql
<sha256>  admin-panel-settings.phase1.snapshot.php
```

#### 1.4 Secure storage
- Compress + encrypt artifacts.
- Store only in approved private location (not in git repository).
- Record retention and access policy.

**Evidence to capture**
- `phase1/evidence/01-snapshot-created.md`
- `phase1/manifest/sha256.txt`
- Location reference ID (vault path, artifact ID), without secrets.

### Step 2 — Credential Hygiene and Rotation
Treat existing credentials as compromised if ever committed/shared.

#### 2.1 Rotate database credentials
- Create a dedicated least-privilege app DB user.
- Grant only required schema permissions.
- Set strong random password.
- Revoke old app user credentials.
- Avoid root for normal app runtime.

#### 2.2 Rotate SMTP credentials
- Generate/assign new SMTP credential (or app password).
- Revoke previous credential.
- Confirm sender identity matches policy.

#### 2.3 Update active runtime credentials
For this legacy phase, keep behavior and update the runtime configuration to use rotated credentials.

> Note: This codebase currently stores/loads DB and SMTP values from `local/admin-panel-settings.php` and can mutate them via installer/preferences flows. In Phase 1, update values safely and operationally control installer access.

#### 2.4 Validate old credentials are unusable
- Attempt authentication with old DB/SMTP credentials and confirm failure.
- Record command/output summary without exposing secrets.

**Evidence to capture**
- `phase1/evidence/02-credential-rotation.md`
- Rotation timestamp, owner, and revocation confirmation.

### Step 3 — Smoke Checks (Before/After Rotation)
Run exactly the same checklist before and after rotation. Keep this minimal and deterministic.

#### Smoke checklist
1. **Login:** root route loads and sign-in succeeds.
2. **Core section load:** at least one main section renders correctly after login.
3. **CRUD sanity:** create/read/update/delete one low-risk entity.
4. **Installer behavior:** verify expected redirect behavior when `installed=0` and document direct installer route behavior.
5. **Session flow:** logout then login again.

#### Expected result
- No functional regression in baseline operations.
- Any defect discovered is documented and triaged before Phase 2.

**Evidence to capture**
- `phase1/evidence/03-smoke-before.md`
- `phase1/evidence/04-smoke-after.md`
- Screenshots/log snippets for failed checks (if any).

### Step 4 — Recovery Drill (Mandatory)
Validate that snapshot artifacts are actually restorable.

#### Drill sequence
- Provision throwaway DB.
- Restore SQL dump.
- Apply captured settings snapshot in isolated environment.
- Bring app to login page and execute a reduced smoke subset.

#### Success criteria
- Restore completes without manual data surgery.
- App reaches login and at least one authenticated route.

**Evidence to capture**
- `phase1/evidence/05-recovery-drill.md`

### Step 5 — Baseline Sign-Off Package
Produce a compact handoff package:
- Runtime fingerprint.
- Snapshot + hash manifest + secure location ID.
- Rotation report (DB/SMTP + old secret invalidation proof).
- Smoke before/after results.
- Recovery drill result.
- Open issues (if any) and risk rating.

---

## Operational Guardrails for This Legacy Codebase
- Do not commit real credentials into repository files.
- Restrict who can edit `local/admin-panel-settings.php` during Phase 1.
- Restrict installer exposure during operations windows.
- Keep all evidence sanitized (never include plaintext passwords/tokens).

---

## Templates

### Template A — Runtime Fingerprint (`phase1/evidence/00-runtime-fingerprint.md`)
```markdown
# Runtime Fingerprint
- Date:
- Operator:
- Git SHA:
- PHP version:
- DB engine/version:
- Web server mode:
- Notes:
```

### Template B — Credential Rotation (`phase1/evidence/02-credential-rotation.md`)
```markdown
# Credential Rotation Report
- Date:
- Operator:
- DB user rotated: yes/no
- DB old credential revoked: yes/no
- SMTP credential rotated: yes/no
- SMTP old credential revoked: yes/no
- Validation of old credentials failing: pass/fail
- Notes:
```

### Template C — Smoke Result (`phase1/evidence/04-smoke-after.md`)
```markdown
# Smoke Check Results (After Rotation)
| Check | Result | Evidence | Notes |
|---|---|---|---|
| Login | pass/fail | link/screenshot id | |
| Section load | pass/fail | link/screenshot id | |
| CRUD sanity | pass/fail | link/screenshot id | |
| Installer behavior | pass/fail | link/screenshot id | |
| Logout/login | pass/fail | link/screenshot id | |
```

### Template D — Sign-Off (`phase1/signoff.md`)
```markdown
# Phase 1 Sign-Off
- Date:
- Approver(s):
- Baseline artifacts complete: yes/no
- Credential hygiene complete: yes/no
- Smoke checks passed: yes/no
- Recovery drill passed: yes/no
- Ready for Phase 2: yes/no
- Blockers/Risks:
```

---

## Exit Criteria (Phase 1 Complete)
Phase 1 is complete when all are true:
1. Reproducible DB + settings snapshots exist, hashed, encrypted, and recoverable.
2. DB/SMTP credentials are rotated, and previous credentials are invalidated.
3. Smoke checks pass before/after credential changes with recorded evidence.
4. Recovery drill succeeds.
5. Sign-off confirms a known-good legacy baseline with credential hygiene.
