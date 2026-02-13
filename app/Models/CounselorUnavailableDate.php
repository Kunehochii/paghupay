<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselorUnavailableDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'counselor_id',
        'unavailable_date',
        'reason',
    ];

    protected $casts = [
        'unavailable_date' => 'date',
    ];

    /**
     * Get the counselor who set this unavailable date.
     */
    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    /**
     * Check if a specific date is unavailable for a counselor.
     */
    public static function isUnavailable(int $counselorId, string $date): bool
    {
        return self::where('counselor_id', $counselorId)
            ->where('unavailable_date', $date)
            ->exists();
    }

    /**
     * Get all unavailable dates for a counselor as an array of Y-m-d strings.
     */
    public static function getUnavailableDatesArray(int $counselorId): array
    {
        return self::where('counselor_id', $counselorId)
            ->pluck('unavailable_date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();
    }
}
