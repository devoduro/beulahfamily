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
        Schema::create('gateway_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('gateway_type', ['payment', 'sms']);
            $table->string('provider'); // paystack, mnotify, etc.
            $table->string('name'); // Display name
            $table->text('description')->nullable();
            $table->json('settings'); // Store API keys, endpoints, etc.
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->integer('priority')->default(0); // For ordering
            $table->timestamps();
            
            $table->unique(['gateway_type', 'provider']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateway_settings');
    }
};
