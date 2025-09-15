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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['conference', 'workshop', 'seminar', 'retreat', 'other'])->default('conference');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('venue')->nullable();
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->integer('max_participants')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->json('requirements')->nullable(); // Store specific form requirements
            $table->text('terms_and_conditions')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('allow_file_uploads')->default(false);
            $table->json('allowed_file_types')->nullable(); // ['pdf', 'image', 'video', 'audio']
            $table->integer('max_file_size')->default(100); // in MB
            $table->integer('max_files')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
