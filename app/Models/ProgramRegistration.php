<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'member_id',
        'business_name',
        'business_type',
        'business_type_other',
        'services_offered',
        'business_address',
        'contact_name',
        'business_phone',
        'whatsapp_number',
        'email',
        'special_offers',
        'additional_info',
        'uploaded_files',
        'status',
        'registered_at',
        'admin_notes',
        'amount_paid',
        'payment_status',
        'payment_reference',
        // Annual Retreat specific fields
        'participant_name',
        'contact_phone',
        'residential_address',
        'how_heard_about',
        'dietary_requirements',
        'emergency_contact',
        'emergency_phone',
        'registration_data',
    ];

    protected $casts = [
        'uploaded_files' => 'array',
        'registered_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'registration_data' => 'array',
    ];

    /**
     * Get the program this registration belongs to
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the member who registered (if applicable)
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get business type options
     */
    public static function getBusinessTypeOptions(): array
    {
        return [
            'retail' => 'Retail (e.g. stores, shops)',
            'wholesale' => 'Wholesale (e.g. distributors)',
            'manufacturing' => 'Manufacturing (e.g. factories, producers)',
            'service' => 'Service (e.g. consulting, accounting)',
            'hospitality' => 'Hospitality (e.g. hotels, restaurants)',
            'healthcare' => 'Healthcare (e.g. hospitals, clinics)',
            'technology' => 'Technology (e.g. software, hardware)',
            'finance' => 'Finance (e.g. banking, insurance)',
            'nonprofit' => 'Nonprofit (e.g. charitable organizations)',
            'transportation' => 'Transportation (e.g. logistics, shipping)',
            'education' => 'Education (e.g. schools, universities)',
            'construction' => 'Construction (e.g. general contractors, subcontractors)',
            'agriculture' => 'Agriculture (e.g. farming, ranching)',
            'energy' => 'Energy (e.g. oil, gas, renewable energy)',
            'media' => 'Media (e.g. publishing, broadcasting)',
            'legal' => 'Legal (e.g. law firms, legal services)',
            'real_estate' => 'Real Estate (e.g. brokerage, development)',
            'arts_entertainment' => 'Arts and Entertainment (e.g. museums, galleries, performing arts)',
            'government' => 'Government (e.g. local, state, federal)',
            'research_development' => 'Research and Development (e.g. laboratories, research institutions)',
            'advertising_marketing' => 'Advertising and Marketing (e.g. agencies, public relations)',
            'professional_services' => 'Professional Services (e.g. accounting, consulting)',
            'other' => 'Other',
        ];
    }

    /**
     * Get formatted business type
     */
    public function getFormattedBusinessTypeAttribute(): string
    {
        $options = self::getBusinessTypeOptions();
        
        if ($this->business_type === 'other' && $this->business_type_other) {
            return 'Other: ' . $this->business_type_other;
        }
        
        return $options[$this->business_type] ?? ucfirst(str_replace('_', ' ', $this->business_type));
    }

    /**
     * Get business type label (alias for formatted_business_type)
     */
    public function getBusinessTypeLabel(): string
    {
        return $this->getFormattedBusinessTypeAttribute();
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get payment status badge color
     */
    public function getPaymentStatusColorAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'yellow',
            'paid' => 'green',
            'partial' => 'orange',
            'refunded' => 'red',
            default => 'gray',
        };
    }

    /**
     * Check if registration has uploaded files
     */
    public function hasUploadedFiles(): bool
    {
        return !empty($this->uploaded_files);
    }

    /**
     * Get uploaded files count
     */
    public function getUploadedFilesCount(): int
    {
        return count($this->uploaded_files ?? []);
    }

    /**
     * Get file upload summary
     */
    public function getFileUploadSummary(): string
    {
        if (!$this->hasUploadedFiles()) {
            return 'No files uploaded';
        }

        $count = $this->getUploadedFilesCount();
        return $count . ' file' . ($count > 1 ? 's' : '') . ' uploaded';
    }

    /**
     * Scope for approved registrations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending registrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid registrations
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope for specific business type
     */
    public function scopeBusinessType($query, $type)
    {
        return $query->where('business_type', $type);
    }

    /**
     * Get contact information summary
     */
    public function getContactSummaryAttribute(): string
    {
        $contact = $this->contact_name;
        if ($this->business_phone) {
            $contact .= ' • ' . $this->business_phone;
        }
        if ($this->email) {
            $contact .= ' • ' . $this->email;
        }
        return $contact;
    }

    /**
     * Get registration summary for display
     */
    public function getRegistrationSummaryAttribute(): string
    {
        if ($this->program && $this->program->type === 'annual_retreat') {
            return $this->participant_name ?: $this->contact_name ?: 'Unknown Participant';
        }
        
        return $this->business_name . ' (' . $this->formatted_business_type . ')';
    }

    /**
     * Get how heard about options for annual retreat
     */
    public static function getHowHeardAboutOptions(): array
    {
        return [
            'radio_advert' => 'Radio Advert',
            'friend' => 'Friend',
            'family' => 'Family',
            'social_media' => 'Social Media',
            'church_announcement' => 'Church Announcement',
            'website' => 'Website',
            'other' => 'Other'
        ];
    }

    /**
     * Get formatted how heard about
     */
    public function getFormattedHowHeardAboutAttribute(): string
    {
        $options = self::getHowHeardAboutOptions();
        return $options[$this->how_heard_about] ?? ucfirst(str_replace('_', ' ', $this->how_heard_about));
    }

    /**
     * Get primary contact name based on program type
     */
    public function getPrimaryContactNameAttribute(): string
    {
        if ($this->program && $this->program->type === 'annual_retreat') {
            return $this->participant_name ?: 'Unknown Participant';
        }
        
        return $this->contact_name ?: 'Unknown Contact';
    }

    /**
     * Get primary contact phone based on program type
     */
    public function getPrimaryContactPhoneAttribute(): string
    {
        if ($this->program && $this->program->type === 'annual_retreat') {
            return $this->contact_phone ?: '';
        }
        
        return $this->business_phone ?: '';
    }

    /**
     * Get primary address based on program type
     */
    public function getPrimaryAddressAttribute(): string
    {
        if ($this->program && $this->program->type === 'annual_retreat') {
            return $this->residential_address ?: '';
        }
        
        return $this->business_address ?: '';
    }
}
