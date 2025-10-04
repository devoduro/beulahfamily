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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('model_type')->nullable()->after('action');
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->json('properties')->nullable()->after('description');
            $table->string('session_id')->nullable()->after('user_agent');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('low')->after('session_id');
            
            // Add indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index('severity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['action', 'created_at']);
            $table->dropIndex(['model_type', 'model_id']);
            $table->dropIndex(['severity']);
            
            $table->dropColumn([
                'model_type',
                'model_id', 
                'properties',
                'session_id',
                'severity'
            ]);
        });
    }
};
