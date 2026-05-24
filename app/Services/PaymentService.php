<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\Payment\PaymentFailedException;
use App\Exceptions\Payment\InvalidPaymentAmountException;
use App\Exceptions\Booking\InvalidBookingStateException;
use App\Models\Booking;
use App\Models\PaymentLog;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Orchestrates all payment operations.
 *
 * This service is the SINGLE ENTRY POINT for money movement.
 * It talks to WalletService for internal ledger and PaymentGatewayInterface for external payments.
 *
 * Flow:
 *   1. initiatePayment()  → Calculate split, create gateway order if needed
 *   2. confirmPayment()   → Verify gateway payment, debit wallet, confirm booking
 *
 * Rules:
 *   - Wallet balance is used FIRST (reduce gateway fees)
 *   - If wallet covers full amount → no gateway needed
 *   - Every operation is atomic (DB::transaction)
 *   - Every gateway call is logged in payment_logs
 */
class PaymentService
{
    protected WalletService $walletService;
    protected PaymentGatewayInterface $gateway;

    public function __construct(WalletService $walletService, PaymentGatewayInterface $gateway)
    {
        $this->walletService = $walletService;
        $this->gateway = $gateway;
    }

    /**
     * Initiate payment for a booking.
     *
     * Returns payment instructions for the frontend:
     *   - If wallet covers everything → booking confirmed immediately
     *   - If gateway needed → returns gateway order details for frontend to complete
     *
     * @throws PaymentException
     */
    public function initiatePayment(Booking $booking, int $userId): array
    {
        if ($booking->user_id !== $userId) {
            throw new PaymentFailedException('Unauthorized.', 403);
        }

        if ($booking->status !== Booking::STATUS_PENDING_PAYMENT) {
            throw new InvalidBookingStateException('This booking is not awaiting payment.', 409);
        }

        $totalAmount = (float) $booking->total_amount;
        $walletBalance = $this->walletService->getBalance($userId);

        // Calculate split
        $walletDeduction = min($walletBalance, $totalAmount);
        $gatewayAmount = round($totalAmount - $walletDeduction, 2);

        // CASE 1: Wallet covers full amount — confirm immediately
        if ($gatewayAmount <= 0) {
            return $this->confirmWithWalletOnly($booking, $walletDeduction);
        }

        // CASE 2: Gateway payment needed (with optional wallet)
        return $this->createGatewayOrder($booking, $walletDeduction, $gatewayAmount);
    }

