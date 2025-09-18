<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add STUDENTS to the chapter enum
        DB::statement("ALTER TABLE members MODIFY COLUMN chapter ENUM('ACCRA', 'KUMASI', 'NEW JESSY', 'STUDENTS') DEFAULT 'ACCRA'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove STUDENTS from the chapter enum
        DB::statement("ALTER TABLE members MODIFY COLUMN chapter ENUM('ACCRA', 'KUMASI', 'NEW JESSY') DEFAULT 'ACCRA'");
    }
};
