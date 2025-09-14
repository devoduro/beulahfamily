<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'template_id',
        'title',
        'message',
        'recipient_type',
        'recipient_filter',
        'total_recipients',
        'successful_sends',
        'failed_sends',
        'status',
        'scheduled_at',
        'sent_at',
        'mnotify_message_id',
        'estimated_cost',
        'actual_cost',
        'sender_name',
        'is_scheduled',
        'delivery_report',
    ];

    protected $casts = [
        'recipient_filter' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivery_report' => 'array',
        'is_scheduled' => 'boolean',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    /**
     * Get the user who sent the SMS.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the SMS template used.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class, 'template_id');
    }

    /**
     * Get the SMS recipients.
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(SmsRecipient::class, 'sms_message_id');
    }

    /**
     * Scope for pending messages.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for sent messages.
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed messages.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for scheduled messages.
     */
    public function scopeScheduled($query)
    {
        return $query->where('is_scheduled', true)
                    ->where('status', 'pending')
                    ->whereNotNull('scheduled_at');
    }

    /**
     * Get success rate percentage.
     */
    public function getSuccessRateAttribute()
    {
        if ($this->total_recipients == 0) {
            return 0;
        }
        
        return round(($this->successful_sends / $this->total_recipients) * 100, 2);
    }

    /**
     * Get delivery status summary.
     */
    public function getDeliveryStatusAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return $this->is_scheduled ? 'Scheduled' : 'Pending';
            case 'sending':
                return 'Sending';
            case 'sent':
                return $this->successful_sends == $this->total_recipients ? 'Delivered' : 'Partially Delivered';
            case 'failed':
                return 'Failed';
            default:
                return 'Unknown';
        }
    }

    /**
     * Check if message can be cancelled.
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'scheduled']) && 
               ($this->is_scheduled ? $this->scheduled_at->isFuture() : true);
    }

    /**
     * Mark message as sent.
     */
    public function markAsSent($mnotifyMessageId = null, $actualCost = null)
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'mnotify_message_id' => $mnotifyMessageId,
            'actual_cost' => $actualCost ?? $this->estimated_cost,
        ]);
    }

    /**
     * Mark message as failed.
     */
    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'delivery_report' => array_merge($this->delivery_report ?? [], [
                'failed_at' => now()->toISOString(),
                'failure_reason' => $reason
            ])
        ]);
    }

    /**
     * Update delivery statistics.
     */
    public function updateDeliveryStats($successful, $failed)
    {
        $this->update([
            'successful_sends' => $successful,
            'failed_sends' => $failed,
        ]);
    }
}
