<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'member_id',
        'checked_in_at',
        'checked_out_at',
        'attendance_method',
        'device_info',
        'ip_address',
        'notes',
        'is_verified'
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    /**
     * Get the event that this attendance belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the member that this attendance belongs to.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Scope for today's attendance
     */
    public function scopeToday($query)
    {
        return $query->whereDate('checked_in_at', Carbon::today());
    }

    /**
     * Scope for this week's attendance
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('checked_in_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    /**
     * Scope for this month's attendance
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('checked_in_at', Carbon::now()->month)
                    ->whereYear('checked_in_at', Carbon::now()->year);
    }

    /**
     * Scope for verified attendance
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Get attendance duration in minutes
     */
    public function getDurationAttribute()
    {
        if (!$this->checked_out_at) {
            return null;
        }
        
        return $this->checked_in_at->diffInMinutes($this->checked_out_at);
    }

    /**
     * Check if member is still checked in
     */
    public function getIsActiveAttribute()
    {
        return is_null($this->checked_out_at);
    }

    /**
     * Mark attendance as checked out
     */
    public function checkOut()
    {
        $this->update(['checked_out_at' => now()]);
        return $this;
    }

    /**
     * Get attendance statistics for an event
     */
    public static function getEventStats($eventId)
    {
        return [
            'total_attendance' => self::where('event_id', $eventId)->verified()->count(),
            'checked_in' => self::where('event_id', $eventId)->verified()->whereNull('checked_out_at')->count(),
            'checked_out' => self::where('event_id', $eventId)->verified()->whereNotNull('checked_out_at')->count(),
            'qr_scans' => self::where('event_id', $eventId)->where('attendance_method', 'qr_code')->count(),
            'manual_entries' => self::where('event_id', $eventId)->where('attendance_method', 'manual')->count(),
        ];
    }
}
