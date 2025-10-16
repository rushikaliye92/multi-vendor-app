<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        Product::truncate();

        $vendors = Vendor::all();

        if ($vendors->isEmpty()) {
            $this->command->info('No vendors found. Please seed vendors first.');
            return;
        }

        $productsByVendor = [
            "L'Oréal SA" => [
                'Shampoo',
                'Conditioner',
                'Hair Oil',
                'Face Cream',
                'Lipstick'
            ],
            "Johnson & Johnson Services Inc" => [
                'Baby Powder',
                'Shampoo',
                'Baby Lotion',
                'Bandages',
                'First Aid Kit'
            ],
            "Harry's Inc." => [
                'Brush',
                'Sunglass',
                'Oil',
                'Cream',
                'Powder'
            ],
        ];

        foreach ($vendors as $vendor) {
            if (!isset($productsByVendor[$vendor->name])) continue;

            foreach ($productsByVendor[$vendor->name] as $productName) {
                Product::create([
                    'vendor_id' => $vendor->id,
                    'name' => $productName,
                    'price' => rand(50, 500),
                    'stock' => rand(5, 15),
                ]);
            }
        }

        $this->command->info('Products seeded for each vendor.');
    }
}