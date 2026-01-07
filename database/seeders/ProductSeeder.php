<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            // Food
            ['category' => 'Food', 'name' => 'Maggi Mee Goreng', 'price' => 1.50, 'stock' => 50, 'featured' => true, 'description' => 'Instant noodles - spicy flavor'],
            ['category' => 'Food', 'name' => 'Mamee Monster', 'price' => 2.00, 'stock' => 30, 'featured' => true, 'description' => 'Crunchy snack noodles'],
            ['category' => 'Food', 'name' => 'Oreo Cookies', 'price' => 4.50, 'stock' => 25, 'featured' => false, 'description' => 'Chocolate sandwich cookies'],
            ['category' => 'Food', 'name' => 'Gardenia Bread', 'price' => 3.50, 'stock' => 20, 'featured' => true, 'description' => 'Fresh white bread loaf'],
            ['category' => 'Food', 'name' => 'Koko Krunch', 'price' => 12.00, 'stock' => 15, 'featured' => false, 'description' => 'Chocolate flavored cereal'],

            // Drink
            ['category' => 'Drink', 'name' => 'Milo 3in1', 'price' => 8.50, 'stock' => 40, 'featured' => true, 'description' => 'Chocolate malt drink - 10 sachets'],
            ['category' => 'Drink', 'name' => 'Spritzer Water 1.5L', 'price' => 2.00, 'stock' => 100, 'featured' => false, 'description' => 'Natural mineral water'],
            ['category' => 'Drink', 'name' => 'Dutch Lady Milk', 'price' => 4.50, 'stock' => 35, 'featured' => true, 'description' => 'Fresh milk 1L'],
            ['category' => 'Drink', 'name' => 'Nescafe Original', 'price' => 15.00, 'stock' => 20, 'featured' => false, 'description' => 'Instant coffee - 25 sticks'],
            ['category' => 'Drink', 'name' => '100 Plus', 'price' => 2.50, 'stock' => 60, 'featured' => false, 'description' => 'Isotonic drink 500ml'],

            // Toiletries
            ['category' => 'Toiletries', 'name' => 'Colgate Toothpaste', 'price' => 6.50, 'stock' => 30, 'featured' => true, 'description' => 'Fresh mint toothpaste 150g'],
            ['category' => 'Toiletries', 'name' => 'Head & Shoulders', 'price' => 12.00, 'stock' => 25, 'featured' => false, 'description' => 'Anti-dandruff shampoo 180ml'],
            ['category' => 'Toiletries', 'name' => 'Dettol Soap', 'price' => 3.50, 'stock' => 45, 'featured' => true, 'description' => 'Antibacterial bar soap'],
            ['category' => 'Toiletries', 'name' => 'Tissue Box', 'price' => 5.00, 'stock' => 50, 'featured' => false, 'description' => '200 sheets facial tissue'],

            // Stationery
            ['category' => 'Stationery', 'name' => 'A4 Paper Ream', 'price' => 12.00, 'stock' => 20, 'featured' => false, 'description' => '500 sheets white paper'],
            ['category' => 'Stationery', 'name' => 'Pilot Pen Blue', 'price' => 2.50, 'stock' => 100, 'featured' => true, 'description' => 'Smooth writing ballpoint pen'],
            ['category' => 'Stationery', 'name' => 'Hardcover Notebook', 'price' => 8.00, 'stock' => 40, 'featured' => true, 'description' => '200 pages ruled notebook'],
            ['category' => 'Stationery', 'name' => 'Stapler', 'price' => 7.50, 'stock' => 15, 'featured' => false, 'description' => 'Standard desk stapler'],

            // Medication
            ['category' => 'Medication', 'name' => 'Panadol', 'price' => 5.50, 'stock' => 30, 'featured' => true, 'description' => 'Pain relief tablets 10s'],
            ['category' => 'Medication', 'name' => 'Strepsils', 'price' => 6.00, 'stock' => 25, 'featured' => false, 'description' => 'Sore throat lozenges'],
            ['category' => 'Medication', 'name' => 'Plaster Band-Aid', 'price' => 4.50, 'stock' => 40, 'featured' => false, 'description' => 'Adhesive bandages 20pcs'],
        ];

        foreach ($products as $productData) {
            $category = $categories->firstWhere('name', $productData['category']);
            Product::create([
                'category_id' => $category->id,
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                'featured' => $productData['featured'],
                'active' => true,
            ]);
        }
    }
}
