<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    const ROLE_ADMIN = 0;
    const ROLE_USER = 1;
    const ROLE_NURSE = 2;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'profile_photo',
        'status',
        'fcm_token',
        'blocked_reason',
        'phone_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'role' => 'integer',
            'status' => 'integer',
            'password' => 'hashed',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }
    public static function getRoleList(): array
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_USER => 'User',
            self::ROLE_NURSE => 'Nurse',
        ];
    }

    public function getRoleNameAttribute(): string
    {
        return self::getRoleList()[$this->role]
            ?? 'Unknown';
    }

    public static function getStatusList(): array
    {
        return [
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_BLOCKED => 'Blocked',
        ];
    }

    public function getStatusNameAttribute(): string
    {
        return self::getStatusList()[$this->status]
            ?? 'Unknown';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_INACTIVE => 'secondary',
            self::STATUS_BLOCKED => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'ki-check-circle',
            self::STATUS_INACTIVE => 'ki-information-5',
            self::STATUS_BLOCKED => 'ki-cross-circle',
            default => 'ki-information-5',
        };
    }


    // ─── Helpers ──────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isNurse(): bool
    {
        return $this->role === self::ROLE_NURSE;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // ─── Scopes ───────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeNurses(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_NURSE);
    }

    public function scopeUsers(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_USER);
    }

    // ─── Relationships ────────────────────────

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function nurseProfile()
    {
        return $this->hasOne(NurseProfile::class);
    }
    // app/Models/User.php

    public function toUserResponse(): array
    {
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'role' => $this->role,
            'role_name' => $this->role_name,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'profile_photo' => $this->profile_photo,
        ];

        if ($this->role === self::ROLE_NURSE) {
            $response = array_merge(
                $response,
                $this->nurseOnboardingData()
            );
        }

        return $response;
    }

    // ── nurseOnboardingData  ──────────────────────
    private function nurseOnboardingData(): array
    {
        $nurse = $this->nurseProfile;

        if (!$nurse) {
            return [
                'profile_status' => NurseProfile::STATUS_PENDING,
                'profile_status_name' => 'Pending',
                'onboarding' => [
                    'is_completed' => false,
                ],
            ];
        }

        $data = [
            'profile_status' => $nurse->status ?? NurseProfile::STATUS_PENDING,
            'profile_status_name' => $nurse->status_name ?? 'Pending',
            'onboarding' => [
                'is_completed' => (bool) $nurse->is_onboarding_completed,
            ],
        ];

        if ($nurse->status === NurseProfile::STATUS_PENDING) {
            $data['onboarding'] = array_merge($data['onboarding'], [
                'current_step' => $nurse->onboarding_step,
                'current_step_name' => $nurse->step_name
            ]);
        }

        if ($nurse->status === NurseProfile::STATUS_REJECTED) {
            $data['profile_reason'] = $nurse->rejection_reason;
            $data['is_reapply'] = (bool) $nurse->can_reapply;
            $data['rejected_steps'] = $nurse->verifications()
                ->where('status', \App\Models\NurseProfileVerification::STATUS_REJECTED)
                ->get()
                ->map(fn($verification) => [
                    'step_id' => $verification->step_id,
                    'step_name' => $verification->step_name,
                    'review_message' => $verification->review_message,
                ])->toArray();
        }

        if ($nurse->status === NurseProfile::STATUS_SUSPENDED) {
            $data['profile_reason'] = $nurse->suspension_reason;
        }

        return $data;
    }
}