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
        Schema::create('event_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('qr_code_token')->unique(); // Unique token for QR code
            $table->string('qr_code_path')->nullable(); // Path to generated QR code image
            $table->timestamp('expires_at')->nullable(); // QR code expiration
            $table->boolean('is_active')->default(true);
            $table->integer('scan_count')->default(0); // Track number of scans
            $table->json('scan_logs')->nullable(); // Log of scan attempts
            $table->timestamps();
            
            $table->index(['event_id', 'is_active']);
            $table->index('qr_code_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_qr_codes');
    }
};
