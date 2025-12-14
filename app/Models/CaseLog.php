<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CaseLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_log_id',
        'appointment_id',
        'counselor_id',
        'client_id',
        'start_time',
        'end_time',
        'session_duration',
        'progress_report',
        'additional_notes',
    ];

    /**
     * Get the attributes that should be cast.
     * 
     * Security: progress_report and additional_notes are encrypted
     * using Laravel's built-in encryption (AES-256-CBC).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'session_duration' => 'integer',
            'progress_report' => 'encrypted',
            'additional_notes' => 'encrypted',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate case_log_id on creation
        static::creating(function ($caseLog) {
            if (empty($caseLog->case_log_id)) {
                $caseLog->case_log_id = 'TUPV-' . Str::uuid();
            }
        });
    }

    /**
     * Get the appointment for this case log.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the counselor for this case log.
     */
    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    /**
     * Get the client for this case log.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get treatment goals for this case log.
     */
    public function treatmentGoals(): HasMany
    {
        return $this->hasMany(TreatmentGoal::class);
    }

    /**
     * Calculate and set session duration from start and end times.
     */
    public function calculateDuration(): void
    {
        if ($this->start_time && $this->end_time) {
            $this->session_duration = $this->start_time->diffInMinutes($this->end_time);
            $this->save();
        }
    }

    /**
     * Start the session timer.
     */
    public function startSession(): void
    {
        $this->update(['start_time' => now()]);
    }

    /**
     * End the session timer and calculate duration.
     */
    public function endSession(): void
    {
        $this->update(['end_time' => now()]);
        $this->calculateDuration();
    }
}
