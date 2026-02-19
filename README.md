# Admin-Panel

This is an admin panel template coded with Laravel and using a MySql database.

## Project Status and Modernization

This repository is currently treated as a **legacy transitional baseline**.

The current codebase reflects early-stage architecture decisions (legacy runtime/framework coupling, mutable PHP-based configuration, and manual bootstrap assumptions), so execution on modern environments may be unstable until modernization phases are completed.

Modernization work is tracked in the [upgrade branch](https://github.com/hrkns/admin-panel/tree/upgrade).

Phase 0 deliverable (target architecture + supported versions matrix):

- [docs/phase-0-target-architecture.md](docs/phase-0-target-architecture.md)

Phase 1A deliverable (bootstrap to first runnable baseline):

- [docs/phase-1a-bootstrap-first-run.md](docs/phase-1a-bootstrap-first-run.md)

Phase 1A caveat (current state):

- First runnable checkpoint currently uses a temporary PHP 7.4 compatibility runtime.
- Phase 0 runtime target (PHP 8.2+) remains an open modernization gap.

Phase 1 deliverable (stabilize + credential hygiene runbook):

- [docs/phase-1-stabilize-secure-runbook.md](docs/phase-1-stabilize-secure-runbook.md)

Phase 2 deliverable (configuration overhaul sign-off):

- [phase2/signoff.md](phase2/signoff.md)
- [docs/phase-2-env-secrets-policy.md](docs/phase-2-env-secrets-policy.md)

Phase 3 deliverable (runtime decoupling / dual web-server support):

- [docs/phase-3-runtime-decoupling.md](docs/phase-3-runtime-decoupling.md)
- [phase3/signoff.md](phase3/signoff.md)

Phase 4B deliverable (targeted runtime + security closure):

- [docs/phase-4b-runtime-and-security-closure.md](docs/phase-4b-runtime-and-security-closure.md)

Known issues tracker:

- [docs/known-issues.md](docs/known-issues.md)

Execution order note:

- If the app is not runnable yet, complete Phase 1A before Phase 1.

Versioning policy for modernization documents:

- Values with `+` (for example, PHP 8.2+, MySQL 8.0+, MariaDB 10.11+) define a minimum supported baseline in Phase 0.
- During implementation phases, exact versions/tags are pinned for reproducible builds and controlled upgrades.

Current local Docker Compose port mapping (Phase 1A file):

- Web container publishes on host port `8081` by default.
- Override with `APP_PORT` when starting compose (example: `APP_PORT=8090`).
- To persist the port without exporting each time, create root `.env` from root `.env.example` and set `APP_PORT` there.

Phase 2 configuration baseline (implemented):

- App settings now load through a compatibility adapter in `local/admin-panel-settings.php` with precedence: environment -> runtime JSON -> legacy snapshot -> defaults.
- Runtime-mutated settings are persisted in `local/storage/admin-panel/runtime-settings.json` (JSON config service), not by rewriting PHP source files.
- Environment contract is centralized in `local/config/admin-panel-config-contract.php` and includes app, DB, SMTP, paths, and feature flags.
- Secret-bearing local files (`local/.env`, `local/storage/admin-panel/legacy-settings.snapshot.php`) must stay untracked and are ignored by git.
- `local/.env.example` is template-only and must never contain active credentials, real APP_KEY values, or production secrets.
- Phase 2 exit criteria includes passing a repo secret hygiene check with no tracked secrets.

Current assessment (post-Phase 2):

- Phase 2 objective is **substantially achieved**.
- Remaining caveat: runtime target from Phase 0 (PHP 8.2+) is still open; current runnable checkpoint uses temporary PHP 7.4 compatibility runtime.
- Deferred (non-blocking): lock-screen unlock browser flow is still inconsistent and scheduled for post-phase maintenance.
- Remaining caveat: historical secret exposure risk in repository history remains a separate security remediation stream even when current tracked files are clean.

Bottom line:

- Configuration architecture is in place and ready for Phase 3.
- Phase 4B should prioritize runtime target alignment (PHP 8.2+) and secret-history remediation verification.

Phase 3 runtime checkpoint (implemented):

- App filesystem/bootstrap paths no longer depend on `$_SERVER["DOCUMENT_ROOT"]` in application helpers.
- Runtime can be launched with Apache or Nginx + PHP-FPM using `docker-compose.phase3.yml` profiles.

Phase 4B focus (current transition):

- Runtime target alignment to PHP 8.2+ baseline.
- Historical secret-remediation closure evidence.

## Historical Instructions (Deprecated)

> [!WARNING]
> The following instructions are preserved for historical/reference purposes only.
> They are **deprecated** and should generally be ignored for new setups.
> Prefer following the modernization guidance and updates from the `upgrade` branch.

**Instructions for use:**

*   Clone the project through the command `git clone https://github.com/hrkns/admin-panel.git` or download it compressed
*   Put it inside `htdocs`, `www` or some equivalent folder in your server, in order to be reachable through a web browser.
*   Load the database (tables, relations and data in the `admin_panel.sql` file) in some MySQL server.
*   In the file `FOLDER_PROJECT/local/admin-panel-settings.php`, edit the values of the fields with the `db_` prefix (`db_address` for the address of the MySQL server, `db_name` for the name of the database, `db_user` and `db_password` for the access credentials to the database).
*   Inside the folder `FOLDER_PROJECT/local` execute the command `composer install`.
*   Run the web app in the browser, you will be redirected to `URL_PROJECT/installer` in order to fill some final data.
*   After last step, you will be redirected to the _sign-in_ screen. The default user is **developer** and the password is **123456**.
