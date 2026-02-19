# Phase 4B Runbook: Runtime Alignment and Security Remediation Closure

## Purpose

Phase 4B is an intermediary stabilization phase focused exclusively on closing two cross-cutting modernization risks:

1. Runtime target alignment to PHP 8.2+.
2. Historical secret-exposure remediation closure.

This phase intentionally excludes minor functional defects that do not block platform modernization.

## Scope

### In Scope

- Upgrade runtime/container definitions and compatibility patches required for PHP 8.2+ baseline execution.
- Validate startup and core smoke flow under PHP 8.2+ runtime.
- Complete operational closure evidence for historical secret exposure (rotation/revocation/history strategy and verification artifacts).

### Out of Scope

- Lock-screen unlock defect (explicitly deferred as non-blocking backlog).
- Broad feature refactors.
- Architectural redesign beyond what is needed to close runtime/security blockers.

## Exit Criteria

Phase 4B is complete when all are true:

1. App runs on PHP 8.2+ in supported runtime profile(s).
2. Known runtime-target issue is marked `resolved` with evidence.
3. Secret-history remediation issue is marked `resolved` (or approved risk acceptance is documented).
4. Phase 4B sign-off confirms readiness to continue the remaining roadmap phases.

## Deliverables

- Runtime updates and validation evidence for PHP 8.2+.
- Security remediation closure record for historical secret exposure.
- Phase 4B sign-off document (recommended path: `phase4b/signoff.md`).

## Position in Roadmap

- Phase 3: runtime decoupling.
- Phase 4: already defined (existing scope remains unchanged).
- **Phase 4B**: targeted closure of runtime + security blockers.
