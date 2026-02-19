# Known Issues Tracker

Use this document to track defects discovered during modernization phases that are deferred or out of scope for the current phase.

## Status legend
- `open`: confirmed and pending fix
- `in-progress`: under active work
- `blocked`: cannot proceed due to external dependency
- `resolved`: fixed and validated

## Issues

### ISSUE-2026-02-19-RUNTIME-TARGET-GAP
- Date discovered: 2026-02-19
- Phase: Phase 2
- Status: resolved
- Severity: high
- Area: runtime / platform compatibility
- Summary: Runtime moved from PHP 7.4 compatibility baseline to PHP 8.2+ and validated via live startup smoke checks on both Phase 3 profiles.
- Reproduction (observed):
  1. Review runtime bootstrap artifacts and compose/docker definitions.
  2. Verify active compatibility image/stack points to PHP 7.4 checkpoint.
  3. Compare with Phase 0 target architecture baseline.
- Expected behavior: Runtime should meet baseline target (PHP 8.2+) with reproducible startup.
- Current behavior: App responds successfully on both Phase 3 startup profiles under PHP 8.2.
- Current workaround: none required.
- Notes:
  - Phase 3B completed runtime image alignment (`docker/php/Dockerfile.phase3-apache`, `docker/php/Dockerfile.phase3-fpm`) and live startup validation.
  - Compatibility hotfix applied in `local/vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php` to prevent PHP 8.2 deprecation notices from being escalated into blocking exceptions.
  - Live smoke evidence: HTTP `200` on Apache (`:8081`) and Nginx (`:8082`) with PHP `8.2.30`.
- Owner: pending assignment
- Target phase: Phase 3B

### ISSUE-2026-02-19-SECRET-HISTORY-REMEDIATION
- Date discovered: 2026-02-19
- Phase: Phase 2
- Status: resolved
- Severity: high
- Area: security / repository hygiene
- Summary: Current tracked files are cleaner, but historical secret exposure reports indicate continuing remediation requirements (rotation/history review).
- Reproduction (observed):
  1. Review repository/PR security scan reports for historical incidents.
  2. Confirm incidents reference prior committed content.
- Expected behavior: No active secrets in tracked files and completed incident remediation workflow (rotation/revocation/history strategy as applicable).
- Current behavior: Tracked-file hygiene is enforced, closure workflow is documented, and PR security checks report no active secret findings.
- Current workaround: none required.
- Notes:
  - Treated as a security operations stream parallel to code modernization.
  - Closure record captured in `phase3b/evidence/01-secret-history-remediation-closure.md` and referenced by `phase3b/signoff.md`.
  - Active PR check context now reports `GitGuardian Security Checks` success (`No secrets detected ✅`).
- Owner: pending assignment
- Target phase: Phase 3B

### ISSUE-2026-02-19-LOCKSCREEN-UNLOCK
- Date discovered: 2026-02-19
- Phase: Phase 2
- Status: open
- Severity: medium
- Area: authentication / session lock flow
- Summary: Lock-screen page allows logout, but unlocking with valid password is inconsistent in browser flow.
- Reproduction (observed):
  1. Authenticate to panel.
  2. Enter lock screen.
  3. Submit valid password in unlock form.
  4. Unlock fails and user remains blocked unless signing out.
- Expected behavior: Valid password should clear lock state and return to panel.
- Current workaround: Use sign out from lock screen, then log in again.
- Notes:
  - Redirect loop (`lock-screen` <-> `logout`) was fixed.
  - Remaining issue appears specific to unlock validation/request flow.
  - Explicitly classified as non-blocking for modernization phases; defer until post-phase maintenance.
- Owner: pending assignment
- Target phase: Post-phase maintenance backlog (non-blocking)

## Issue Template

Use this block when adding a new issue:

```markdown
### ISSUE-YYYY-MM-DD-SHORT-NAME
- Date discovered: YYYY-MM-DD
- Phase: Phase X
- Status: open
- Severity: low|medium|high|critical
- Area: subsystem / module
- Summary: One-line description.
- Reproduction (observed):
  1. Step one
  2. Step two
  3. Step three
- Expected behavior: What should happen.
- Current behavior: What actually happens.
- Current workaround: Temporary mitigation, if any.
- Notes: Any diagnostics, links, traces, or context.
- Owner: pending assignment
- Target phase: Phase X or bugfix patch
```
