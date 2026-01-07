<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Try to drop the old unique constraint - try different possible names
        try {
            DB::statement('ALTER TABLE carts DROP INDEX carts_user_id_product_id_unique');
        } catch (\Exception $e) {
            // Index might not exist with this name, try alternative
            try {
                DB::statement('ALTER TABLE carts DROP INDEX user_id');
            } catch (\Exception $e2) {
                // Ignore if neither exists
            }
        }
        
        // Remove any duplicate entries before adding new constraint
        // Keep the entry with the latest created_at
        DB::statement('
            DELETE c1 FROM carts c1
            INNER JOIN carts c2 
            WHERE c1.id < c2.id 
            AND c1.user_id = c2.user_id 
            AND c1.product_id = c2.product_id 
            AND COALESCE(c1.variation, "") = COALESCE(c2.variation, "")
        ');
        
        // Add new unique constraint including variation
        DB::statement('CREATE UNIQUE INDEX carts_user_product_variation_unique ON carts (user_id, product_id, variation(191))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE carts DROP INDEX carts_user_product_variation_unique');
        } catch (\Exception $e) {
            // Ignore
        }
        
        Schema::table('carts', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id']);
        });
    }
};
