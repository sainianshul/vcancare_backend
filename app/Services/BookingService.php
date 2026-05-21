<?php

namespace App\Services;

use App\Exceptions\BookingException;
use App\Models\Booking;
use App\Models\BookingSession;
use App\Models\CareRequest;
use App\Models\RequestBid;
use App\Models\WalletTransaction;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Handles booking lifecycle: creation, session generation, session start/end, completion.
 *
 * Payment is handled by PaymentService — this service DOES NOT touch money directly
 * except for nurse payout on booking completion (via WalletService).
 *
 * Flow:
 *   1. User selects bid       → createFromBid()     → Booking(pending_payment)
 *   2. PaymentService handles payment confirmation
 *   3. After payment          → generateSessions()   → BookingSessions created
 *   4. Daily OTP cycle        → startSession() / endSession()
 *   5. All sessions done      → completeBooking()    → Nurse gets paid
 */
class BookingService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Create a booking from a selected bid.
     *
     * @throws BookingException
     */
    public function createFromBid(int $careRequestId, int $bidId, int $userId): Booking
    {
        $careRequest = CareRequest::where('id', $careRequestId)
            ->where('user_id', $userId)
            ->first();

        if (!$careRequest) {
            throw new BookingException('Care request not found.', 404);
        }

        if (!in_array($careRequest->status, [CareRequest::STATUS_MATCHING, CareRequest::STATUS_ACCEPTED])) {
            throw new BookingException('This care request is not in a valid state for booking.', 409);
        }

        // Prevent duplicate bookings for same care request
        $existingBooking = Booking::where('care_request_id', $careRequestId)
            ->whereNotIn('status', [Booking::STATUS_CANCELLED])
            ->exists();

        if ($existingBooking) {
            throw new BookingException('A booking already exists for this care request.', 409);
        }

        $bid = RequestBid::where('id', $bidId)
            ->where('care_request_id', $careRequestId)
            ->where('status', RequestBid::STATUS_PENDING)
            ->first();

        if (!$bid) {
            throw new BookingException('Bid not found or already processed.', 404);
        }

        // Calculate total sessions (days)
        $startDate = $careRequest->start_date;
        $endDate = $careRequest->end_date ?? $careRequest->start_date;
        $totalSessions = $startDate->diffInDays($endDate) + 1;

        return DB::transaction(function () use ($careRequest, $bid, $userId, $totalSessions) {
            // 1. Create booking
            $booking = Booking::create([
                'reference_id' => 'BKG-' . now()->format('ymd') . '-' . strtoupper(Str::random(4)),
                'care_request_id' => $careRequest->id,
                'bid_id' => $bid->id,
                'user_id' => $userId,
                'nurse_id' => $bid->nurse_id,
                'nurse_amount' => $bid->nurse_amount,
                'commission_amount' => $bid->commission_amount,
                'total_amount' => $bid->total_amount,
                'start_date' => $careRequest->start_date,
                'end_date' => $careRequest->end_date ?? $careRequest->start_date,
                'start_time' => $careRequest->start_time,
                'end_time' => $careRequest->end_time,
                'total_sessions' => $totalSessions,
                'completed_sessions' => 0,
                'status' => Booking::STATUS_PENDING_PAYMENT,
                'payment_status' => Booking::PAYMENT_UNPAID,
            ]);

            // 2. Mark bid as selected, reject all others
            $bid->update(['status' => RequestBid::STATUS_SELECTED]);

            RequestBid::where('care_request_id', $careRequest->id)
                ->where('id', '!=', $bid->id)
                ->where('status', RequestBid::STATUS_PENDING)
                ->update(['status' => RequestBid::STATUS_REJECTED]);

            // 3. Update care request status
            $careRequest->update(['status' => CareRequest::STATUS_ACCEPTED]);

            return $booking;
        });
    }

    /**
     * Generate daily sessions for a confirmed booking.
     * Called by PaymentService after payment is confirmed.
     */
    public function generateSessions(Booking $booking): void
    {
        // Safety: don't generate sessions twice
        if (BookingSession::where('booking_id', $booking->id)->exists()) {
            return;
        }

        $period = CarbonPeriod::create($booking->start_date, $booking->end_date);
        $sessionNumber = 1;
        $records = [];
        $now = now();

        foreach ($period as $date) {
            $records[] = [
                'booking_id' => $booking->id,
                'session_date' => $date->format('Y-m-d'),
                'session_number' => $sessionNumber++,
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'status' => BookingSession::STATUS_UPCOMING,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($records)) {
            BookingSession::insert($records);
        }
    }

    /**
     * Get booking details for a user.
     *
     * @throws BookingException
     */
    public function getBookingForUser(int $bookingId, int $userId): Booking
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', $userId)
            ->with(['sessions', 'nurse.user:id,name,profile_photo', 'careRequest.careType:id,name'])
            ->first();

        if (!$booking) {
            throw new BookingException('Booking not found.', 404);
        }

        return $booking;
    }

    /**
     * List bookings for a user (paginated).
     */
    public function listForUser(int $userId)
    {
        return Booking::where('user_id', $userId)
            ->with(['nurse.user:id,name,profile_photo', 'careRequest.careType:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Get nurse's schedule for a specific date.
     */
    public function getNurseSchedule(int $nurseId, string $date)
    {
        return BookingSession::whereHas('booking', function ($query) use ($nurseId) {
                $query->where('nurse_id', $nurseId)
                    ->whereIn('status', [
                        Booking::STATUS_CONFIRMED,
                        Booking::STATUS_ACTIVE,
                    ]);
            })
            ->where('session_date', $date)
            ->whereIn('status', [
                BookingSession::STATUS_UPCOMING,
                BookingSession::STATUS_STARTED,
            ])
            ->with(['booking.user:id,name,phone', 'booking.careRequest.careType:id,name'])
            ->orderBy('start_time')
            ->get();
    }

    /**
     * List bookings for a nurse (paginated).
     */
    public function listForNurse(int $nurseId)
    {
        return Booking::where('nurse_id', $nurseId)
            ->whereIn('status', [
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_ACTIVE,
                Booking::STATUS_COMPLETED,
            ])
            ->with(['user:id,name,phone', 'careRequest.careType:id,name'])
            ->orderBy('start_date', 'desc')
            ->paginate(10);
    }

    /**
     * Start a session — nurse verifies OTP from patient.
     *
     * @throws BookingException
     */
    public function startSession(int $sessionId, string $otp, int $nurseId): BookingSession
    {
        $session = BookingSession::whereHas('booking', function ($query) use ($nurseId) {
                $query->where('nurse_id', $nurseId);
            })
            ->where('id', $sessionId)
            ->first();

        if (!$session) {
            throw new BookingException('Session not found.', 404);
        }

        if ($session->status !== BookingSession::STATUS_UPCOMING) {
            throw new BookingException('This session cannot be started.', 409);
        }

        if (!$session->verifyOtp($otp)) {
            throw new BookingException('Invalid OTP.', 422);
        }

        $session->update([
            'status' => BookingSession::STATUS_STARTED,
            'otp_verified_at' => now(),
            'started_at' => now(),
        ]);

        // Mark booking as active if it's still confirmed
        $booking = $session->booking;
        if ($booking->status === Booking::STATUS_CONFIRMED) {
            $booking->update(['status' => Booking::STATUS_ACTIVE]);
        }

        return $session->fresh();
    }

    /**
     * End a session — nurse marks it complete.
     * If all sessions done → booking completes and nurse gets paid.
     *
     * @throws BookingException
     */
    public function endSession(int $sessionId, int $nurseId, ?string $notes = null): BookingSession
    {
        $session = BookingSession::whereHas('booking', function ($query) use ($nurseId) {
                $query->where('nurse_id', $nurseId);
            })
            ->where('id', $sessionId)
            ->first();

        if (!$session) {
            throw new BookingException('Session not found.', 404);
        }

        if ($session->status !== BookingSession::STATUS_STARTED) {
            throw new BookingException('This session has not been started yet.', 409);
        }

        return DB::transaction(function () use ($session, $notes) {
            $session->update([
                'status' => BookingSession::STATUS_COMPLETED,
                'ended_at' => now(),
                'nurse_notes' => $notes,
            ]);

            // Increment completed sessions on booking
            $booking = $session->booking;
            $booking->increment('completed_sessions');
            $booking->refresh();

            // If all sessions completed → complete booking and payout nurse
            if ($booking->completed_sessions >= $booking->total_sessions) {
                $this->completeBooking($booking);
            }

            return $session->fresh();
        });
    }

    /**
     * Complete a booking — nurse gets full nurse_amount.
     */
    private function completeBooking(Booking $booking): void
    {
        $nursePayoutAmount = (float) $booking->nurse_amount;

        $booking->update([
            'status' => Booking::STATUS_COMPLETED,
            'nurse_payout_amount' => $nursePayoutAmount,
        ]);

        // Credit nurse wallet
        $this->walletService->credit(
            $booking->nurse->user_id,
            $nursePayoutAmount,
            WalletTransaction::REASON_NURSE_PAYOUT,
            "Payout for completed booking {$booking->reference_id}",
            $booking->id
        );
    }

    /**
     * Get today's OTP for a session (user-facing — shows to patient).
     *
     * @throws BookingException
     */
    public function getSessionOtp(int $bookingId, int $userId): array
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', $userId)
            ->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_ACTIVE])
            ->first();

        if (!$booking) {
            throw new BookingException('Booking not found or not active.', 404);
        }

        $today = now()->format('Y-m-d');
        $session = BookingSession::where('booking_id', $booking->id)
            ->where('session_date', $today)
            ->where('status', BookingSession::STATUS_UPCOMING)
            ->first();

        if (!$session) {
            throw new BookingException('No upcoming session found for today.', 404);
        }

        // Generate fresh OTP
        $otp = $session->generateOtp();

        return [
            'session_id' => $session->id,
            'session_date' => $session->session_date->format('Y-m-d'),
            'session_number' => $session->session_number,
            'otp' => $otp,
        ];
    }
}
