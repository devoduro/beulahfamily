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
        Schema::create('program_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            
            // Business Information
            $table->string('business_name');
            $table->enum('business_type', [
                'retail', 'wholesale', 'manufacturing', 'service', 'hospitality',
                'healthcare', 'technology', 'finance', 'nonprofit', 'transportation',
                'education', 'construction', 'agriculture', 'energy', 'media',
                'legal', 'real_estate', 'arts_entertainment', 'government',
                'research_development', 'advertising_marketing', 'professional_services', 'other'
            ]);
            $table->string('business_type_other')->nullable();
            $table->text('services_offered');
            $table->text('business_address');
            
            // Contact Information
            $table->string('contact_name');
            $table->string('business_phone');
            $table->string('whatsapp_number')->nullable();
            $table->string('email');
            
            // Additional Information
            $table->text('special_offers')->nullable();
            $table->text('additional_info')->nullable();
            
            // File uploads
            $table->json('uploaded_files')->nullable(); // Store file paths and metadata
            
            // Registration metadata
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamp('registered_at')->useCurrent();
            $table->text('admin_notes')->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'partial', 'refunded'])->default('pending');
            $table->string('payment_reference')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['program_id', 'status']);
            $table->index(['member_id']);
            $table->index('registered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_registrations');
    }
};
