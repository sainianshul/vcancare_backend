<?php

namespace App\Services;

use App\Exceptions\CareRequestException;
use App\Models\CareRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CareRequestService
{
    public function createCareRequest(array $data, int $userId): CareRequest
    {
        try {
            $data['user_id'] = $userId;
            $data['uuid'] = Str::uuid()->toString();
            $data['status'] = CareRequest::STATUS_PENDING;

            $careRequest = CareRequest::create($data);

            // Dispatch the job to find eligible nurses in the background
            \App\Jobs\MatchNursesForCareRequest::dispatch($careRequest);

            return $careRequest;
        } catch (\Exception $e) {
            Log::error('Care Request Creation Failed: ' . $e->getMessage());
            throw new CareRequestException('Failed to create care request. Please try again.');
        }
    }
}
