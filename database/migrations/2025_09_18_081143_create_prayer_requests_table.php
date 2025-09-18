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
        Schema::create('prayer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['health', 'family', 'financial', 'spiritual', 'career', 'relationship', 'protection', 'guidance', 'other'])->default('other');
            $table->enum('urgency', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_private')->default(false);
            $table->enum('status', ['active', 'answered', 'closed'])->default('active');
            $table->text('answer_testimony')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->json('prayer_count')->nullable(); // Track who prayed
            $table->integer('total_prayers')->default(0);
            $table->date('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'is_private']);
            $table->index('category');
            $table->index('urgency');
            $table->index('created_at');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_requests');
    }
};
