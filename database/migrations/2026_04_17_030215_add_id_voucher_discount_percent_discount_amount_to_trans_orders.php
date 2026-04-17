<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trans_orders', function (Blueprint $table) {
            $table->foreignId('id_voucher')->nullable()->constrained('vouchers')->onDelete('cascade');
            $table->integer('discount_percent')->nullable();
            $table->double('discount_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_orders', function (Blueprint $table) {
            //
        });
    }
};
