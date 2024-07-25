




This is WilcoERP platform's backend API and Admin bashboard system in Laravel framework.
--------------------------------------------------------------------------------------------
Esta é a API backend da plataforma WilcoERP e o sistema Admin bashboard na estrutura Laravel.
--------------------------------------------------------------------------------------------

composer dump-autoload
php artisan db:seed --class=PageTableSeeder

php artisan make:migration create_permission_table --create=permission
php artisan migrate

php artisan route:clear

