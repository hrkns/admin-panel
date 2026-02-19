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
