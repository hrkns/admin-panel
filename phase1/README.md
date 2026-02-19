# Phase 1 Working Folder

This folder stores execution artifacts for **Phase 1: Stabilize & Secure Current State**.

## Structure
- `evidence/`: operational evidence documents.
- `manifest/`: integrity metadata (hashes/checksums).
- `signoff.md`: final Phase 1 approval record.

## Notes
- Do not store plaintext secrets in these files.
- If binary artifacts are generated (DB dumps, encrypted archives), keep them out of git and store only secure location references here.
- Follow the runbook in `docs/phase-1-stabilize-secure-runbook.md`.

## Quick Start (Prefilled)
- Date: 2026-02-19
- Branch: upgrade
- Git SHA: 76c9062

Prerequisite:
- If the app is not currently runnable, complete `docs/phase-1a-bootstrap-first-run.md` before using this folder.
- If Phase 1A was completed with temporary PHP 7.4 compatibility runtime, record that caveat in Phase 1 evidence and keep it visible until resolved.

### Start here
1. Complete `evidence/00-runtime-fingerprint.md` Step 0 checklist.
2. Run smoke-before checks and fill `evidence/03-smoke-before.md`.
3. Capture snapshots and fill `evidence/01-snapshot-created.md`.
