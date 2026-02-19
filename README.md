# Admin-Panel

This is an admin panel template coded with Laravel and using a MySql database.

## Project Status and Modernization

This repository is currently treated as a **legacy transitional baseline**.

The current codebase reflects early-stage architecture decisions (legacy runtime/framework coupling, mutable PHP-based configuration, and manual bootstrap assumptions), so execution on modern environments may be unstable until modernization phases are completed.

Modernization work is tracked in the [upgrade branch](https://github.com/hrkns/admin-panel/tree/upgrade).

Phase 0 deliverable (target architecture + supported versions matrix):

- [docs/phase-0-target-architecture.md](docs/phase-0-target-architecture.md)

Versioning policy for modernization documents:

- Values with `+` (for example, PHP 8.2+, MySQL 8.0+, MariaDB 10.11+) define a minimum supported baseline in Phase 0.
- During implementation phases, exact versions/tags are pinned for reproducible builds and controlled upgrades.

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
