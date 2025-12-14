<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentActivity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'goal_id',
        'description',
        'activity_date',
    ];

    /**
     * Get the attributes that should be cast.
     * 
     * Security: description is encrypted using Laravel's 
     * built-in encryption (AES-256-CBC).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'description' => 'encrypted',
            'activity_date' => 'date',
        ];
    }

    /**
     * Get the goal this activity belongs to.
     */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(TreatmentGoal::class, 'goal_id');
    }

    /**
     * Get the case log through the goal.
     */
    public function caseLog()
    {
        return $this->goal->caseLog;
    }

    /**
     * Scope for upcoming activities.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('activity_date', '>=', today())
                     ->orderBy('activity_date', 'asc');
    }

    /**
     * Scope for past activities.
     */
    public function scopePast($query)
    {
        return $query->where('activity_date', '<', today())
                     ->orderBy('activity_date', 'desc');
    }
}
