Для встановлення проекту:

Вимоги
- php >= 8.2
- composer
- laravel >= 11.0
- mysql

- `composer create-project "laravel/laravel:^11.0" serafim`
- `cd serafim`
- `git clone `https://github.com/vladolewko/serafim.git`
- `composer install`
- `npm install`
- dublicate file .env.example and make it .env
-  php artisan key:generate
- Configure your database in the .env file:
  - DB_DATABASE=serafim
  - DB_USERNAME=root
  - DB_PASSWORD=password
  - DB_HOST=127.0.0.1
  - DB_PORT=8889

- `php artisan migrate`
- `php artisan db:seed`

Для запуску
----------------
- `npm run dev`