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
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

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
