<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'title',
        'description',
        'category',
        'urgency',
        'is_anonymous',
        'is_private',
        'status',
        'answer_testimony',
        'answered_at',
        'prayer_count',
        'total_prayers',
        'expires_at'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_private' => 'boolean',
        'answered_at' => 'datetime',
        'prayer_count' => 'array',
        'total_prayers' => 'integer',
        'expires_at' => 'date'
    ];

    /**
     * Get the member who submitted this prayer request
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Scope for active prayer requests
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now()->toDateString());
                    });
    }

    /**
     * Scope for public prayer requests
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Scope by urgency
     */
    public function scopeByUrgency($query, $urgency)
    {
        return $query->where('urgency', $urgency);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get urgency color class
     */
    public function getUrgencyColorAttribute()
    {
        $colors = [
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'urgent' => 'bg-red-100 text-red-800'
        ];

        return $colors[$this->urgency] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get category display name
     */
    public function getCategoryDisplayAttribute()
    {
        $categories = [
            'health' => 'Health & Healing',
            'family' => 'Family & Relationships',
            'financial' => 'Financial Needs',
            'spiritual' => 'Spiritual Growth',
            'career' => 'Career & Education',
            'relationship' => 'Relationships',
            'protection' => 'Protection & Safety',
            'guidance' => 'Guidance & Direction',
            'other' => 'Other Requests'
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    /**
     * Check if prayer request is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->expires_at, false));
    }

    /**
     * Add prayer from member
     */
    public function addPrayer($memberId)
    {
        $prayers = $this->prayer_count ?? [];
        
        if (!in_array($memberId, $prayers)) {
            $prayers[] = $memberId;
            $this->update([
                'prayer_count' => $prayers,
                'total_prayers' => count($prayers)
            ]);
        }
    }

    /**
     * Check if member has prayed
     */
    public function hasPrayed($memberId)
    {
        $prayers = $this->prayer_count ?? [];
        return in_array($memberId, $prayers);
    }

    /**
     * Mark as answered
     */
    public function markAsAnswered($testimony = null)
    {
        $this->update([
            'status' => 'answered',
            'answered_at' => now(),
            'answer_testimony' => $testimony
        ]);
    }
}
