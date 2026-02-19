# Admin-Panel

This is an admin panel template coded with Laravel and using a MySql database.

## Legacy Project Notice

This repository is currently maintained as a **legacy codebase**.

Its present architecture reflects an early implementation stage and contains multiple patterns that are not aligned with modern software engineering and security standards. As a result, execution in contemporary environments can be difficult and unstable.

The main technical reasons are:

- tight coupling to a classic Apache + document-root deployment model;
- direct dependency on legacy runtime and framework versions;
- configuration and operational settings stored in mutable PHP files instead of environment-driven configuration;
- historical bootstrap assumptions based on manual SQL import and manual server configuration;
- limited reproducibility for local onboarding and platform portability.

For these reasons, this repository should be treated as a transitional legacy baseline.

## Modernization and Stabilization Plan

A structured upgrade and stabilization process is being tracked in the **`upgrade`** branch:

- Upgrade branch: https://github.com/hrkns/admin-panel/tree/upgrade

The objective of that branch is to progressively deliver:

- modernized configuration and secret handling;
- improved portability across modern platforms;
- safer execution defaults;
- progressive refactoring toward a maintainable architecture.

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
