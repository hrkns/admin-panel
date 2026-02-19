# Known Issues Tracker

Use this document to track defects discovered during modernization phases that are deferred or out of scope for the current phase.

## Status legend
- `open`: confirmed and pending fix
- `in-progress`: under active work
- `blocked`: cannot proceed due to external dependency
- `resolved`: fixed and validated

## Issues

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
- Owner: pending assignment
- Target phase: Phase 3 (or dedicated bugfix patch)
