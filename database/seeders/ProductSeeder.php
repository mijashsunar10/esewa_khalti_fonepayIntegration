<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Test Product 1',
            'slug' => 'test-product-1',
            'price' => 1000.00,
            'description' => 'This is a test product for Khalti integration'
        ]);

        Product::create([
            'name' => 'Test Product 2',
            'slug' => 'test-product-2',
            'price' => 1500.00,
            'description' => 'Another test product for Khalti integration'
        ]);
    }
}