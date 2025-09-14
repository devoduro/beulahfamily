<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'message',
        'category',
        'variables',
        'is_active',
        'created_by',
        'usage_count',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created the template.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get SMS messages that used this template.
     */
    public function smsMessages(): HasMany
    {
        return $this->hasMany(SmsMessage::class, 'template_id');
    }

    /**
     * Scope for active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Replace template variables with actual values.
     */
    public function renderMessage($variables = [])
    {
        $message = $this->message;
        
        foreach ($variables as $key => $value) {
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }
        
        return $message;
    }

    /**
     * Get available variables in the template.
     */
    public function getTemplateVariables()
    {
        preg_match_all('/\{\{(\w+)\}\}/', $this->message, $matches);
        return array_unique($matches[1]);
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Get predefined template categories.
     */
    public static function getCategories()
    {
        return [
            'general' => 'General',
            'events' => 'Events & Announcements',
            'reminders' => 'Reminders',
            'birthday' => 'Birthday Wishes',
            'welcome' => 'Welcome Messages',
            'donations' => 'Donation Thanks',
            'meetings' => 'Meeting Notifications',
            'emergency' => 'Emergency Alerts',
            'seasonal' => 'Seasonal Greetings',
            'custom' => 'Custom'
        ];
    }
}
