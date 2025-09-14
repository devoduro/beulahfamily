<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewaySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'gateway_type',
        'provider',
        'name',
        'description',
        'settings',
        'is_active',
        'is_test_mode',
        'priority'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePayment($query)
    {
        return $query->where('gateway_type', 'payment');
    }

    public function scopeSms($query)
    {
        return $query->where('gateway_type', 'sms');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('name');
    }

    // Accessors
    public function getTypeBadgeAttribute()
    {
        $badges = [
            'payment' => '<span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Payment</span>',
            'sms' => '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">SMS</span>'
        ];
        
        return $badges[$this->gateway_type] ?? '';
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_active) {
            $mode = $this->is_test_mode ? 'Test' : 'Live';
            $color = $this->is_test_mode ? 'yellow' : 'green';
            return "<span class=\"px-2 py-1 text-xs font-medium bg-{$color}-100 text-{$color}-800 rounded-full\">{$mode}</span>";
        }
        
        return '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Inactive</span>';
    }

    // Static methods
    public static function getActivePaymentGateway()
    {
        return self::payment()->active()->ordered()->first();
    }

    public static function getActiveSmsGateway()
    {
        return self::sms()->active()->ordered()->first();
    }
}
