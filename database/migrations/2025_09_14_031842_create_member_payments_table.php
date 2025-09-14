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
        Schema::create('member_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->string('payment_reference')->unique();
            $table->enum('payment_type', ['tithe', 'offering', 'welfare', 'building_fund', 'special_offering', 'thanksgiving', 'other']);
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money', 'cheque', 'card', 'online'])->default('cash');
            $table->string('receipt_number')->nullable();
            $table->text('description')->nullable();
            $table->string('transaction_id')->nullable(); // For online payments
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('confirmed');
            $table->boolean('sms_sent')->default(false);
            $table->timestamp('sms_sent_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['member_id', 'payment_date']);
            $table->index(['payment_type', 'payment_date']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_payments');
    }
};
