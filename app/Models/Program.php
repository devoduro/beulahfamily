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
}
