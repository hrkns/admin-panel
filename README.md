# Admin-Panel

This is an admin panel template coded with Laravel and using a MySql database.

**Instructions for use:**

*   Clone the project through the command `git clone https://github.com/hrkns/admin-panel.git` or download it compressed.
*   Put it inside `htdoc`, `www` or some equivalent folder in your server.
*   Load the database (tables, relations and data in the `admin_panel.sql` file) in some MySQL server.
*   Open the file `PROJECT_FOLDER/local/admin-panel-settings.php` and set the fields with `db_` prefix.
*   From the command line and being inside the `local` folder, run `composer install`.
*   Run the web app in the browser, you will be redirected to `URL_PROJECT/installer` in order to fill some final data.
*   After last step, you will be redirected to the sign-in screen. The default user is **developer** and the password is **123456**.
