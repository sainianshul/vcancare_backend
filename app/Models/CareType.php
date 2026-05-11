<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareType extends Model
{
    use SoftDeletes;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'duration_type',
        'created_by',
        'status',
    ];

    protected $casts = [
        'created_by' => 'integer',
        'status' => 'integer',
    ];

    public static function getStatusList(): array
    {
        return [
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
        ];
    }

    public function getStatusTextAttribute(): string
    {
        return self::getStatusList()[$this->status] ?? 'Unknown';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function nurses()
    {
        return $this->belongsToMany(
            NurseProfile::class,
            'care_type_nurse',
            'care_type_id',
            'nurse_id'
        );
    }

    public function careRequests()
    {
        return $this->hasMany(CareRequest::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = str($model->name)->slug();
            }
        });
    }
}