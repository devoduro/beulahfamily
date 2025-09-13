<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'event_type',
        'start_datetime',
        'end_datetime',
        'is_all_day',
        'location',
        'address',
        'ministry_id',
        'organizer_id',
        'max_attendees',
        'registration_fee',
        'requires_registration',
        'registration_deadline',
        'special_instructions',
        'required_items',
        'status',
        'is_recurring',
        'recurrence_type',
        'recurrence_interval',
        'recurrence_end_date',
        'recurrence_days',
        'image_path',
        'send_reminders',
        'reminder_days_before'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'registration_deadline' => 'datetime',
        'recurrence_end_date' => 'date',
        'is_all_day' => 'boolean',
        'requires_registration' => 'boolean',
        'is_recurring' => 'boolean',
        'send_reminders' => 'boolean',
        'registration_fee' => 'decimal:2',
        'max_attendees' => 'integer',
        'reminder_days_before' => 'integer',
        'recurrence_interval' => 'integer',
        'required_items' => 'array',
        'recurrence_days' => 'array'
    ];

    // Relationships
    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Member::class, 'organizer_id');
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(Member::class, 'event_attendances')
                    ->withPivot(['registered_at', 'checked_in_at', 'attendance_status', 'payment_status'])
                    ->withTimestamps();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('end_datetime', '<', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeByMinistry($query, $ministryId)
    {
        return $query->where('ministry_id', $ministryId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_datetime', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('start_datetime', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    // Accessors
    public function getTotalAttendeesAttribute()
    {
        return $this->attendances()->count();
    }

    public function getConfirmedAttendeesAttribute()
    {
        return $this->attendances()->where('attendance_status', 'attended')->count();
    }

    public function getRegisteredAttendeesAttribute()
    {
        return $this->attendances()->where('attendance_status', 'registered')->count();
    }

    public function getAvailableSpotsAttribute()
    {
        if (!$this->max_attendees) {
            return null;
        }
        
        return $this->max_attendees - $this->total_attendees;
    }

    public function getIsPastAttribute()
    {
        return $this->end_datetime < now();
    }

    public function getIsUpcomingAttribute()
    {
        return $this->start_datetime >= now();
    }

    public function getIsTodayAttribute()
    {
        return $this->start_datetime->isToday();
    }

    public function getDurationAttribute()
    {
        return $this->start_datetime->diffInMinutes($this->end_datetime);
    }

    // Methods
    public function canRegister()
    {
        if (!$this->requires_registration) {
            return false;
        }
        
        if ($this->registration_deadline && now() > $this->registration_deadline) {
            return false;
        }
        
        if ($this->max_attendees && $this->total_attendees >= $this->max_attendees) {
            return false;
        }
        
        return $this->status === 'published' && $this->is_upcoming;
    }

    public function registerAttendee(Member $member = null, $guestData = [])
    {
        if (!$this->canRegister()) {
            return false;
        }
        
        $attendanceData = [
            'event_id' => $this->id,
            'registered_at' => now(),
            'attendance_status' => 'registered',
            'payment_status' => $this->registration_fee > 0 ? 'pending' : 'waived'
        ];
        
        if ($member) {
            $attendanceData['member_id'] = $member->id;
        } else {
            $attendanceData = array_merge($attendanceData, $guestData);
        }
        
        return EventAttendance::create($attendanceData);
    }

    public function checkInAttendee($attendanceId)
    {
        $attendance = $this->attendances()->find($attendanceId);
        
        if ($attendance) {
            $attendance->update([
                'checked_in_at' => now(),
                'attendance_status' => 'attended'
            ]);
            
            return true;
        }
        
        return false;
    }

    public function generateRecurringEvents()
    {
        if (!$this->is_recurring || !$this->recurrence_type) {
            return [];
        }
        
        $events = [];
        $currentDate = $this->start_datetime->copy();
        $endDate = $this->recurrence_end_date ?? now()->addYear();
        
        while ($currentDate <= $endDate) {
            switch ($this->recurrence_type) {
                case 'daily':
                    $currentDate->addDays($this->recurrence_interval ?? 1);
                    break;
                case 'weekly':
                    $currentDate->addWeeks($this->recurrence_interval ?? 1);
                    break;
                case 'monthly':
                    $currentDate->addMonths($this->recurrence_interval ?? 1);
                    break;
                case 'yearly':
                    $currentDate->addYears($this->recurrence_interval ?? 1);
                    break;
            }
            
            if ($currentDate <= $endDate) {
                $eventData = $this->toArray();
                unset($eventData['id'], $eventData['created_at'], $eventData['updated_at']);
                
                $duration = $this->start_datetime->diffInMinutes($this->end_datetime);
                $eventData['start_datetime'] = $currentDate->copy();
                $eventData['end_datetime'] = $currentDate->copy()->addMinutes($duration);
                
                $events[] = static::create($eventData);
            }
        }
        
        return $events;
    }
}
