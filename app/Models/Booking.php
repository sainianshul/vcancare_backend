<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */
    const STATUS_PENDING_PAYMENT = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELLED = 4;

    /*
    |--------------------------------------------------------------------------
    | Payment Status Constants
    |--------------------------------------------------------------------------
    */
    const PAYMENT_UNPAID = 0;
    const PAYMENT_PAID = 1;
    const PAYMENT_REFUND_INITIATED = 2;
    const PAYMENT_REFUNDED = 3;
    const PAYMENT_PARTIALLY_REFUNDED = 4;

    /*
    |--------------------------------------------------------------------------
    | Payment Method Constants
    |--------------------------------------------------------------------------
    */
    const PAY_METHOD_GATEWAY = 1;
    const PAY_METHOD_WALLET = 2;
    const PAY_METHOD_WALLET_PLUS_GATEWAY = 3;

    /*
    |--------------------------------------------------------------------------
    | Cancelled By Constants (shared with CareRequest)
    |--------------------------------------------------------------------------
    */
    const CANCELLED_BY_USER = 1;
    const CANCELLED_BY_NURSE = 2;
    const CANCELLED_BY_ADMIN = 3;
    const CANCELLED_BY_SYSTEM = 4;

    protected $fillable = [
        'reference_id',
        'care_request_id',
        'bid_id',
        'user_id',
        'nurse_id',
        'nurse_amount',
        'commission_type',
        'commission_value',
        'commission_amount',
        'total_amount',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'total_sessions',
        'completed_sessions',
        'status',
        'payment_status',
        'payment_method',
        'gateway_order_id',
        'gateway_payment_id',
        'wallet_amount_used',
        'gateway_amount',
        'refund_amount',
        'nurse_payout_amount',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'parent_booking_id',
    ];

    protected $casts = [
        'care_request_id' => 'integer',
        'bid_id' => 'integer',
        'user_id' => 'integer',
        'nurse_id' => 'integer',
        'nurse_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'total_sessions' => 'integer',
        'completed_sessions' => 'integer',
        'status' => 'integer',
        'payment_status' => 'integer',
        'payment_method' => 'integer',
        'wallet_amount_used' => 'decimal:2',
        'gateway_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'nurse_payout_amount' => 'decimal:2',
        'cancelled_by' => 'integer',
        'cancelled_at' => 'datetime',
        'parent_booking_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Lists
    |--------------------------------------------------------------------------
    */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING_PAYMENT => 'Pending Payment',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function getPaymentStatusList(): array
    {
        return [
            self::PAYMENT_UNPAID => 'Unpaid',
            self::PAYMENT_PAID => 'Paid',
            self::PAYMENT_REFUND_INITIATED => 'Refund Initiated',
            self::PAYMENT_REFUNDED => 'Refunded',
            self::PAYMENT_PARTIALLY_REFUNDED => 'Partially Refunded',
        ];
    }

    public static function getPaymentMethodList(): array
    {
        return [
            self::PAY_METHOD_GATEWAY => 'Payment Gateway',
            self::PAY_METHOD_WALLET => 'Wallet',
            self::PAY_METHOD_WALLET_PLUS_GATEWAY => 'Wallet + Gateway',
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

    public function getPaymentStatusTextAttribute(): string
    {
        return self::getPaymentStatusList()[$this->payment_status] ?? 'Unknown';
    }

    public function getPaymentMethodTextAttribute(): string
    {
        return self::getPaymentMethodList()[$this->payment_method] ?? 'Unknown';
    }

    /**
     * Per-session rate based on TOTAL amount (for user refund calculations).
     */
    public function getPerSessionRateAttribute(): float
    {
        if ($this->total_sessions <= 0) {
            return 0;
        }

        return round((float) $this->total_amount / $this->total_sessions, 2);
    }

    /**
     * Per-session rate based on NURSE amount (for nurse payout calculations).
     */
    public function getNursePerSessionRateAttribute(): float
    {
        if ($this->total_sessions <= 0) {
            return 0;
        }

        return round((float) $this->nurse_amount / $this->total_sessions, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Checks
    |--------------------------------------------------------------------------
    */
    public function isPendingPayment(): bool
    {
        return $this->status === self::STATUS_PENDING_PAYMENT;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isPaid(): bool
    {
        return in_array($this->payment_status, [
            self::PAYMENT_PAID,
            self::PAYMENT_PARTIALLY_REFUNDED,
        ]);
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING_PAYMENT,
            self::STATUS_CONFIRMED,
            self::STATUS_ACTIVE,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForNurse(Builder $query, int $nurseId): Builder
    {
        return $query->where('nurse_id', $nurseId);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function careRequest()
    {
        return $this->belongsTo(CareRequest::class);
    }

    public function bid()
    {
        return $this->belongsTo(RequestBid::class, 'bid_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nurse()
    {
        return $this->belongsTo(NurseProfile::class, 'nurse_id');
    }

    public function sessions()
    {
        return $this->hasMany(BookingSession::class);
    }

    public function parentBooking()
    {
        return $this->belongsTo(Booking::class, 'parent_booking_id');
    }

    public function extensions()
    {
        return $this->hasMany(Booking::class, 'parent_booking_id');
    }

    public function paymentLogs()
    {
        return $this->morphMany(PaymentLog::class, 'loggable');
    }
}
