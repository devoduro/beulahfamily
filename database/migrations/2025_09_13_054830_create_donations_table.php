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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donation_number')->unique();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->string('donor_name')->nullable(); // For anonymous or non-member donations
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('donation_type', ['tithe', 'offering', 'special_offering', 'building_fund', 'missions', 'charity', 'other'])->default('offering');
            $table->string('purpose')->nullable(); // Specific purpose or project
            $table->enum('payment_method', ['cash', 'check', 'bank_transfer', 'online', 'mobile_money', 'card'])->default('cash');
            $table->string('reference_number')->nullable(); // Check number, transaction ID, etc.
            $table->date('donation_date');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['weekly', 'monthly', 'quarterly', 'annually'])->nullable();
            $table->date('recurring_end_date')->nullable();
            $table->boolean('tax_deductible')->default(true);
            $table->string('receipt_number')->nullable();
            $table->boolean('receipt_sent')->default(false);
            $table->datetime('receipt_sent_at')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'refunded'])->default('confirmed');
            $table->timestamps();
            
            $table->index(['donation_date', 'donation_type']);
            $table->index('member_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
