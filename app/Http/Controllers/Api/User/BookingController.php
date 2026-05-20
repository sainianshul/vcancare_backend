<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\CancellationService;
use App\Services\PaymentService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected PaymentService $paymentService;
    protected CancellationService $cancellationService;
    protected WalletService $walletService;

    public function __construct(
        BookingService $bookingService,
        PaymentService $paymentService,
        CancellationService $cancellationService,
        WalletService $walletService
    ) {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
        $this->cancellationService = $cancellationService;
        $this->walletService = $walletService;
    }

    #[OA\Get(
        path: '/api/v1/user/bookings',
        operationId: 'listUserBookings',
        summary: 'List user bookings',
        description: 'Get a paginated list of bookings for the authenticated user.',
        security: [['bearerAuth' => []]],
        tags: ['User Bookings'],
        responses: [
            new OA\Response(response: 200, description: 'Bookings retrieved successfully')
        ]
    )]
    public function index(Request $request)
    {
        $bookings = $this->bookingService->listForUser($request->user()->id);

        return ApiResponse::success('Bookings retrieved successfully.', [
            'bookings' => $bookings,
        ]);
    }

    #[OA\Get(
        path: '/api/v1/user/bookings/{booking_id}',
        operationId: 'showUserBooking',
        summary: 'Show booking details',
        description: 'Get details of a specific booking with sessions and nurse info.',
        security: [['bearerAuth' => []]],
        tags: ['User Bookings'],
        parameters: [
            new OA\Parameter(name: 'booking_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Booking details'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function show(Request $request, int $bookingId)
    {
        $booking = $this->bookingService->getBookingForUser($bookingId, $request->user()->id);

        return ApiResponse::success('Booking retrieved successfully.', [
            'booking' => $booking,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/user/bookings/select-bid',
        operationId: 'selectUserBid',
        summary: 'Select a bid and create booking',
        description: 'Selects a nurse bid to create a booking (pending payment).',
        security: [['bearerAuth' => []]],
        tags: ['User Bookings'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['care_request_id', 'bid_id'],
                properties: [
                    new OA\Property(property: 'care_request_id', type: 'integer', example: 1),
                    new OA\Property(property: 'bid_id', type: 'integer', example: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Booking created'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 409, description: 'Invalid state')
        ]
    )]
    public function selectBid(Request $request)
    {
        $request->validate([
            'care_request_id' => 'required|integer',
            'bid_id' => 'required|integer',
        ]);

        $booking = $this->bookingService->createFromBid(
            $request->care_request_id,
            $request->bid_id,
            $request->user()->id
        );

        return ApiResponse::success('Bid selected. Booking created. Please proceed with payment.', [
            'booking' => $booking,
        ], 201);
    }

    #[OA\Post(
        path: '/api/v1/user/bookings/{booking_id}/pay',
        operationId: 'initiateBookingPayment',
        summary: 'Initiate payment for booking',
        description: 'Calculates wallet/gateway split. If wallet covers full amount, booking is confirmed immediately. Otherwise returns gateway order details for frontend.',
        security: [['bearerAuth' => []]],
        tags: ['User Bookings'],
        parameters: [
            new OA\Parameter(name: 'booking_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Payment initiated or completed'),
            new OA\Response(response: 402, description: 'Payment error'),
            new OA\Response(response: 409, description: 'Booking not awaiting payment')
        ]
    )]
    public function initiatePayment(Request $request, int $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $result = $this->paymentService->initiatePayment($booking, $request->user()->id);

        $message = $result['payment_completed']
            ? 'Payment completed. Booking confirmed.'
            : 'Gateway order created. Complete payment on the payment page.';

        // Generate sessions if payment was completed via wallet
        if ($result['payment_completed']) {
            $this->bookingService->generateSessions($result['booking']);
        }

        return ApiResponse::success($message, $result);
    }

    #[OA\Post(
        path: '/api/v1/user/bookings/{booking_id}/confirm-payment',
        operationId: 'confirmGatewayPayment',
        summary: 'Confirm gateway payment',
        description: 'Called after user completes payment on Razorpay/Stripe. Verifies signature, debits wallet portion, confirms booking, generates sessions.',
        security: [['bearerAuth' => []]],
        tags: ['User Bookings'],
        parameters: [
            new OA\Parameter(name: 'booking_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['gateway_payment_id', 'gateway_signature'],
                properties: [
                    new OA\Property(property: 'gateway_payment_id', type: 'string', example: 'pay_abc123xyz'),
                    new OA\Property(property: 'gateway_signature', type: 'string', example: 'sig_hash_here'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Payment confirmed'),
            new OA\Response(response: 402, description: 'Payment verification failed'),
            new OA\Response(response: 409, description: 'Invalid state')
        ]
    )]
    public function confirmPayment(Request $request, int $bookingId)
    {
        $request->validate([
            'gateway_payment_id' => 'required|string',
            'gateway_signature' => 'required|string',
        ]);

        $booking = Booking::where('id', $bookingId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $confirmedBooking = $this->paymentService->confirmGatewayPayment(
            $booking,
            $request->gateway_payment_id,
            $request->gateway_signature,
            $request->user()->id,
            $request->ip(),
            $request->userAgent()
        );

        // Generate sessions after successful payment
        $this->bookingService->generateSessions($confirmedBooking);

        return ApiResponse::success('Payment confirmed. Booking is now active.', [
            'booking' => $confirmedBooking->fresh(['sessions']),
        ]);
    }

    #[OA\Post(
        path: '/api/v1/user/bookings/{booking_id}/cancel',
        operationId: 'cancelUserBooking',
        summary: 'Cancel a booking',
        description: 'Cancels booking with slab-based refund to wallet.',
        security: [['bearerAuth' => []]],
        tags: ['User Bookings'],
        parameters: [
            new OA\Parameter(name: 'booking_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'reason', type: 'string', example: 'Change of plans'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Booking cancelled'),
            new OA\Response(response: 409, description: 'Cannot be cancelled')
        ]
    )]
    public function cancel(Request $request, int $bookingId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $result = $this->cancellationService->cancelByUser(
            $bookingId,
            $request->user()->id,
            $request->reason
        );

        return ApiResponse::success('Booking cancelled successfully.', $result);
    }

    #[OA\Post(
        path: '/api/v1/user/care-requests/{care_request_id}/cancel',
        operationId: 'cancelUserCareRequest',
        summary: 'Cancel a care request',
        description: 'Free cancellation if no booking exists yet.',
        security: [['bearerAuth' => []]],
        tags: ['User Bookings'],
        parameters: [
            new OA\Parameter(name: 'care_request_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Care request cancelled'),
            new OA\Response(response: 409, description: 'Cannot be cancelled')
        ]
    )]
    public function cancelRequest(Request $request, int $careRequestId)
    {
        $careRequest = $this->cancellationService->cancelCareRequest(
            $careRequestId,
            $request->user()->id
        );

        return ApiResponse::success('Care request cancelled.', [
            'care_request' => $careRequest,
        ]);
    }

    #[OA\Get(
        path: '/api/v1/user/bookings/{booking_id}/otp',
        operationId: 'getUserBookingOtp',
        summary: 'Get daily session OTP',
        description: 'Generates a 6-digit OTP for today\'s session to share with the nurse.',
        security: [['bearerAuth' => []]],
        tags: ['User Bookings'],
        parameters: [
            new OA\Parameter(name: 'booking_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'OTP generated'),
            new OA\Response(response: 404, description: 'No session today')
        ]
    )]
    public function getSessionOtp(Request $request, int $bookingId)
    {
        $result = $this->bookingService->getSessionOtp($bookingId, $request->user()->id);

        return ApiResponse::success('OTP generated for today\'s session.', $result);
    }

    #[OA\Get(
        path: '/api/v1/user/wallet',
        operationId: 'getUserWallet',
        summary: 'Get wallet details',
        description: 'Returns wallet balance and paginated transaction history.',
        security: [['bearerAuth' => []]],
        tags: ['User Wallet'],
        responses: [
            new OA\Response(response: 200, description: 'Wallet details')
        ]
    )]
    public function wallet(Request $request)
    {
        $userId = $request->user()->id;

        return ApiResponse::success('Wallet details retrieved.', [
            'balance' => $this->walletService->getBalance($userId),
            'transactions' => $this->walletService->getTransactions($userId),
        ]);
    }
}
