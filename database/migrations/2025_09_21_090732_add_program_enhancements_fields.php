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
        Schema::table('programs', function (Blueprint $table) {
            $table->string('flyer_path')->nullable()->after('max_files');
            $table->string('program_category')->nullable()->after('flyer_path');
            $table->json('registration_fields')->nullable()->after('program_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['flyer_path', 'program_category', 'registration_fields']);
        });
    }
};
