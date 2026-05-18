<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareRequest extends Model
{
    use SoftDeletes;

    const STATUS_PENDING = 0;
    const STATUS_SEARCHING = 1;
    const STATUS_BIDDING = 2;
    const STATUS_CONFIRMED = 3;
    const STATUS_OTP_VERIFIED = 4;
    const STATUS_IN_PROGRESS = 5;
    const STATUS_COMPLETED = 6;
    const STATUS_CANCELLED = 7;
    const STATUS_REFUNDED = 8;
    const STATUS_NO_NURSE_FOUND = 9;

    const CARE_FOR_SELF = 1;
    const CARE_FOR_OTHER = 2;


    const CANCELLED_BY_USER = 1;
    const CANCELLED_BY_NURSE = 2;
    const CANCELLED_BY_ADMIN = 3;
    const CANCELLED_BY_SYSTEM = 4;

    protected $fillable = [
        'uuid',
        'user_id',
        'care_type_id',
        'assigned_nurse_id',
        'care_for',
        'patient_name',
        'patient_age',
        'contact_phone',
        'secondary_phone',
        'latitude',
        'longitude',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'notes',
        'otp',
        'otp_expires_at',
        'service_started_at',
        'service_ended_at',
        'final_amount',
        'commission_amount',
        'nurse_payout',
        'cancelled_by',
        'is_disputed',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'care_type_id' => 'integer',
        'assigned_nurse_id' => 'integer',
        'care_for' => 'integer',
        'cancelled_by' => 'integer',
        'status' => 'integer',
        'is_disputed' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'final_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'nurse_payout' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'service_started_at' => 'datetime',
        'service_ended_at' => 'datetime',
        'otp_expires_at' => 'datetime',
    ];

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SEARCHING => 'Searching',
            self::STATUS_BIDDING => 'Bidding',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_OTP_VERIFIED => 'OTP Verified',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REFUNDED => 'Refunded',
            self::STATUS_NO_NURSE_FOUND => 'No Nurse Found',
        ];
    }

    public function getStatusTextAttribute(): string
    {
        return self::getStatusList()[$this->status] ?? 'Unknown';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            self::STATUS_CANCELLED,
            self::STATUS_COMPLETED,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function careType()
    {
        return $this->belongsTo(CareType::class);
    }

    public function assignedNurse()
    {
        return $this->belongsTo(NurseProfile::class, 'assigned_nurse_id');
    }

    public function bids()
    {
        return $this->hasMany(RequestBid::class);
    }

    public function documents()
    {
        return $this->hasMany(RequestDocument::class);
    }
}