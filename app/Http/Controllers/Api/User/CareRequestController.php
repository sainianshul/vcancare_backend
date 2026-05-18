<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreCareRequest;
use App\Services\CareRequestService;
use Exception;

class CareRequestController extends Controller
{
    protected CareRequestService $careRequestService;

    public function __construct(CareRequestService $careRequestService)
    {
        $this->careRequestService = $careRequestService;
    }

    /**
     * @OA\Post(
     *     path="/api/user/care-requests",
     *     summary="Create a new care request",
     *     description="Creates a new care request for a patient.",
     *     operationId="storeCareRequest",
     *     tags={"User Care Requests"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"care_type_id", "care_for", "contact_phone", "latitude", "longitude", "address", "city", "state", "pincode", "start_date"},
     *             @OA\Property(property="care_type_id", type="integer", example=1),
     *             @OA\Property(property="care_for", type="integer", description="1: Self, 2: Other", enum={1, 2}, example=1),
     *             @OA\Property(property="patient_name", type="string", description="Required if care_for is 2", example="John Doe"),
     *             @OA\Property(property="patient_age", type="string", description="Required if care_for is 2", example="65"),
     *             @OA\Property(property="contact_phone", type="string", example="1234567890"),
     *             @OA\Property(property="secondary_phone", type="string", example="0987654321"),
     *             @OA\Property(property="latitude", type="number", format="float", example=28.6139),
     *             @OA\Property(property="longitude", type="number", format="float", example=77.2090),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="city", type="string", example="New Delhi"),
     *             @OA\Property(property="state", type="string", example="Delhi"),
     *             @OA\Property(property="country", type="string", example="India"),
     *             @OA\Property(property="pincode", type="string", example="110001"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2026-06-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2026-06-05"),
     *             @OA\Property(property="start_time", type="string", example="09:00"),
     *             @OA\Property(property="end_time", type="string", example="17:00"),
     *             @OA\Property(property="notes", type="string", example="Patient needs post-surgery care")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Care request created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Care request created successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Only patients can create care requests.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     )
     * )
     */
    public function store(StoreCareRequest $request)
    {
        $user = $request->user();

        if (!$user->isUser()) {
            return ApiResponse::error('Only patients can create care requests.', 403);
        }

        $careRequest = $this->careRequestService->createCareRequest(
            $request->validated(),
            $user->id
        );

        return ApiResponse::success(
            $careRequest,
            'Care request created successfully.',
            201
        );
    }
}
