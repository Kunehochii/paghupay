<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        // Note: start_time and end_time are stored as TIME in DB
        // Do NOT cast them to datetime as it adds today's date
    ];

    /**
     * Get start time as a simple time string (H:i:s).
     */
    public function getStartTimeAttribute($value): string
    {
        // Return just the time portion, stripping any date that might be added
        if ($value instanceof \DateTimeInterface) {
            return $value->format('H:i:s');
        }
        // If it's a string with a date prefix, extract just the time
        if (preg_match('/(\d{2}:\d{2}:\d{2})/', $value, $matches)) {
            return $matches[1];
        }
        return $value;
    }

    /**
     * Get end time as a simple time string (H:i:s).
     */
    public function getEndTimeAttribute($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('H:i:s');
        }
        if (preg_match('/(\d{2}:\d{2}:\d{2})/', $value, $matches)) {
            return $matches[1];
        }
        return $value;
    }

    /**
     * Scope to get only active time slots.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get morning slots.
     */
    public function scopeMorning($query)
    {
        return $query->where('type', 'morning');
    }

    /**
     * Scope to get afternoon slots.
     */
    public function scopeAfternoon($query)
    {
        return $query->where('type', 'afternoon');
    }

    /**
     * Get formatted time range.
     */
    public function getFormattedTimeAttribute(): string
    {
        return date('g:i A', strtotime($this->start_time)) . ' - ' . date('g:i A', strtotime($this->end_time));
    }
}
