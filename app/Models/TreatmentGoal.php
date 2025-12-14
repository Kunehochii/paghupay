<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentGoal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_log_id',
        'description',
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
        ];
    }

    /**
     * Get the case log this goal belongs to.
     */
    public function caseLog(): BelongsTo
    {
        return $this->belongsTo(CaseLog::class);
    }

    /**
     * Get activities for this goal.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(TreatmentActivity::class, 'goal_id');
    }
}
