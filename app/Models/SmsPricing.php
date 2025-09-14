<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsPricing extends Model
{
    use HasFactory;

    protected $table = 'sms_pricing';

    protected $fillable = [
        'name',
        'description',
        'credits',
        'price',
        'bonus_credits',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'credits' => 'integer',
        'price' => 'decimal:2',
        'bonus_credits' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Scope for active pricing plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get pricing plans ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    /**
     * Calculate total credits including bonus.
     */
    public function getTotalCreditsAttribute(): int
    {
        $bonusCredits = ($this->credits * $this->bonus_credits) / 100;
        return $this->credits + (int) $bonusCredits;
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'GHS ' . number_format($this->price, 2);
    }

    /**
     * Get credits per cedi value.
     */
    public function getValueAttribute(): float
    {
        return $this->total_credits / $this->price;
    }
}
