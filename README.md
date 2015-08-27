- Inside project directory, install the application dependencies using command: `composer install`.
- Copy .env.example to .env.
- Make the require changes and update your database configurations in .env file.
- Run migration using: `php artisan migrate`.
- Give read/write permission to the storage folder using command: `chmod -R 777 storage`.
- Create a directory uploads inside public folder and, files and temp directory inside the uploads directory.
- Give read/write permission to uploads directory recurrsively using: `chmod 777 -R uploads/`.

Login access: 
Email : dev@aidstream.com.np
Password : admin123