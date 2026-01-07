<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Food', 'icon' => '🍔', 'description' => 'Snacks, instant noodles, biscuits, and more'],
            ['name' => 'Drink', 'icon' => '🥤', 'description' => 'Beverages, milk, juices, and water'],
            ['name' => 'Toiletries', 'icon' => '🧴', 'description' => 'Soap, shampoo, toothpaste, and personal care'],
            ['name' => 'Stationery', 'icon' => '📝', 'description' => 'Pens, notebooks, paper, and office supplies'],
            ['name' => 'Medication', 'icon' => '💊', 'description' => 'Basic medicine and health products'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
