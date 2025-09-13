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
        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('guest_name')->nullable(); // For non-members
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->datetime('registered_at')->nullable();
            $table->datetime('checked_in_at')->nullable();
            $table->datetime('checked_out_at')->nullable();
            $table->enum('attendance_status', ['registered', 'attended', 'no_show', 'cancelled'])->default('registered');
            $table->decimal('payment_amount', 8, 2)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'refunded', 'waived'])->default('pending');
            $table->text('special_requirements')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['event_id', 'member_id']);
            $table->index(['event_id', 'attendance_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendances');
    }
};
