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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->timestamp('checked_in_at');
            $table->timestamp('checked_out_at')->nullable();
            $table->string('attendance_method')->default('qr_code'); // qr_code, manual, mobile_app
            $table->string('device_info')->nullable(); // Device used for scanning
            $table->string('ip_address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
            
            // Prevent duplicate attendance for same member and event
            $table->unique(['event_id', 'member_id']);
            
            $table->index(['event_id', 'checked_in_at']);
            $table->index('member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
