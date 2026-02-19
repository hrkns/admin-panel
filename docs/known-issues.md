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
- Status: open
- Severity: high
- Area: runtime / platform compatibility
- Summary: Project remains on temporary PHP 7.4 compatibility runtime while modernization baseline target is PHP 8.2+.
- Reproduction (observed):
  1. Review runtime bootstrap artifacts and compose/docker definitions.
  2. Verify active compatibility image/stack points to PHP 7.4 checkpoint.
  3. Compare with Phase 0 target architecture baseline.
- Expected behavior: Runtime should meet baseline target (PHP 8.2+) with reproducible startup.
- Current behavior: Startup depends on compatibility fallback runtime.
- Current workaround: Continue running with temporary PHP 7.4 compatibility runtime until Phase 4B runtime alignment.
- Notes:
  - This is a modernization gap, not a same-phase blocker for Phase 2 configuration overhaul.
- Owner: pending assignment
- Target phase: Phase 4B

### ISSUE-2026-02-19-SECRET-HISTORY-REMEDIATION
- Date discovered: 2026-02-19
- Phase: Phase 2
- Status: open
- Severity: high
- Area: security / repository hygiene
- Summary: Current tracked files are cleaner, but historical secret exposure reports indicate continuing remediation requirements (rotation/history review).
- Reproduction (observed):
  1. Review repository/PR security scan reports for historical incidents.
  2. Confirm incidents reference prior committed content.
- Expected behavior: No active secrets in tracked files and completed incident remediation workflow (rotation/revocation/history strategy as applicable).
- Current behavior: Tracked-file hygiene is improved, but incident follow-up remains an explicit operational requirement.
- Current workaround: Maintain strict no-secret policy in tracked files and continue security incident handling workflow.
- Notes:
  - Treat this as a security operations stream parallel to code modernization.
- Owner: pending assignment
- Target phase: Phase 4B (or dedicated security remediation patch)

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
