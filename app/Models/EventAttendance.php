<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'member_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'registered_at',
        'checked_in_at',
        'checked_out_at',
        'attendance_status',
        'payment_amount',
        'payment_status',
        'special_requirements',
        'notes'
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'payment_amount' => 'decimal:2'
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Scopes
    public function scopeAttended($query)
    {
        return $query->where('attendance_status', 'attended');
    }

    public function scopeRegistered($query)
    {
        return $query->where('attendance_status', 'registered');
    }

    public function scopeNoShow($query)
    {
        return $query->where('attendance_status', 'no_show');
    }

    // Accessors
    public function getAttendeeNameAttribute()
    {
        return $this->member ? $this->member->full_name : $this->guest_name;
    }

    public function getIsGuestAttribute()
    {
        return !$this->member_id;
    }

    // Methods
    public function checkIn()
    {
        $this->update([
            'checked_in_at' => now(),
            'attendance_status' => 'attended'
        ]);
    }

    public function checkOut()
    {
        $this->update([
            'checked_out_at' => now()
        ]);
    }
}
