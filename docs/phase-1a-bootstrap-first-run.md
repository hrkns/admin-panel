# Phase 1A Runbook: Bootstrap to First Runnable Baseline

## Objective
Bring the legacy application from **not runnable** to a **first successful startup** with minimal, reversible changes.

This phase exists because there is no current working environment to freeze yet.

Runtime caveat for current execution:
- First runnable checkpoint may require a temporary compatibility runtime (currently PHP 7.4 in this repository state).
- This does not replace the Phase 0 runtime target (PHP 8.2+), which remains pending for subsequent modernization phases.

## Outcome of Phase 1A
At the end of this phase, all of the following are true:
1. Application starts and reaches at least the login screen.
2. Core route flow is reachable (root route and authenticated path).
3. A minimal startup log exists with exact environment and steps used.
4. The resulting state is ready to enter Phase 1 (stabilize + secure).

## Scope
### In scope
- Environment bootstrap for local reproducible run.
- Dependency/runtime alignment to the current legacy code requirements.
- First-run diagnostics and unblock actions.
- First-run evidence capture.

### Out of scope
- Framework major upgrades.
- Refactors and cleanup.
- Feature changes.
- Security hardening beyond what is strictly needed to boot.

## Inputs
- Current repository branch and commit SHA.
- Existing SQL baseline file (`admin_panel.sql`) when applicable.
- Existing local settings file (`local/admin-panel-settings.php`) as temporary bootstrap source.

---

## Execution Plan

### Step 0 — Capture Current Non-Working State
Document what fails now before changing anything:
- Current error message(s).
- How startup was attempted.
- Runtime currently installed on machine.

**Evidence**
- `phase1a/evidence/00-current-failure.md`

### Step 1 — Build Minimal Local Runtime
Prepare a local runtime matching the modernization baseline as closely as possible without refactoring:
- Preferred target: PHP 8.2+ runtime with required extensions.
- Temporary fallback allowed for first boot only: PHP 7.4 compatibility runtime when legacy framework/runtime incompatibilities block startup.
- MySQL 8.0+ or MariaDB 10.11+ instance.
- Web entrypoint through project root (`index.php`).

Record exact versions and commands used.

**Evidence**
- `phase1a/evidence/01-runtime-setup.md`

### Step 2 — Install Legacy Dependencies
Inside `local/`:
- Run dependency install (`composer install`) and record any version conflicts.
- If install fails, document exact package blockers and chosen workaround.

**Evidence**
- `phase1a/evidence/02-dependencies.md`

### Step 3 — Prepare Database and Settings for First Boot
- Create database and import `admin_panel.sql`.
- Configure bootstrap DB and SMTP placeholders in `local/admin-panel-settings.php` sufficient for startup.
- Keep changes minimal and reversible.

**Evidence**
- `phase1a/evidence/03-db-and-settings.md`

### Step 4 — First Successful Startup
Attempt startup and validate:
- Root route responds.
- Login view renders.
- Installer behavior is observed and documented.

If startup fails, iterate with smallest possible fixes and capture each iteration.

**Evidence**
- `phase1a/evidence/04-first-startup.md`

### Step 5 — First Runnable Checkpoint
When app is first runnable, record checkpoint metadata:
- Branch + commit SHA.
- Runtime versions.
- Startup command(s).
- Known limitations still present.

This checkpoint becomes the input for Phase 1.

**Evidence**
- `phase1a/signoff.md`

---

## Exit Criteria (Phase 1A Complete)
Phase 1A is complete when all are true:
1. App starts and reaches login screen locally.
2. Root route and at least one authenticated flow are reachable.
3. Bootstrap steps are documented and repeatable.
4. First runnable checkpoint is signed off.
5. Team can proceed to Phase 1 runbook.

Documented caveat requirement:
- If Phase 1A used PHP 7.4 fallback, this must be explicitly recorded in `phase1a/signoff.md` as an unresolved gap against the PHP 8.2+ target.
