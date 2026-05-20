<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    // Query Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_NOTIFIED,
            self::STATUS_VIEWED,
        ]);
    }

    // Relationships
    public function nurse()
    {
        return $this->belongsTo(NurseProfile::class, 'nurse_id');
    }

    public function careRequest()
    {
        return $this->belongsTo(CareRequest::class);
    }

    /**
     * Format cache entry for API response.
     * Single source of truth — used by both index and show endpoints.
     */
    public function toApiArray(): array
    {
        $snapshot = $this->request_snapshot ?? [];
        $expiresAt = Carbon::parse($this->expires_at);

        return [
            'id' => $this->id,
            'care_request_id' => $this->care_request_id,
            'care_request_ref' => $snapshot['care_request_ref'] ?? null,
            'status' => $this->status,
            'status_name' => $this->status_text,
            'city' => $snapshot['city'] ?? 'Unknown',
            'pincode' => $snapshot['pincode'] ?? 'Unknown',
            'start_date' => $snapshot['start_date'] ?? null,
            'end_date' => $snapshot['end_date'] ?? null,
            'start_time' => $snapshot['start_time'] ?? null,
            'end_time' => $snapshot['end_time'] ?? null,
            'care_type' => $snapshot['care_type'] ?? 'Unknown',
            'approx_distance_km' => $snapshot['distance_to_patient'] ?? null,
            'expires_at' => $expiresAt->toDateTimeString(),
            'expires_in_human' => $expiresAt->diffForHumans([
                'parts' => 2,
                'short' => true,
                'syntax' => Carbon::DIFF_ABSOLUTE,
            ]) . ' left',
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}