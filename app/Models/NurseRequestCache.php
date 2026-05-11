<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NurseRequestCache extends Model
{
    const STATUS_NOTIFIED = 0;
    const STATUS_VIEWED = 1;
    const STATUS_BID_PLACED = 2;
    const STATUS_EXPIRED = 3;

    protected $table = 'nurse_request_cache';

    protected $fillable = [
        'nurse_id',
        'care_request_id',
        'request_snapshot',
        'status',
        'notified_at',
        'viewed_at',
        'expires_at',
    ];

    protected $casts = [
        'nurse_id' => 'integer',
        'care_request_id' => 'integer',
        'status' => 'integer',
        'request_snapshot' => 'array',
        'notified_at' => 'datetime',
        'viewed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public static function getStatusList(): array
    {
        return [
            self::STATUS_NOTIFIED => 'Notified',
            self::STATUS_VIEWED => 'Viewed',
            self::STATUS_BID_PLACED => 'Bid Placed',
            self::STATUS_EXPIRED => 'Expired',
        ];
    }

    public function getStatusTextAttribute(): string
    {
        return self::getStatusList()[$this->status] ?? 'Unknown';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_NOTIFIED,
            self::STATUS_VIEWED,
        ]);
    }

    public function nurse()
    {
        return $this->belongsTo(NurseProfile::class, 'nurse_id');
    }

    public function careRequest()
    {
        return $this->belongsTo(CareRequest::class);
    }
}