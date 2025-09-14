<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\SmsCreditTransaction;

class SmsCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'credits',
        'balance'
    ];

    protected $casts = [
        'credits' => 'integer',
        'balance' => 'decimal:2'
    ];

    /**
     * Get the user that owns the SMS credits.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the credit transactions for this user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(SmsCreditTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Add credits to the user's account.
     */
    public function addCredits(int $credits, string $description = 'Credits added'): void
    {
        $this->increment('credits', $credits);
        
        SmsCreditTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'purchase',
            'credits' => $credits,
            'description' => $description,
            'status' => 'completed'
        ]);
    }

    /**
     * Deduct credits from the user's account.
     */
    public function deductCredits(int $credits, string $description = 'Credits used'): bool
    {
        if ($this->credits < $credits) {
            return false;
        }

        $this->decrement('credits', $credits);
        
        SmsCreditTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'usage',
            'credits' => -$credits,
            'description' => $description,
            'status' => 'completed'
        ]);

        return true;
    }

    /**
     * Check if user has sufficient credits.
     */
    public function hasCredits(int $requiredCredits): bool
    {
        return $this->credits >= $requiredCredits;
    }

    /**
     * Get or create SMS credits for a user.
     */
    public static function getOrCreateForUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            ['credits' => 0, 'balance' => 0.00]
        );
    }
}
