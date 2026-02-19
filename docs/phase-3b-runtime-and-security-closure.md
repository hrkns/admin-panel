# Phase 3B Runbook: Runtime Alignment and Security Remediation Closure

## Purpose

Phase 3B is an intermediary stabilization phase focused exclusively on closing two cross-cutting modernization risks:

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

Phase 3B is complete when all are true:

1. App runs on PHP 8.2+ in supported runtime profile(s).
2. Known runtime-target issue is marked `resolved` with evidence.
3. Secret-history remediation issue is marked `resolved` (or approved risk acceptance is documented).
4. Phase 3B sign-off confirms readiness to continue the remaining roadmap phases.

## Deliverables

- Runtime updates and validation evidence for PHP 8.2+.
- Security remediation closure record for historical secret exposure.
- Phase 3B sign-off document (recommended path: `phase3b/signoff.md`).

## Implementation Checkpoint (Current)

- Runtime images aligned to PHP 8.2+ for both Phase 3 profiles:
	- `docker/php/Dockerfile.phase3-apache`
	- `docker/php/Dockerfile.phase3-fpm`
- Phase 3 compose Apache profile now builds from `docker/php/Dockerfile.phase3-apache`.
- Validation evidence captured in `phase3b/signoff.md` (compose profile resolution + image build verification + live smoke checks).
- Legacy Laravel compatibility hotfix applied in `local/vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php` to prevent PHP 8.2 deprecation notices from becoming blocking exceptions.
- Live smoke evidence in `phase3b/signoff.md` shows HTTP 200 on both profiles under PHP 8.2.
- Security remediation closure record captured in:
	- `phase3b/evidence/01-secret-history-remediation-closure.md`

## Sign-Off Artifacts

- `phase3b/signoff.md`
- `phase3b/evidence/01-secret-history-remediation-closure.md`

## Position in Roadmap

- Phase 3: runtime decoupling.
- Phase 4: already defined (existing scope remains unchanged).
- **Phase 3B**: targeted closure of runtime + security blockers.
