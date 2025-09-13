<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ministry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'meeting_day',
        'meeting_time',
        'meeting_location',
        'ministry_type',
        'target_age_min',
        'target_age_max',
        'target_gender',
        'requirements',
        'goals',
        'budget',
        'is_active'
    ];

    protected $casts = [
        'meeting_time' => 'datetime',
        'target_age_min' => 'integer',
        'target_age_max' => 'integer',
        'budget' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function leader()
    {
        return $this->belongsTo(Member::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_ministry')
                    ->withPivot(['role', 'joined_date', 'left_date', 'is_active', 'notes'])
                    ->withTimestamps();
    }

    public function activeMembers()
    {
        return $this->members()->wherePivot('is_active', true);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('ministry_type', $type);
    }

    public function scopeByTargetGender($query, $gender)
    {
        return $query->where('target_gender', $gender);
    }

    // Accessors
    public function getTotalMembersAttribute()
    {
        return $this->members()->count();
    }

    public function getTotalActiveMembersAttribute()
    {
        return $this->activeMembers()->count();
    }

    public function getUpcomingEventsAttribute()
    {
        return $this->events()
                    ->where('start_datetime', '>=', now())
                    ->where('status', 'published')
                    ->orderBy('start_datetime')
                    ->limit(5)
                    ->get();
    }

    // Methods
    public function addMember(Member $member, $role = 'member', $joinedDate = null)
    {
        $this->members()->attach($member->id, [
            'role' => $role,
            'joined_date' => $joinedDate ?? now(),
            'is_active' => true
        ]);
    }

    public function removeMember(Member $member, $leftDate = null)
    {
        $this->members()->updateExistingPivot($member->id, [
            'left_date' => $leftDate ?? now(),
            'is_active' => false
        ]);
    }

    public function isEligibleMember(Member $member)
    {
        // Check age requirements
        if ($this->target_age_min && $member->age < $this->target_age_min) {
            return false;
        }
        
        if ($this->target_age_max && $member->age > $this->target_age_max) {
            return false;
        }
        
        // Check gender requirements
        if ($this->target_gender !== 'all' && $member->gender !== $this->target_gender) {
            return false;
        }
        
        return true;
    }
}
