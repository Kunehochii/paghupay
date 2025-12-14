<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nickname',
        'course_year_section',
        'birthdate',
        'birthplace',
        'sex',
        'contact_number',
        'fb_account',
        'nationality',
        'address',
        'home_address',
        'guardian_name',
        'guardian_relationship',
        'guardian_contact',
        'is_active',
        'temp_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'temp_password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is a client/student.
     */
    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    /**
     * Check if user is a counselor.
     */
    public function isCounselor(): bool
    {
        return $this->hasRole('counselor');
    }

    /**
     * Get the counselor profile (for counselors only).
     */
    public function counselorProfile(): HasOne
    {
        return $this->hasOne(CounselorProfile::class);
    }

    /**
     * Get appointments as a client.
     */
    public function clientAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    /**
     * Get appointments as a counselor.
     */
    public function counselorAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'counselor_id');
    }

    /**
     * Get case logs as a client.
     */
    public function clientCaseLogs(): HasMany
    {
        return $this->hasMany(CaseLog::class, 'client_id');
    }

    /**
     * Get case logs as a counselor.
     */
    public function counselorCaseLogs(): HasMany
    {
        return $this->hasMany(CaseLog::class, 'counselor_id');
    }
}
