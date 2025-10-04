<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
        'session_id',
        'severity'
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected by the activity
     */
    public function subject()
    {
        return $this->morphTo('model');
    }

    /**
     * Log an activity
     */
    public static function log(array $attributes): self
    {
        // Add request information if available
        if (request()) {
            $attributes['ip_address'] = $attributes['ip_address'] ?? request()->ip();
            $attributes['user_agent'] = $attributes['user_agent'] ?? request()->userAgent();
            $attributes['session_id'] = $attributes['session_id'] ?? session()->getId();
        }

        // Add authenticated user if available
        if (auth()->check() && !isset($attributes['user_id'])) {
            $attributes['user_id'] = auth()->id();
            $attributes['user_type'] = auth()->user()->role ?? 'member';
        } elseif (!isset($attributes['user_id'])) {
            // Set user_id to null for system actions
            $attributes['user_id'] = null;
            $attributes['user_type'] = $attributes['user_type'] ?? 'system';
        }

        return self::create($attributes);
    }

    /**
     * Scope for filtering by action
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering by severity
     */
    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope for recent activities
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
