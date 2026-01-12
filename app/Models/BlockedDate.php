<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'blocked_date',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'blocked_date' => 'date',
    ];

    /**
     * Get the admin who blocked this date.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if a specific date is blocked.
     */
    public static function isBlocked(string $date): bool
    {
        return self::where('blocked_date', $date)->exists();
    }

    /**
     * Get all blocked dates as an array.
     */
    public static function getBlockedDatesArray(): array
    {
        return self::pluck('blocked_date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();
    }
}
