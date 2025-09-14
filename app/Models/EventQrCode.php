<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventQrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'qr_code_token',
        'qr_code_path',
        'expires_at',
        'is_active',
        'scan_count',
        'scan_logs'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'scan_logs' => 'array'
    ];

    /**
     * Get the event that this QR code belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Generate a unique QR code token
     */
    public static function generateToken()
    {
        do {
            $token = Str::random(32);
        } while (self::where('qr_code_token', $token)->exists());

        return $token;
    }

    /**
     * Check if QR code is valid and active
     */
    public function isValid()
    {
        return $this->is_active && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Increment scan count and log scan attempt
     */
    public function logScan($memberInfo = null, $success = true)
    {
        $this->increment('scan_count');
        
        $logs = $this->scan_logs ?? [];
        $logs[] = [
            'timestamp' => now()->toISOString(),
            'member_info' => $memberInfo,
            'success' => $success,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];
        
        $this->update(['scan_logs' => $logs]);
    }

    /**
     * Scope for active QR codes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for expired QR codes
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Get QR code URL for scanning
     */
    public function getQrUrlAttribute()
    {
        return route('attendance.scan', ['token' => $this->qr_code_token]);
    }

    /**
     * Deactivate QR code
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
        return $this;
    }

    /**
     * Set expiration time
     */
    public function setExpiration($hours = 24)
    {
        $this->update(['expires_at' => now()->addHours($hours)]);
        return $this;
    }
}
