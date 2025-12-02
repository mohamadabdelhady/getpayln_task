<?php

namespace Database\Seeders;

use App\Models\products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        products::create([
            'name' => 'Product A',
            'description' => 'Description for Product A',
            'price' => 100.00,
            'stock_available' => 50,
        ]);
        products::create([
            'name' => 'Product B',
            'description' => 'Description for Product B',
            'price' => 150.00,
            'stock_available' => 30,
        ]);
        products::create([
            'name' => 'Product C',
            'description' => 'Description for Product C',  
            'price' => 200.00,
            'stock_available' => 20,
        ]); 
        products::create([
            'name' => 'Product D',
            'description' => 'Description for Product D',  
            'price' => 250.00,
            'stock_available' => 10,
        ]);
        products::create([
            'name' => 'Product E',
            'description' => 'Description for Product E',  
            'price' => 300.00,
            'stock_available' => 5,
        ]);
    }
}
