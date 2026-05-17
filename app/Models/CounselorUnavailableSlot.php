<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselorUnavailableSlot extends Model
{
    protected $fillable = [
        'counselor_id',
        'unavailable_date',
        'time_slot_id',
    ];

    protected $casts = [
        'unavailable_date' => 'date',
    ];

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public static function isSlotUnavailable(int $counselorId, string $date, int $timeSlotId): bool
    {
        return self::where('counselor_id', $counselorId)
            ->where('unavailable_date', $date)
            ->where('time_slot_id', $timeSlotId)
            ->exists();
    }

    public static function getUnavailableSlotIdsForDate(int $counselorId, string $date): array
    {
        return self::where('counselor_id', $counselorId)
            ->where('unavailable_date', $date)
            ->pluck('time_slot_id')
            ->toArray();
    }
}
