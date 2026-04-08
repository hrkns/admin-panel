# Admin-Panel

Admin-Panel is a legacy administrative back-office application built on Laravel and MySQL. This repository now provides a stable local startup path, a consolidated modernization history, and a clear roadmap for the next branch of work.

## Current Status

- The Docker-based local baseline is working through the Phase 5 modernization checkpoint.
- The supported local entrypoint is `scripts/startup.ps1`.
- Modernization history, sign-offs, roadmap, and related runbooks are grouped under `modernization/`.

More information:

- Modernization overview: [modernization/docs/overview.md](modernization/docs/overview.md)
- Roadmap and future phases: [modernization/roadmap.md](modernization/roadmap.md)
- Known issues: [modernization/docs/known-issues.md](modernization/docs/known-issues.md)

## Startup

Prerequisites:

- Docker Desktop or another Docker Engine + Compose v2 compatible runtime
- PowerShell

From the repository root:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\startup.ps1
```

Optional flags:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\startup.ps1 -Rebuild
powershell -ExecutionPolicy Bypass -File .\scripts\startup.ps1 -ResetDb
powershell -ExecutionPolicy Bypass -File .\scripts\startup.ps1 -KeepExistingSmtp
```

Default local endpoints:

- App: `http://localhost:8081`
- Health: `http://localhost:8081/health/live`
- Mail UI: `http://localhost:8025`

To change the default app port, set `APP_PORT` in the root `.env` file. Use `.env.example` as the template.
