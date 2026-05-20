<?php

namespace App\Http\Controllers\Api\Nurse;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Nurse\PlaceBidRequest;
use App\Models\NurseRequestCache;
use App\Services\BiddingService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use OpenApi\Attributes as OA;

class CareRequestController extends Controller
{
    protected BiddingService $biddingService;

    public function __construct(BiddingService $biddingService)
    {
        $this->biddingService = $biddingService;
    }

    #[OA\Get(
        path: '/api/v1/nurse/care-requests',
        operationId: 'listNurseCareRequests',
        summary: 'List available care requests for nurse',
        description: 'Get a list of active care requests sent to the authenticated nurse.',
        security: [['bearerAuth' => []]],
        tags: ['Nurse Care Requests'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of requests',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Requests retrieved successfully.'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isNurse()) {
            return ApiResponse::error('Only nurses can access this endpoint.', 403);
        }

        $nurseProfileId = $user->nurseProfile->id ?? null;

        if (!$nurseProfileId) {
            return ApiResponse::success('Available requests retrieved successfully.', ['requests' => []], 200);
        }

        // Fetch requests that are assigned to this nurse's profile and haven't expired yet
        $cachedRequests = NurseRequestCache::where('nurse_id', $nurseProfileId)
            ->whereIn('status', [NurseRequestCache::STATUS_NOTIFIED])
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Format the output using toApiArray() on the model
        $formattedRequests = $cachedRequests->getCollection()->map(function ($cache) {
            return $cache->toApiArray();
        });

        // Replace collection with formatted data
        $cachedRequests->setCollection($formattedRequests);

        return ApiResponse::success(
            'Available requests retrieved successfully.',
            ['requests' => $cachedRequests],
            200
        );
    }

    #[OA\Post(
        path: '/api/v1/nurse/care-requests/bid',
        operationId: 'placeNurseBid',
        summary: 'Place a bid on a care request',
        description: 'Nurse places a bid with their price on an available care request.',
        security: [['bearerAuth' => []]],
        tags: ['Nurse Care Requests'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['care_request_id', 'nurse_amount'],
                properties: [
                    new OA\Property(property: 'care_request_id', type: 'integer', description: 'ID of the care request to bid on', example: 1),
                    new OA\Property(property: 'nurse_amount', type: 'number', format: 'float', description: 'Proposed amount by the nurse', example: 500.00),
                    new OA\Property(property: 'notes', type: 'string', description: 'Optional proposal notes or specialties details', example: 'I specialize in post-surgery care.'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Bid placed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Bid placed successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'bid',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'care_request_id', type: 'integer', example: 1),
                                        new OA\Property(property: 'nurse_id', type: 'integer', example: 3),
                                        new OA\Property(property: 'nurse_amount', type: 'string', example: '500.00'),
                                        new OA\Property(property: 'commission_type', type: 'integer', description: '1: Percentage, 2: Flat', example: 1),
                                        new OA\Property(property: 'commission_value', type: 'string', example: '15.00'),
                                        new OA\Property(property: 'commission_amount', type: 'string', example: '75.00'),
                                        new OA\Property(property: 'total_amount', type: 'string', example: '575.00'),
                                        new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'I specialize in post-surgery care.'),
                                        new OA\Property(property: 'distance_km', type: 'string', nullable: true, example: '3.45'),
                                        new OA\Property(property: 'status', type: 'integer', description: '0: Pending, 1: Selected, 2: Rejected, 3: Expired, 4: Cancelled', example: 0),
                                        new OA\Property(property: 'expires_at', type: 'string', format: 'date-time', example: '2026-05-20T12:00:00Z'),
                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-05-19T10:20:00Z'),
                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-05-19T10:20:00Z'),
                                    ]
                                )
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Validation failed or bad request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - only approved nurses or not eligible for request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'You are not eligible to bid on this request.'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Not Found - Care request or nurse profile not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Care request not found.'),
                    ]
                )
            ),
            new OA\Response(
                response: 409,
                description: 'Conflict - Duplicate bid or request not in matching state',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'You have already placed a bid on this request.'),
                    ]
                )
            ),
            new OA\Response(
                response: 410,
                description: 'Gone - Bidding window has expired',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Bidding window has expired for this request.'),
                    ]
                )
            )
        ]
    )]
    public function placeBid(PlaceBidRequest $request)
    {
        $user = $request->user();

        if (!$user->isNurse()) {
            return ApiResponse::error('Only nurses can place bids.', 403);
        }

        $nurseProfileId = $user->nurseProfile->id ?? null;

        if (!$nurseProfileId) {
            return ApiResponse::error('Nurse profile not found.', 404);
        }

        $bid = $this->biddingService->placeBid(
            $nurseProfileId,
            $request->validated()
        );

        return ApiResponse::success('Bid placed successfully.', $bid->toArray(), 201);
    }

    #[OA\Get(
        path: '/api/v1/nurse/care-requests/{id}',
        operationId: 'showNurseCareRequest',
        summary: 'View a single care request',
        description: 'Nurse views details of a single care request they were notified about.',
        security: [['bearerAuth' => []]],
        tags: ['Nurse Care Requests'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Request details retrieved successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Not found or not authorized'
            )
        ]
    )]
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isNurse()) {
            return ApiResponse::error('Only nurses can access this endpoint.', 403);
        }

        $nurseProfileId = $user->nurseProfile->id ?? null;

        if (!$nurseProfileId) {
            return ApiResponse::error('Nurse profile not found.', 404);
        }

        $cache = NurseRequestCache::where('nurse_id', $nurseProfileId)
            ->where('care_request_id', $id)
            ->where('expires_at', '>', now())
            ->first();

        if (!$cache) {
            return ApiResponse::error('Request not found or expired.', 404);
        }

        $data = $cache->toApiArray();

        return ApiResponse::success('Request details retrieved successfully.', $data, 200);
    }
}
