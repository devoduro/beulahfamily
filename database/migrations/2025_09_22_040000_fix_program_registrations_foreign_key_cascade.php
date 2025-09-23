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
            // Drop the existing foreign key constraint if it exists
            $table->dropForeign(['program_id']);

            // Recreate it with CASCADE delete
            $table->foreignId('program_id')->change()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_registrations', function (Blueprint $table) {
            // Drop the CASCADE constraint
            $table->dropForeign(['program_id']);

            // Recreate it with RESTRICT (original behavior)
            $table->foreignId('program_id')->change()->constrained()->onDelete('restrict');
        });
    }
};
