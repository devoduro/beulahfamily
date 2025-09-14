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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['general', 'urgent', 'event', 'ministry', 'prayer_request', 'celebration'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->datetime('publish_date')->default(now());
            $table->datetime('expire_date')->nullable();
            $table->json('target_audience')->nullable(); // ministries, age groups, etc.
            $table->boolean('send_email')->default(false);
            $table->boolean('send_sms')->default(false);
            $table->boolean('display_on_website')->default(true);
            $table->boolean('display_on_screens')->default(false);
            $table->string('image_path')->nullable();
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['draft', 'published', 'expired', 'archived'])->default('draft');
            $table->text('notes')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'publish_date']);
            $table->index('type');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
