<?php

namespace App\Services;

use App\Exceptions\CareRequestException;
use App\Models\CareRequest;
use App\Models\CareType;
use App\Models\NurseRequestCache;
use App\Helpers\BiddingWindow;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CareRequestService
{
    protected ProviderMatchingService $matchingService;

    public function __construct(ProviderMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * List care requests for a user (paginated).
     */
    public function listForUser(int $userId)
    {
        return CareRequest::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Create a care request and run nurse matching.
     *
     * @throws CareRequestException
     */
    public function createAndMatch(array $data, int $userId): array
    {
        // 1. Create the care request
        $careRequest = $this->createRequestRecord($data, $userId);

        // 2. Find eligible nurses iteratively
        $matchResult = $this->matchingService->runIterativeMatching($careRequest);
        $eligibleNurses = $matchResult['nurses'];
        $matchedLevel = $matchResult['level'];

        // 3. Handle case where no nurses are found
        if ($eligibleNurses->isEmpty()) {
            $careRequest->update([
                'status' => CareRequest::STATUS_FAILED_NO_NURSES,
                'matching_attempt_level' => $matchedLevel,
            ]);

            throw new CareRequestException(
                'No nurses available for the requested time and location. Please try adjusting your schedule or location.',
                422
            );
        }

        // 4. Calculate bidding window expiration
        $expiresAt = BiddingWindow::calculateExpiry($careRequest);

        // 5. Save matching records to cache for notifications
        $this->createRequestCacheEntries($careRequest, $eligibleNurses, $matchedLevel, $expiresAt);

        // 6. Update request status to MATCHING
        $careRequest->update([
            'status' => CareRequest::STATUS_MATCHING,
            'bidding_ends_at' => $expiresAt,
            'matching_attempt_level' => $matchedLevel,
        ]);

        return [
            'care_request' => $careRequest,
            'final_nurses_count' => $eligibleNurses->count(),
            'matching_level' => $matchedLevel,
        ];
    }

    /**
     * Create the CareRequest record with validated data.
     */
    private function createRequestRecord(array $data, int $userId): CareRequest
    {
        $data['user_id'] = $userId;
        $data['reference_id'] = 'REQ-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
        $data['status'] = CareRequest::STATUS_PENDING;

        // Snapshot commission settings from the CareType
        $careType = CareType::findOrFail($data['care_type_id']);
        $data['commission_type'] = $careType->commision_type;
        $data['commission_value'] = $careType->commision_value;

        return CareRequest::create($data);
    }

    /**
     * Generate cache entries for matched nurses to receive notifications/bids.
     */
    private function createRequestCacheEntries(CareRequest $careRequest, $nurses, int $matchedLevel, Carbon $expiresAt): void
    {
        $now = now();
        $baseSnapshot = [
            'care_request_ref' => $careRequest->reference_id,
            'start_date' => $careRequest->start_date,
            'end_date' => $careRequest->end_date,
            'start_time' => $careRequest->start_time,
            'end_time' => $careRequest->end_time,
            'city' => $careRequest->city,
            'pincode' => $careRequest->pincode,
            'care_type' => $careRequest->careType->name ?? 'Unknown',
        ];

        $records = [];
        foreach ($nurses as $nurse) {
            $records[] = [
                'nurse_id' => $nurse->id,
                'care_request_id' => $careRequest->id,
                'request_snapshot' => json_encode(array_merge($baseSnapshot, [
                    'distance_to_patient' => isset($nurse->distance) ? round($nurse->distance, 2) : null,
                    'matched_level' => $matchedLevel,
                ])),
                'status' => NurseRequestCache::STATUS_NOTIFIED,
                'notified_at' => $now,
                'expires_at' => $expiresAt,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($records)) {
            NurseRequestCache::insert($records);
        }
    }
}
