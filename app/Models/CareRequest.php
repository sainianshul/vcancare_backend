<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareRequest extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        static::saving(function ($model) {
            $lng = (float) ($model->longitude ?? 0);
            $lat = (float) ($model->latitude ?? 0);
            $model->location = \Illuminate\Support\Facades\DB::raw("ST_GeomFromText('POINT({$lng} {$lat})', 4326)");
        });
    }

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_EXPIRED = 3;
    const STATUS_MATCHING = 4;
    const STATUS_ACCEPTED = 5;
    const STATUS_FAILED_NO_NURSES = 6;
    const STATUS_FAILED_NO_BIDS = 7;
    const STATUS_FAILED_UNACCEPTED = 8;

    const CARE_FOR_SELF = 1;
    const CARE_FOR_OTHER = 2;

    const CANCELLED_BY_USER = 1;
    const CANCELLED_BY_NURSE = 2;
    const CANCELLED_BY_ADMIN = 3;
    const CANCELLED_BY_SYSTEM = 4;

    protected $hidden = [
        'location',
    ];

    protected $fillable = [
        'reference_id',
        'user_id',
        'care_type_id',
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
        'cancelled_by',
        'status',
        'bidding_ends_at',
        'matching_attempt_level',
        'patient_nudged_at',
        'total_bids_received',
        'tip_amount',
        'commission_type',
        'commission_value',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'care_type_id' => 'integer',
        'care_for' => 'integer',
        'cancelled_by' => 'integer',
        'status' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'start_date' => 'date',
        'end_date' => 'date',
        'bidding_ends_at' => 'datetime',
        'patient_nudged_at' => 'datetime',
        'matching_attempt_level' => 'integer',
        'total_bids_received' => 'integer',
        'tip_amount' => 'decimal:2',
    ];

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_MATCHING => 'Matching',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_FAILED_NO_NURSES => 'Failed: No Nurses',
            self::STATUS_FAILED_NO_BIDS => 'Failed: No Bids',
            self::STATUS_FAILED_UNACCEPTED => 'Failed: Unaccepted',
        ];
    }

    public function getStatusTextAttribute(): string
    {
        return self::getStatusList()[$this->status] ?? 'Unknown';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function careType()
    {
        return $this->belongsTo(CareType::class);
    }

    public function bids()
    {
        return $this->hasMany(RequestBid::class);
    }

    public function documents()
    {
        return $this->hasMany(RequestDocument::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }
}