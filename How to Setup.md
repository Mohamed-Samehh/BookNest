## Setting Up Database Server: WampServer with PhpMyAdmin (MySQL)

1. **Run WampServer and Access PhpMyAdmin:**
   - Start WampServer.
   - Access PhpMyAdmin from WampServer.

2. **Login to PhpMyAdmin:**
   - Use Username `"root"` and leave the password field empty if no password is set for the root user. Otherwise, enter the appropriate password.
   - Make sure Server choice is set to `"MySQL"`.

3. **Create Database:**
   - Create a new database named `"booknest"` in PhpMyAdmin.

4. **Import Database File:**
   - Click on the `"Import"` tab in PhpMyAdmin.
   - Choose the file named `"booknest.sql"` from the `"Data"` folder.
   - Execute the import to populate the `"booknest"` database.

5. **Move Project Folder:**
   - Locate the folder named `"Internet_project"`.
   - Move it to `"C:\wamp64\www"` directory.

6. **Access Your Project:**
   - To run the project, open your web browser.
   - Enter the URL: [http://localhost/Internet_project/](http://localhost/Internet_project/).
