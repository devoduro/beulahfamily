<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\FinanceCategory;
use App\Models\User;

class FinanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'reference_number',
        'title',
        'description',
        'amount',
        'type',
        'transaction_date',
        'payment_method',
        'receipt_number',
        'attachment',
        'status',
        'approved_by',
        'approved_at',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
        'metadata' => 'array',
        'amount' => 'decimal:2'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
                    ->whereYear('transaction_date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('transaction_date', now()->year);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>',
            'approved' => '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Approved</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Rejected</span>'
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
        return 'FIN-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}
