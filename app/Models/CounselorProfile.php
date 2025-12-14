<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselorProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'position',
        'picture_url',
        'temp_password',
        'device_token',
        'device_bound_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'temp_password',
        'device_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'device_bound_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns this counselor profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if device is bound.
     */
    public function isDeviceBound(): bool
    {
        return !is_null($this->device_token);
    }

    /**
     * Bind device with token.
     */
    public function bindDevice(string $token): void
    {
        $this->update([
            'device_token' => $token,
            'device_bound_at' => now(),
        ]);
    }

    /**
     * Reset device binding.
     */
    public function resetDevice(): void
    {
        $this->update([
            'device_token' => null,
            'device_bound_at' => null,
        ]);
    }
}
