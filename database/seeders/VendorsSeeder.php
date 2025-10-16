<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorsSeeder extends Seeder
{

    public function run(): void
    {
        Vendor::truncate();
        
        $vendors = [
            "L'Oréal SA",
            "Harry's Inc.",
            "Johnson & Johnson Services Inc",
        ];

        foreach ($vendors as $vendorName) {
            Vendor::create(['name' => $vendorName]);
        }
    }
}
