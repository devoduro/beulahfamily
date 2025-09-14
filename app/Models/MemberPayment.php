<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Member;
use App\Models\User;

class MemberPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'recorded_by',
        'payment_reference',
        'payment_type',
        'amount',
        'payment_date',
        'payment_method',
        'receipt_number',
        'invoice_number',
        'description',
        'transaction_id',
        'status',
        'sms_sent',
        'sms_sent_at',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'sms_sent' => 'boolean',
        'sms_sent_at' => 'datetime',
        'metadata' => 'array',
        'amount' => 'decimal:2'
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('payment_date', now()->month)
                    ->whereYear('payment_date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('payment_date', now()->year);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('payment_type', $type);
    }

    // Accessors
    public function getPaymentTypeDisplayAttribute()
    {
        $types = [
            'tithe' => 'Tithe',
            'offering' => 'Offering',
            'welfare' => 'Welfare',
            'building_fund' => 'Building Fund',
            'special_offering' => 'Special Offering',
            'thanksgiving' => 'Thanksgiving',
            'other' => 'Other'
        ];

        return $types[$this->payment_type] ?? 'Unknown';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>',
            'confirmed' => '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Confirmed</span>',
            'failed' => '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Failed</span>'
        ];
        
        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getFormattedAmountAttribute()
    {
        return 'GHS ' . number_format($this->amount, 2);
    }

    // Static methods
    public static function generateReference()
    {
        return 'PAY-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public static function generateReceiptNumber()
    {
        $lastPayment = self::whereDate('created_at', today())
            ->whereNotNull('receipt_number')
            ->where('receipt_number', 'like', 'RCP-' . date('Ymd') . '-%')
            ->orderBy('receipt_number', 'desc')
            ->first();

        if ($lastPayment) {
            $lastNumber = (int) substr($lastPayment->receipt_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'RCP-' . date('Ymd') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function generateInvoiceNumber()
    {
        $lastPayment = self::whereYear('created_at', date('Y'))
            ->whereNotNull('invoice_number')
            ->where('invoice_number', 'like', 'INV-' . date('Y') . '-%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastPayment) {
            $lastNumber = (int) substr($lastPayment->invoice_number, -6);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'INV-' . date('Y') . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public static function getPaymentTypes()
    {
        return [
            'tithe' => 'Tithe',
            'offering' => 'Offering',
            'welfare' => 'Welfare',
            'building_fund' => 'Building Fund',
            'special_offering' => 'Special Offering',
            'thanksgiving' => 'Thanksgiving',
            'other' => 'Other'
        ];
    }
}
