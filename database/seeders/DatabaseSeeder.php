<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            VendorsSeeder::class,
            ProductsSeeder::class,
            OrdersSeeder::class, // optional
        ]);
    }
}
