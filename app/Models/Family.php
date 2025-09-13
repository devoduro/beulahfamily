<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'family_name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'home_phone',
        'email',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function activeMembers()
    {
        return $this->members()->where('is_active', true);
    }

    public function head()
    {
        return $this->hasOne(Member::class)->where('relationship_to_head', 'head');
    }

    public function spouse()
    {
        return $this->hasOne(Member::class)->where('relationship_to_head', 'spouse');
    }

    public function children()
    {
        return $this->hasMany(Member::class)->where('relationship_to_head', 'child');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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

    public function getTotalDonationsAttribute()
    {
        return $this->members()->with('donations')->get()
                    ->sum(function ($member) {
                        return $member->donations->sum('amount');
                    });
    }

    public function getYearlyDonationsAttribute()
    {
        return $this->members()->with(['donations' => function ($query) {
                        $query->whereYear('donation_date', now()->year);
                    }])->get()
                    ->sum(function ($member) {
                        return $member->donations->sum('amount');
                    });
    }

    // Methods
    public function getFullAddress()
    {
        $addressParts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);
        
        return implode(', ', $addressParts);
    }
}
