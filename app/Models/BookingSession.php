<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BookingSession extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */
    const STATUS_UPCOMING = 0;
    const STATUS_STARTED = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_MISSED = 3;
    const STATUS_CANCELLED = 4;

    protected $fillable = [
        'booking_id',
        'session_date',
        'session_number',
        'start_time',
        'end_time',
        'otp_code',
        'otp_verified_at',
        'started_at',
        'ended_at',
        'status',
        'nurse_notes',
    ];

    protected $casts = [
        'booking_id' => 'integer',
        'session_date' => 'date',
        'session_number' => 'integer',
        'otp_verified_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'status' => 'integer',
    ];

    protected $hidden = [
        'otp_code',
    ];

    /*
    |--------------------------------------------------------------------------
    | Lists
    |--------------------------------------------------------------------------
    */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_UPCOMING => 'Upcoming',
            self::STATUS_STARTED => 'Started',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_MISSED => 'Missed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    public function getStatusTextAttribute(): string
    {
        return self::getStatusList()[$this->status] ?? 'Unknown';
    }

    /*
    |--------------------------------------------------------------------------
    | OTP Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Generate a fresh 6-digit OTP for this session.
     */
    public function generateOtp(): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'otp_code' => $otp,
            'otp_verified_at' => null,
        ]);

        return $otp;
    }

    /**
     * Verify a given OTP against the stored OTP.
     */
    public function verifyOtp(string $otp): bool
    {
        return $this->otp_code === $otp;
    }

    /*
    |--------------------------------------------------------------------------
    | Status Checks
    |--------------------------------------------------------------------------
    */
    public function isUpcoming(): bool
    {
        return $this->status === self::STATUS_UPCOMING;
    }

    public function isStarted(): bool
    {
        return $this->status === self::STATUS_STARTED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->where('session_date', $date);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_UPCOMING,
            self::STATUS_STARTED,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
