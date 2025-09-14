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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('event_type', ['service', 'meeting', 'conference', 'workshop', 'social', 'outreach', 'fundraising', 'other'])->default('other');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->boolean('is_all_day')->default(false);
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('ministry_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('organizer_id')->nullable()->constrained('members')->onDelete('set null');
            $table->integer('max_attendees')->nullable();
            $table->decimal('registration_fee', 8, 2)->nullable();
            $table->boolean('requires_registration')->default(false);
            $table->datetime('registration_deadline')->nullable();
            $table->text('special_instructions')->nullable();
            $table->json('required_items')->nullable(); // Items attendees should bring
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurrence_type', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->integer('recurrence_interval')->nullable(); // Every X days/weeks/months/years
            $table->date('recurrence_end_date')->nullable();
            $table->json('recurrence_days')->nullable(); // For weekly: [1,3,5] for Mon,Wed,Fri
            $table->string('image_path')->nullable();
            $table->boolean('send_reminders')->default(true);
            $table->integer('reminder_days_before')->default(1);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['start_datetime', 'end_datetime']);
            $table->index('event_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
