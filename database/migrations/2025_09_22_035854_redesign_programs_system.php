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
        // Create program_types table for flexible program type management
        Schema::create('program_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Beulah Family Annual', 'ERGATES Conference'
            $table->string('slug')->unique(); // e.g., 'beulah_family_annual', 'ergates_conference'
            $table->text('description')->nullable();
            $table->json('registration_fields'); // Dynamic form fields configuration
            $table->json('display_settings')->nullable(); // Colors, icons, etc.
            $table->boolean('allow_file_uploads')->default(false);
            $table->json('allowed_file_types')->nullable();
            $table->integer('max_file_size')->nullable(); // in KB
            $table->integer('max_files')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Add program_type_id to programs table
        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('program_type_id')->nullable()->after('type')->constrained('program_types')->onDelete('set null');
            $table->json('custom_fields')->nullable()->after('registration_fields'); // Store custom field values
            $table->json('images')->nullable()->after('flyer_path'); // Multiple images/flyers support
        });

        // Update program_registrations table for flexible data storage
        Schema::table('program_registrations', function (Blueprint $table) {
            $table->json('form_data')->nullable()->after('registration_data'); // All form data in JSON
            $table->json('files')->nullable()->after('uploaded_files'); // Enhanced file storage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_registrations', function (Blueprint $table) {
            $table->dropColumn(['form_data', 'files']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['program_type_id']);
            $table->dropColumn(['program_type_id', 'custom_fields', 'images']);
        });

        Schema::dropIfExists('program_types');
    }
};
