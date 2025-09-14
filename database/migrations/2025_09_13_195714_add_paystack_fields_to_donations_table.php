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
            $table->string('paystack_reference')->nullable()->after('status');
            $table->string('paystack_transaction_id')->nullable()->after('paystack_reference');
            $table->string('paystack_access_code')->nullable()->after('paystack_transaction_id');
            $table->string('payment_channel')->nullable()->after('paystack_access_code'); // card, bank, mobile_money
            $table->json('payment_gateway_response')->nullable()->after('payment_channel');
            $table->decimal('transaction_fee', 10, 2)->nullable()->after('payment_gateway_response');
            $table->decimal('net_amount', 10, 2)->nullable()->after('transaction_fee');
            
            $table->index('paystack_reference');
            $table->index('paystack_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropIndex(['paystack_reference']);
            $table->dropIndex(['paystack_transaction_id']);
            $table->dropColumn([
                'paystack_reference',
                'paystack_transaction_id', 
                'paystack_access_code',
                'payment_channel',
                'payment_gateway_response',
                'transaction_fee',
                'net_amount'
            ]);
        });
    }
};
