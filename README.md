👤 Default Sample customer/admin credentials

Login as Admin user →
admin@sample.com
admin

Login as Customer →
altrone@sample.com
altrone

🧩 1. Laravel Setup

    composer create-project laravel/laravel multi-vendor-app
    cd multi-vendor-app
    
⚙️ 2. Install dependencies

    composer install
    npm install

🔑 3. Models & Migrations 

    php artisan make:migration add_role_to_users_table --table=users
    php artisan migrate

🗄️ 4. Seed database
    php artisan make:seeder ProductsSeeder (example)
    php artisan migrate:fresh --seed

🧹 5. Clear caches (optional)
    php artisan optimize:clear

🧱 6. Build assets
    npm run build
    # or
    npm run dev

🚀 7. Run app
    php artisan serve


Open 👉 http://127.0.0.1:8000
