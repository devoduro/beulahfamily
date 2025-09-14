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
        Schema::table('donations', function (Blueprint $table) {
            // Drop the existing enum column and recreate with updated values
            $table->dropColumn('payment_method');
        });
        
        Schema::table('donations', function (Blueprint $table) {
            // Add the updated payment_method column with proper enum values
            $table->enum('payment_method', [
                'cash', 
                'check', 
                'cheque',
                'bank_transfer', 
                'online', 
                'mobile_money', 
                'card',
                'paystack'
            ])->default('cash')->after('purpose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
        
        Schema::table('donations', function (Blueprint $table) {
            // Restore original enum values
            $table->enum('payment_method', [
                'cash', 
                'check', 
                'bank_transfer', 
                'online', 
                'mobile_money', 
                'card'
            ])->default('cash')->after('purpose');
        });
    }
};
