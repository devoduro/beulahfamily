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
        Schema::table('program_registrations', function (Blueprint $table) {
            // Annual Retreat specific fields
            $table->string('participant_name')->nullable()->after('payment_reference');
            $table->string('contact_phone')->nullable()->after('participant_name');
            $table->text('residential_address')->nullable()->after('contact_phone');
            $table->string('how_heard_about')->nullable()->after('residential_address');
            $table->text('dietary_requirements')->nullable()->after('how_heard_about');
            $table->string('emergency_contact')->nullable()->after('dietary_requirements');
            $table->string('emergency_phone')->nullable()->after('emergency_contact');
            $table->json('registration_data')->nullable()->after('emergency_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'participant_name',
                'contact_phone', 
                'residential_address',
                'how_heard_about',
                'dietary_requirements',
                'emergency_contact',
                'emergency_phone',
                'registration_data'
            ]);
        });
    }
};
