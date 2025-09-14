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
        Schema::create('ministries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('leader_id')->nullable()->constrained('members')->onDelete('set null');
            $table->string('meeting_day')->nullable();
            $table->time('meeting_time')->nullable();
            $table->string('meeting_location')->nullable();
            $table->enum('ministry_type', ['worship', 'youth', 'children', 'men', 'women', 'seniors', 'outreach', 'education', 'administration', 'other'])->default('other');
            $table->integer('target_age_min')->nullable();
            $table->integer('target_age_max')->nullable();
            $table->enum('target_gender', ['all', 'male', 'female'])->default('all');
            $table->text('requirements')->nullable();
            $table->text('goals')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('name');
            $table->index('ministry_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ministries');
    }
};
