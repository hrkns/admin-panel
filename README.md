# Admin-Panel

This is an admin panel template coded with Laravel and using a MySql database.

**Instructions for use:**

*   Clone the project through the command `git clone https://github.com/hrkns/admin-panel.git` or download it compressed
*   Put it inside `htdocs`, `www` or some equivalent folder in your server, in order to be reachable through a web browser.
*   Load the database (tables, relations and data in the `admin_panel.sql` file) in some MySQL server.
*   In the file `FOLDER_PROJECT/local/admin-panel-settings.php`, edit the values of the fields with the `db_` prefix (`db_address` for the address of the MySQL server, `db_name` for the name of the database, `db_user` and `db_password` for the access credentials to the database).
*   Inside the folder `FOLDER_PROJECT/local` execute the command `composer install`.
*   Run the web app in the browser, you will be redirected to `URL_PROJECT/installer` in order to fill some final data.
*   After last step, you will be redirected to the _sign-in_ screen. The default user is **developer** and the password is **123456**.
