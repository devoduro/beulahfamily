<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Member extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'member_id',
        'first_name',
        'last_name',
        'middle_name',
        'title',
        'email',
        'phone',
        'whatsapp_phone',
        'alternate_phone',
        'date_of_birth',
        'gender',
        'marital_status',
        'church_affiliation',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'occupation',
        'employer',
        'membership_date',
        'membership_status',
        'membership_type',
        'chapter',
        'family_id',
        'relationship_to_head',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_conditions',
        'special_needs',
        'photo_path',
        'notes',
        'is_baptized',
        'baptism_date',
        'is_confirmed',
        'confirmation_date',
        'skills_talents',
        'interests',
        'receive_newsletter',
        'receive_sms',
        'is_active',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'password',
        'force_password_change',
        'password_changed_at',
        'registration_token',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'membership_date' => 'date',
        'baptism_date' => 'date',
        'confirmation_date' => 'date',
        'approved_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'skills_talents' => 'array',
        'interests' => 'array',
        'is_baptized' => 'boolean',
        'is_confirmed' => 'boolean',
        'receive_newsletter' => 'boolean',
        'receive_sms' => 'boolean',
        'is_active' => 'boolean',
        'force_password_change' => 'boolean'
    ];

    // Relationships
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function ministries()
    {
        return $this->belongsToMany(Ministry::class, 'member_ministry')
                    ->withPivot(['role', 'joined_date', 'left_date', 'is_active', 'notes'])
                    ->withTimestamps();
    }

    public function activeMinistries()
    {
        return $this->ministries()->wherePivot('is_active', true);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function eventAttendances()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function ledMinistries()
    {
        return $this->hasMany(Ministry::class, 'leader_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByMembershipStatus($query, $status)
    {
        return $query->where('membership_status', $status);
    }

    public function scopeByMembershipType($query, $type)
    {
        return $query->where('membership_type', $type);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByAgeRange($query, $minAge, $maxAge)
    {
        $minDate = now()->subYears($maxAge)->format('Y-m-d');
        $maxDate = now()->subYears($minAge)->format('Y-m-d');
        
        return $query->whereBetween('date_of_birth', [$minDate, $maxDate]);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        $name = $this->first_name . ' ' . $this->last_name;
        return $this->title ? $this->title . ' ' . $name : $name;
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getTotalDonationsAttribute()
    {
        return $this->donations()->sum('amount');
    }

    public function getYearlyDonationsAttribute()
    {
        return $this->donations()
                    ->whereYear('donation_date', now()->year)
                    ->sum('amount');
    }

    // Methods
    public function generateMemberId()
    {
        $year = now()->year;
        $lastMember = static::where('member_id', 'like', $year . '%')
                           ->orderBy('member_id', 'desc')
                           ->first();
        
        if ($lastMember) {
            $lastNumber = (int) substr($lastMember->member_id, 4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }
        
        return $year . $nextNumber;
    }

    public function isMinor()
    {
        return $this->age && $this->age < 18;
    }

    public function canReceiveCommunication($type = 'email')
    {
        if ($type === 'email') {
            return $this->receive_newsletter && !empty($this->email);
        }
        
        if ($type === 'sms') {
            return $this->receive_sms && !empty($this->phone);
        }
        
        return false;
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes for approval status
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    // Helper methods
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    public function needsPasswordChange()
    {
        return $this->force_password_change;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($member) {
            if (empty($member->member_id)) {
                $member->member_id = $member->generateMemberId();
            }
        });
    }
}
