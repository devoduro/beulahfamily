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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('title')->nullable(); // Mr, Mrs, Dr, Pastor, etc.
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Nigeria');
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->date('membership_date')->nullable();
            $table->enum('membership_status', ['active', 'inactive', 'transferred', 'deceased'])->default('active');
            $table->enum('membership_type', ['member', 'visitor', 'friend', 'associate'])->default('member');
            $table->foreignId('family_id')->nullable()->constrained()->onDelete('set null');
            $table->string('relationship_to_head')->nullable(); // head, spouse, child, other
            $table->text('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('special_needs')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_baptized')->default(false);
            $table->date('baptism_date')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->date('confirmation_date')->nullable();
            $table->json('skills_talents')->nullable();
            $table->json('interests')->nullable();
            $table->boolean('receive_newsletter')->default(true);
            $table->boolean('receive_sms')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['first_name', 'last_name']);
            $table->index('membership_status');
            $table->index('membership_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
