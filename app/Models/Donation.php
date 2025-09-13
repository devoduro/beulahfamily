<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donation_number',
        'member_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'donation_type',
        'purpose',
        'payment_method',
        'reference_number',
        'donation_date',
        'received_by',
        'notes',
        'is_anonymous',
        'is_recurring',
        'recurring_frequency',
        'recurring_end_date',
        'tax_deductible',
        'receipt_number',
        'receipt_sent',
        'receipt_sent_at',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'donation_date' => 'date',
        'recurring_end_date' => 'date',
        'receipt_sent_at' => 'datetime',
        'is_anonymous' => 'boolean',
        'is_recurring' => 'boolean',
        'tax_deductible' => 'boolean',
        'receipt_sent' => 'boolean'
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('donation_type', $type);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('donation_date', $year);
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('donation_date', $year)
                    ->whereMonth('donation_date', $month);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('donation_date', [$startDate, $endDate]);
    }

    public function scopeTaxDeductible($query)
    {
        return $query->where('tax_deductible', true);
    }

    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    // Accessors
    public function getDonorDisplayNameAttribute()
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }
        
        return $this->member ? $this->member->full_name : $this->donor_name;
    }

    // Methods
    public function generateDonationNumber()
    {
        $year = $this->donation_date->year;
        $lastDonation = static::where('donation_number', 'like', 'DN' . $year . '%')
                             ->orderBy('donation_number', 'desc')
                             ->first();
        
        if ($lastDonation) {
            $lastNumber = (int) substr($lastDonation->donation_number, 6);
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '000001';
        }
        
        return 'DN' . $year . $nextNumber;
    }

    public function generateReceiptNumber()
    {
        $year = $this->donation_date->year;
        $lastReceipt = static::where('receipt_number', 'like', 'RC' . $year . '%')
                            ->orderBy('receipt_number', 'desc')
                            ->first();
        
        if ($lastReceipt) {
            $lastNumber = (int) substr($lastReceipt->receipt_number, 6);
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '000001';
        }
        
        return 'RC' . $year . $nextNumber;
    }

    public function sendReceipt()
    {
        if (!$this->receipt_number) {
            $this->receipt_number = $this->generateReceiptNumber();
        }
        
        // Logic to send receipt via email would go here
        // For now, just mark as sent
        $this->update([
            'receipt_sent' => true,
            'receipt_sent_at' => now()
        ]);
        
        return true;
    }

    public function createRecurringDonations()
    {
        if (!$this->is_recurring || !$this->recurring_frequency) {
            return [];
        }
        
        $donations = [];
        $currentDate = $this->donation_date->copy();
        $endDate = $this->recurring_end_date ?? now()->addYear();
        
        while ($currentDate <= $endDate) {
            switch ($this->recurring_frequency) {
                case 'weekly':
                    $currentDate->addWeek();
                    break;
                case 'monthly':
                    $currentDate->addMonth();
                    break;
                case 'quarterly':
                    $currentDate->addMonths(3);
                    break;
                case 'annually':
                    $currentDate->addYear();
                    break;
            }
            
            if ($currentDate <= $endDate) {
                $donationData = $this->toArray();
                unset($donationData['id'], $donationData['donation_number'], 
                      $donationData['receipt_number'], $donationData['created_at'], 
                      $donationData['updated_at']);
                
                $donationData['donation_date'] = $currentDate->copy();
                $donationData['status'] = 'pending';
                $donationData['receipt_sent'] = false;
                $donationData['receipt_sent_at'] = null;
                
                $donations[] = static::create($donationData);
            }
        }
        
        return $donations;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($donation) {
            if (empty($donation->donation_number)) {
                $donation->donation_number = $donation->generateDonationNumber();
            }
        });
    }
}
