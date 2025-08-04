composer install
npm install
npm run build

copy .env.example into .env
configure database/APP_LOCALE/APP_NAME in .env

php artisan key:generate

php artisan migrate
It will create the default administrator account with username: admin password:admin

php artisan serve
