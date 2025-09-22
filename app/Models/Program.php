<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'venue',
        'registration_fee',
        'max_participants',
        'registration_deadline',
        'status',
        'requirements',
        'terms_and_conditions',
        'contact_email',
        'contact_phone',
        'allow_file_uploads',
        'allowed_file_types',
        'max_file_size',
        'max_files',
        'flyer_path',
        'program_category',
        'registration_fields',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'requirements' => 'array',
        'allowed_file_types' => 'array',
        'allow_file_uploads' => 'boolean',
        'registration_fee' => 'decimal:2',
        'max_file_size' => 'integer',
        'max_files' => 'integer',
        'registration_fields' => 'array',
    ];

    /**
     * Get all registrations for this program
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(ProgramRegistration::class);
    }

    /**
     * Get approved registrations only
     */
    public function approvedRegistrations(): HasMany
    {
        return $this->hasMany(ProgramRegistration::class)->where('status', 'approved');
    }

    /**
     * Get pending registrations only
     */
    public function pendingRegistrations(): HasMany
    {
        return $this->hasMany(ProgramRegistration::class)->where('status', 'pending');
    }

    /**
     * Check if registration is still open
     */
    public function isRegistrationOpen(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->registration_deadline && $this->registration_deadline->isPast()) {
            return false;
        }

        if ($this->max_participants && $this->approvedRegistrations()->count() >= $this->max_participants) {
            return false;
        }

        return true;
    }

    /**
     * Get remaining slots
     */
    public function getRemainingSlots(): ?int
    {
        if (!$this->max_participants) {
            return null;
        }

        return $this->max_participants - $this->approvedRegistrations()->count();
    }

    /**
     * Get registration statistics
     */
    public function getRegistrationStats(): array
    {
        $total = $this->registrations()->count();
        $approved = $this->approvedRegistrations()->count();
        $pending = $this->pendingRegistrations()->count();
        $rejected = $this->registrations()->where('status', 'rejected')->count();

        return [
            'total' => $total,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected,
            'remaining_slots' => $this->getRemainingSlots(),
        ];
    }

    /**
     * Get business type distribution
     */
    public function getBusinessTypeDistribution(): array
    {
        return $this->registrations()
            ->selectRaw('business_type, COUNT(*) as count')
            ->groupBy('business_type')
            ->pluck('count', 'business_type')
            ->toArray();
    }

    /**
     * Scope for published programs
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for upcoming programs
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString());
    }

    /**
     * Scope for ongoing programs
     */
    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now()->toDateString())
                    ->where('end_date', '>=', now()->toDateString());
    }

    /**
     * Get formatted date range
     */
    public function getDateRangeAttribute(): string
    {
        if (!$this->start_date) {
            return 'Date TBD';
        }

        if (!$this->end_date || $this->start_date->isSameDay($this->end_date)) {
            return $this->start_date->format('M j, Y');
        }

        return $this->start_date->format('M j') . ' - ' . $this->end_date->format('M j, Y');
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute(): ?string
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        return $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'published' => 'green',
            'ongoing' => 'blue',
            'completed' => 'purple',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get program type options
     */
    public static function getProgramTypeOptions(): array
    {
        return [
            'ergates_conference' => 'ERGATES Conference',
            'annual_retreat' => 'Annual Retreat',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar',
            'conference' => 'Conference',
            'other' => 'Other',
        ];
    }

    /**
     * Get flyer URL
     */
    public function getFlyerUrlAttribute(): ?string
    {
        if (!$this->flyer_path) {
            return null;
        }
        
        return asset('storage/' . $this->flyer_path);
    }

    /**
     * Check if program has flyer
     */
    public function hasFlyer(): bool
    {
        return !empty($this->flyer_path);
    }

    /**
     * Get registration fields for program type
     */
    public function getRegistrationFieldsForType(): array
    {
        return match($this->type) {
            'ergates_conference' => [
                'business_name' => ['required' => true, 'type' => 'text', 'label' => 'Business Name'],
                'business_type' => ['required' => true, 'type' => 'select', 'label' => 'Business Type'],
                'business_type_other' => ['required' => false, 'type' => 'text', 'label' => 'Other Business Type'],
                'services_offered' => ['required' => true, 'type' => 'textarea', 'label' => 'Services Offered'],
                'business_address' => ['required' => true, 'type' => 'textarea', 'label' => 'Business Address'],
                'contact_name' => ['required' => true, 'type' => 'text', 'label' => 'Contact Name'],
                'business_phone' => ['required' => true, 'type' => 'tel', 'label' => 'Business Phone'],
                'whatsapp_number' => ['required' => false, 'type' => 'tel', 'label' => 'WhatsApp Number'],
                'email' => ['required' => true, 'type' => 'email', 'label' => 'Email Address'],
                'special_offers' => ['required' => false, 'type' => 'textarea', 'label' => 'Special Offers'],
                'additional_info' => ['required' => false, 'type' => 'textarea', 'label' => 'Additional Information'],
            ],
            'annual_retreat' => [
                'participant_name' => ['required' => true, 'type' => 'text', 'label' => 'Name'],
                'contact_phone' => ['required' => true, 'type' => 'tel', 'label' => 'Contact'],
                'residential_address' => ['required' => true, 'type' => 'textarea', 'label' => 'Residential Address'],
                'email' => ['required' => true, 'type' => 'email', 'label' => 'Email Address'],
                'how_heard_about' => ['required' => true, 'type' => 'select', 'label' => 'How did you hear of the Retreat?', 'options' => [
                    'radio_advert' => 'Radio Advert',
                    'friend' => 'Friend',
                    'family' => 'Family',
                    'social_media' => 'Social Media',
                    'church_announcement' => 'Church Announcement',
                    'website' => 'Website',
                    'other' => 'Other'
                ]],
                'dietary_requirements' => ['required' => false, 'type' => 'textarea', 'label' => 'Dietary Requirements'],
                'emergency_contact' => ['required' => false, 'type' => 'text', 'label' => 'Emergency Contact'],
                'emergency_phone' => ['required' => false, 'type' => 'tel', 'label' => 'Emergency Contact Phone'],
                'additional_info' => ['required' => false, 'type' => 'textarea', 'label' => 'Additional Information'],
            ],
            default => $this->registration_fields ?? []
        };
    }

    /**
     * Get formatted program type
     */
    public function getFormattedTypeAttribute(): string
    {
        $types = self::getProgramTypeOptions();
        return $types[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }
}
