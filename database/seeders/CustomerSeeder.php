<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::truncate();
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@sample.com'], // look for existing user
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'), // hashed password
                'role' => 'admin',
            ]
        );

        // Customer user
        User::updateOrCreate(
            ['email' => 'customer@sample.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('customer'),
                'role' => 'customer',
            ]
        );

        // Altrone user
        User::updateOrCreate(
            ['email' => 'altrone@sample.com'],
            [
                'name' => 'Altrone',
                'password' => Hash::make('altrone'),
                'role' => 'customer',
            ]
        );
    }
}
