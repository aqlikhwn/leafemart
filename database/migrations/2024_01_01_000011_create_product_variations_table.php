<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Blue", "Black", "Red"
            $table->decimal('price_adjustment', 10, 2)->default(0); // Optional price difference
            $table->integer('stock')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Add selected_variation to carts table
        Schema::table('carts', function (Blueprint $table) {
            $table->string('variation')->nullable()->after('product_id');
        });

        // Add selected_variation to order_items table  
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('variation')->nullable()->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('variation');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('variation');
        });

        Schema::dropIfExists('product_variations');
    }
};
