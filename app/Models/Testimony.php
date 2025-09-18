<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimony extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'title',
        'content',
        'category',
        'is_public',
        'is_approved',
        'approved_at',
        'approved_by',
        'tags',
        'likes_count'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'tags' => 'array',
        'likes_count' => 'integer'
    ];

    /**
     * Get the member who submitted this testimony
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the user who approved this testimony
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for public testimonies
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for approved testimonies
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for published testimonies (public and approved)
     */
    public function scopePublished($query)
    {
        return $query->where('is_public', true)->where('is_approved', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get category display name
     */
    public function getCategoryDisplayAttribute()
    {
        $categories = [
            'healing' => 'Healing & Health',
            'financial' => 'Financial Breakthrough',
            'spiritual' => 'Spiritual Growth',
            'family' => 'Family & Relationships',
            'career' => 'Career & Education',
            'protection' => 'Divine Protection',
            'other' => 'Other Blessings'
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    /**
     * Get excerpt of content
     */
    public function getExcerptAttribute($length = 150)
    {
        return strlen($this->content) > $length 
            ? substr($this->content, 0, $length) . '...' 
            : $this->content;
    }
}
