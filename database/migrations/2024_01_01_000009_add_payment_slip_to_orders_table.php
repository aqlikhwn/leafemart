<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_slip')->nullable()->after('payment_method');
            $table->enum('payment_status', ['pending', 'uploaded', 'approved', 'rejected'])->default('pending')->after('payment_slip');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_slip', 'payment_status']);
        });
    }
};
