# Modernization Roadmap

This document records the modernization phases, their goals, and their current status. Phases 6 through 9 are the planned continuation points for future branches and pull requests.

## Completed Phases

| Phase | Status | Goal | Primary references |
|---|---|---|---|
| Phase 0 | complete | Define the target architecture, supported versions, and compatibility contract for the program of work. | `modernization/docs/phase-0-target-architecture.md` |
| Phase 1A | complete | Recover a first runnable local baseline. | `modernization/docs/phase-1a-bootstrap-first-run.md`, `modernization/phase1a/signoff.md` |
| Phase 1 | complete | Stabilize the recovered baseline and capture backup, smoke, and credential-hygiene procedures. | `modernization/docs/phase-1-stabilize-secure-runbook.md`, `modernization/phase1/signoff.md` |
| Phase 2 | complete | Move configuration and secret handling into an environment-driven contract with safer persistence. | `modernization/docs/phase-2-env-secrets-policy.md`, `modernization/phase2/signoff.md` |
| Phase 3 | complete | Decouple runtime assumptions and support Apache plus Nginx/PHP-FPM profiles. | `modernization/docs/phase-3-runtime-decoupling.md`, `modernization/phase3/signoff.md` |
| Phase 3B | complete | Close the PHP 8.2+ runtime target gap and the secret-remediation closure stream. | `modernization/docs/phase-3b-runtime-and-security-closure.md`, `modernization/phase3b/signoff.md` |
| Phase 4 | complete | Move schema ownership and deterministic baseline data bootstrap into Laravel migrations and seeders. | `modernization/docs/phase-4-database-portability-and-data-layer-hardening.md`, `modernization/phase4/signoff.md` |
| Phase 5 | complete | Provide a repeatable local developer experience with startup automation, health checks, and local mail capture. | `modernization/docs/phase-5-developer-experience-and-automation.md`, `modernization/phase5/signoff.md` |

## Planned Phases

The remaining phases are the agreed next slices after this branch merges.

| Phase | Status | Goal | Notes |
|---|---|---|---|
| Phase 6 | proposed | Add quality gates before large-scale refactor work. | Includes linting and static analysis, high-value integration tests, and CI automation so later changes are safer. |
| Phase 7 | proposed | Refactor the legacy codebase incrementally by domain. | Replace generated-style model patterns and break large route definitions into modular units one vertical slice at a time. |
| Phase 8 | proposed | Execute the framework upgrade path with controlled validation. | Prefer stepped Laravel upgrades; if direct jumps are too risky, use a strangler-style path for new modules. |
| Phase 9 | proposed | Finish rollout, operations, and production-readiness documentation. | Add logging, error tracking, backups, rollback guidance, and deploy/runbook documentation. |

## Planned Deliverables

### Phase 6: Quality Gates Before Refactor

Goal:
- Add safe guardrails before iterative refactor work starts.

Scope:
- Add linting and static analysis where feasible, including PHPStan or Psalm, coding standards, and JavaScript linting for the custom application code.
- Add high-value integration tests for authentication, session handling, and critical CRUD flows.
- Add a CI pipeline covering build, lint, tests, and smoke verification.

Deliverable:
- Safe guardrails for iterative changes.

Implementation note:
- Existing repo analysis indicates there is no JavaScript linting infrastructure today, so JS lint setup should be treated as one component of this phase rather than the entire phase.

### Phase 7: Incremental Code Refactor (Domain-by-Domain)

Goal:
- Improve architecture without destabilizing the whole legacy system in one pass.

Scope:
- Replace generated-style model patterns such as `__create__`, `__update__`, and `__delete__` with service or repository boundaries plus events or audit middleware where appropriate.
- Refactor one vertical slice at a time, starting with high-value domains such as authentication and users.
- Reduce large route arrays in `routes_requests.php` into modular route files.

Deliverable:
- Cleaner architecture without breaking the whole system at once.

### Phase 8: Framework Upgrade Path

Goal:
- Move from the legacy framework baseline to a modern supported framework/runtime with controlled risk.

Scope:
- Use a stepped upgrade path from Laravel 5.1 through intermediary versions to a modern LTS.
- Validate the application after each upgrade hop.
- If a direct upgrade path proves too risky, keep the legacy core stable while moving new modules behind a strangler-style boundary in a modern application.

Deliverable:
- Modern supported framework and runtime.

### Phase 9: Rollout, Operations, and Documentation

Goal:
- Bring the system to production-ready operational maturity.

Scope:
- Add structured logging and error tracking.
- Define backup and rollback procedures.
- Document deployment, runbook, and migration procedures.

Deliverable:
- Production-ready operational maturity.

## After Phase 9

- Additional phases are intentionally undefined for now and should be planned after the outcome of the framework-upgrade and operations work is known.

## Cross-Cutting Backlog

- Lock-screen unlock browser flow remains open and is tracked in `modernization/docs/known-issues.md`.
- Any newly discovered secret-history or security-remediation work should be opened as a new issue before being folded into a future phase.