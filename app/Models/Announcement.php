<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'created_by',
        'publish_date',
        'expire_date',
        'target_audience',
        'send_email',
        'send_sms',
        'display_on_website',
        'display_on_screens',
        'image_path',
        'attachment_path',
        'status',
        'view_count'
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'expire_date' => 'datetime',
        'target_audience' => 'array',
        'send_email' => 'boolean',
        'send_sms' => 'boolean',
        'display_on_website' => 'boolean',
        'display_on_screens' => 'boolean',
        'view_count' => 'integer'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                    ->where('publish_date', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('expire_date')
                          ->orWhere('expire_date', '>=', now());
                    });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeForWebsite($query)
    {
        return $query->where('display_on_website', true);
    }

    public function scopeForScreens($query)
    {
        return $query->where('display_on_screens', true);
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'published' 
               && $this->publish_date <= now()
               && ($this->expire_date === null || $this->expire_date >= now());
    }

    public function getIsExpiredAttribute()
    {
        return $this->expire_date && $this->expire_date < now();
    }

    // Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'publish_date' => now()
        ]);
    }

    public function archive()
    {
        $this->update(['status' => 'archived']);
    }

    public function expire()
    {
        $this->update(['status' => 'expired']);
    }
}