    /**
     * Confirm a gateway payment after user completes payment on frontend.
     *
     * Called after Razorpay/Stripe returns success on the frontend.
     *
     * @throws PaymentException
     */
    public function confirmGatewayPayment(
        Booking $booking,
        string $gatewayPaymentId,
        string $gatewaySignature,
        int $userId,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): Booking {
        if ($booking->user_id !== $userId) {
            throw new PaymentFailedException('Unauthorized.', 403);
        }

        if ($booking->status !== Booking::STATUS_PENDING_PAYMENT) {
            throw new InvalidBookingStateException('This booking is not awaiting payment.', 409);
        }

        if (!$booking->gateway_order_id) {
            throw new InvalidBookingStateException('No gateway order found for this booking.', 409);
        }

        // 1. Verify payment signature with gateway
        $isValid = false;
        try {
            $isValid = $this->gateway->verifyPayment(
                $booking->gateway_order_id,
                $gatewayPaymentId,
                $gatewaySignature
            );
        } catch (\Throwable $e) {
            // Log the failure
            PaymentLog::record($booking, PaymentLog::EVENT_PAYMENT_FAILED, (float) $booking->gateway_amount, [
                'gateway_order_id' => $booking->gateway_order_id,
                'gateway_payment_id' => $gatewayPaymentId,
                'gateway_signature' => $gatewaySignature,
                'gateway_status' => 'verification_exception',
                'gateway_raw' => ['error' => $e->getMessage()],
            ], ['ip' => $ipAddress, 'user_agent' => $userAgent]);

            Log::error('Payment verification failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            throw new PaymentFailedException('Payment verification failed. Please contact support.', 402);
        }

        if (!$isValid) {
            PaymentLog::record($booking, PaymentLog::EVENT_PAYMENT_FAILED, (float) $booking->gateway_amount, [
                'gateway_order_id' => $booking->gateway_order_id,
                'gateway_payment_id' => $gatewayPaymentId,
                'gateway_signature' => $gatewaySignature,
                'gateway_status' => 'invalid_signature',
            ], ['ip' => $ipAddress, 'user_agent' => $userAgent]);

            throw new PaymentFailedException('Payment signature verification failed.', 402);
        }

        // 2. Fetch payment details from gateway for audit
        $paymentDetails = [];
        try {
            $paymentDetails = $this->gateway->fetchPayment($gatewayPaymentId);
        } catch (\Throwable $e) {
            Log::warning('Could not fetch payment details for audit', [
                'booking_id' => $booking->id,
                'payment_id' => $gatewayPaymentId,
                'error' => $e->getMessage(),
            ]);
        }

        // 3. Confirm payment atomically
        return DB::transaction(function () use ($booking, $gatewayPaymentId, $gatewaySignature, $paymentDetails, $ipAddress, $userAgent) {

            // Log success
            PaymentLog::record($booking, PaymentLog::EVENT_PAYMENT_SUCCESS, (float) $booking->gateway_amount, [
                'gateway_order_id' => $booking->gateway_order_id,
                'gateway_payment_id' => $gatewayPaymentId,
                'gateway_signature' => $gatewaySignature,
                'gateway_status' => $paymentDetails['status'] ?? 'captured',
                'gateway_raw' => $paymentDetails['gateway_raw'] ?? $paymentDetails,
            ], ['ip' => $ipAddress, 'user_agent' => $userAgent]);

            // Debit wallet portion if any
            $walletUsed = (float) $booking->wallet_amount_used;
            if ($walletUsed > 0) {
                $this->walletService->debit(
                    $booking->user_id,
                    $walletUsed,
                    WalletTransaction::REASON_BOOKING_PAYMENT,
                    "Wallet portion for booking {$booking->reference_id}",
                    $booking->id
                );
            }

            // Determine payment method
            $paymentMethod = $walletUsed > 0
                ? Booking::PAY_METHOD_WALLET_PLUS_GATEWAY
                : Booking::PAY_METHOD_GATEWAY;

            // Update booking
            $booking->update([
                'status' => Booking::STATUS_CONFIRMED,
                'payment_status' => Booking::PAYMENT_PAID,
                'payment_method' => $paymentMethod,
                'gateway_payment_id' => $gatewayPaymentId,
            ]);

            return $booking->fresh();
        });
    }

    /**
     * Handle payment via webhook (for async payment confirmations).
     *
     * @throws PaymentException
     */
    public function handleWebhook(array $payload, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $orderId = $payload['order_id'] ?? null;

        if (!$orderId) {
            Log::warning('Webhook received without order_id', $payload);
            return;
        }

        $booking = Booking::where('gateway_order_id', $orderId)->first();

        if (!$booking) {
            Log::warning('Webhook received for unknown order', ['order_id' => $orderId]);
            return;
        }

        // Log the webhook
        PaymentLog::record($booking, PaymentLog::EVENT_WEBHOOK_RECEIVED, (float) ($payload['amount'] ?? 0) / 100, [
            'gateway_order_id' => $orderId,
            'gateway_payment_id' => $payload['payment_id'] ?? null,
            'gateway_status' => $payload['status'] ?? 'unknown',
            'gateway_raw' => $payload,
        ], ['ip' => $ipAddress, 'user_agent' => $userAgent]);
    }

    /*
    |--------------------------------------------------------------------------
    | Private Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Confirm booking using wallet balance only (no gateway).
     */
    private function confirmWithWalletOnly(Booking $booking, float $walletDeduction): array
    {
        return DB::transaction(function () use ($booking, $walletDeduction) {

            // Debit wallet
            $this->walletService->debit(
                $booking->user_id,
                $walletDeduction,
                WalletTransaction::REASON_BOOKING_PAYMENT,
                "Full payment from wallet for booking {$booking->reference_id}",
                $booking->id
            );

            // Log it
            PaymentLog::record($booking, PaymentLog::EVENT_PAYMENT_SUCCESS, $walletDeduction, [
                'gateway_status' => 'wallet_only',
                'gateway_raw' => ['method' => 'wallet', 'amount' => $walletDeduction],
            ]);

            // Update booking
            $booking->update([
                'status' => Booking::STATUS_CONFIRMED,
                'payment_status' => Booking::PAYMENT_PAID,
                'payment_method' => Booking::PAY_METHOD_WALLET,
                'wallet_amount_used' => $walletDeduction,
                'gateway_amount' => 0,
            ]);

            return [
                'payment_completed' => true,
                'payment_method' => 'wallet',
                'wallet_amount_used' => $walletDeduction,
                'gateway_amount' => 0,
                'booking' => $booking->fresh(),
            ];
        });
    }

    /**
     * Create a gateway payment order (Razorpay/Stripe) for the gateway portion.
     */
    private function createGatewayOrder(Booking $booking, float $walletDeduction, float $gatewayAmount): array
    {
        // Convert to paise (smallest currency unit) — avoids floating point at gateway boundary
        $amountInPaise = (int) round($gatewayAmount * 100);

        try {
            $orderResult = $this->gateway->createOrder(
                $amountInPaise,
                'INR',
                $booking->reference_id,
                [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                ]
            );
        } catch (\Throwable $e) {
            PaymentLog::record($booking, PaymentLog::EVENT_PAYMENT_FAILED, $gatewayAmount, [
                'gateway_status' => 'order_creation_failed',
                'gateway_raw' => ['error' => $e->getMessage()],
            ]);

            Log::error('Gateway order creation failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            throw new PaymentFailedException('Unable to create payment order. Please try again.', 502);
        }

        $gatewayOrderId = $orderResult['order_id'];

        // Save order info on booking
        $booking->update([
            'gateway_order_id' => $gatewayOrderId,
            'wallet_amount_used' => $walletDeduction,
            'gateway_amount' => $gatewayAmount,
        ]);

        // Log order creation
        PaymentLog::record($booking, PaymentLog::EVENT_ORDER_CREATED, $gatewayAmount, [
            'gateway_order_id' => $gatewayOrderId,
            'gateway_status' => 'created',
            'gateway_raw' => $orderResult['gateway_raw'] ?? $orderResult,
        ]);

        return [
            'payment_completed' => false,
            'payment_method' => $walletDeduction > 0 ? 'wallet+gateway' : 'gateway',
            'wallet_amount_used' => $walletDeduction,
            'gateway_amount' => $gatewayAmount,
            'gateway_order_id' => $gatewayOrderId,
            'gateway_currency' => 'INR',
            'booking_reference' => $booking->reference_id,
        ];
    }
}
