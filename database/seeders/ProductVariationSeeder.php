<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Database\Seeder;

class ProductVariationSeeder extends Seeder
{
    public function run(): void
    {
        // Find the Pilot Pen product
        $pen = Product::where('name', 'like', '%Pilot%')->first();
        
        if ($pen) {
            // Clear existing variations
            $pen->variations()->delete();
            
            // Add color variations
            ProductVariation::create([
                'product_id' => $pen->id,
                'name' => 'Blue',
                'stock' => 50,
                'price_adjustment' => 0,
            ]);

            ProductVariation::create([
                'product_id' => $pen->id,
                'name' => 'Black',
                'stock' => 50,
                'price_adjustment' => 0,
            ]);

            ProductVariation::create([
                'product_id' => $pen->id,
                'name' => 'Red',
                'stock' => 30,
                'price_adjustment' => 0,
            ]);

            ProductVariation::create([
                'product_id' => $pen->id,
                'name' => 'Green',
                'stock' => 20,
                'price_adjustment' => 0.50, // Green is 50 sen more expensive
            ]);
        }

        // Add variations to another product for demo
        $notebook = Product::where('name', 'like', '%Notebook%')->first();
        
        if ($notebook) {
            $notebook->variations()->delete();
            
            ProductVariation::create([
                'product_id' => $notebook->id,
                'name' => 'A4 Size',
                'stock' => 30,
                'price_adjustment' => 0,
            ]);

            ProductVariation::create([
                'product_id' => $notebook->id,
                'name' => 'A5 Size',
                'stock' => 40,
                'price_adjustment' => -1.00, // A5 is RM1 cheaper
            ]);

            ProductVariation::create([
                'product_id' => $notebook->id,
                'name' => 'B5 Size',
                'stock' => 25,
                'price_adjustment' => 0.50,
            ]);
        }
    }
}
