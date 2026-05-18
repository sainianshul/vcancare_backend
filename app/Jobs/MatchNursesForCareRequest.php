<?php

namespace App\Jobs;

use App\Models\CareRequest;
use App\Models\NurseProfile;
use App\Models\NurseRequestCache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MatchNursesForCareRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $careRequest;

    /**
     * Create a new job instance.
     */
    public function __construct(CareRequest $careRequest)
    {
        $this->careRequest = $careRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lat = $this->careRequest->latitude;
        $lng = $this->careRequest->longitude;
        $careTypeId = $this->careRequest->care_type_id;
        
        $startDate = Carbon::parse($this->careRequest->start_date);
        $dayOfWeek = $startDate->dayOfWeek; // 0 (Sunday) - 6 (Saturday)

        // Basic Haversine Formula for Distance Calculation (5km radius)
        $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude))))";

        $eligibleNurses = NurseProfile::query()
            ->select('nurse_profiles.*')
            ->selectRaw("{$haversine} AS distance")
            // 1. Distance filter (Fastest check first)
            ->whereRaw("{$haversine} <= ?", [5])
            
            // 2. Is Active and Approved toggle
            ->where('is_available', true)
            ->where('status', NurseProfile::STATUS_APPROVED)
            
            // 3. Provided Selected Service
            ->whereHas('careTypes', function ($query) use ($careTypeId) {
                $query->where('care_types.id', $careTypeId);
            })
            
            // 4. Time matching 
            ->when($this->careRequest->start_time && $this->careRequest->end_time, function($query) {
                $query->whereTime('available_from', '<=', $this->careRequest->start_time)
                      ->whereTime('available_to', '>=', $this->careRequest->end_time);
            })
            
            // Note: If you need to filter by day_of_week via JSON, uncomment below.
            // ->whereJsonContains('available_days', $dayOfWeek) 
            
            ->get();

        // 5. Filter out nurses with overlapping schedules
        $finalNurses = $eligibleNurses->filter(function ($nurse) {
            // Check day availability in PHP if JSON matching in SQL is tricky due to format (e.g. string vs int)
            // if (!in_array(Carbon::parse($this->careRequest->start_date)->format('w'), $nurse->available_days ?? [])) {
            //    return false;
            // }

            $hasOverlap = CareRequest::where('assigned_nurse_id', $nurse->id)
                ->whereIn('status', [CareRequest::STATUS_CONFIRMED, CareRequest::STATUS_IN_PROGRESS])
                ->where(function ($query) {
                    $reqStart = $this->careRequest->start_date;
                    $reqEnd = $this->careRequest->end_date ?? $this->careRequest->start_date;
                    
                    $query->where('start_date', '<=', $reqEnd)
                          ->where('end_date', '>=', $reqStart);
                })
                ->when($this->careRequest->start_time && $this->careRequest->end_time, function ($query) {
                    $query->where(function ($timeQ) {
                        $timeQ->where('start_time', '<', $this->careRequest->end_time)
                              ->where('end_time', '>', $this->careRequest->start_time);
                    });
                })
                ->exists();

            return !$hasOverlap;
        });

        // 6. Save data to Nurse Request cache table
        $records = [];
        $now = now();
        $expiresAt = $now->copy()->addHours(24); // Expiration time for cache

        // Create snapshot to avoid multiple joins later
        $snapshot = json_encode([
            'care_request_uuid' => $this->careRequest->uuid,
            'start_date' => $this->careRequest->start_date,
            'end_date' => $this->careRequest->end_date,
            'start_time' => $this->careRequest->start_time,
            'end_time' => $this->careRequest->end_time,
            'distance_to_patient' => null, // Placeholder, can be filled dynamically 
        ]);

        foreach ($finalNurses as $nurse) {
            $records[] = [
                'nurse_id' => $nurse->id,
                'care_request_id' => $this->careRequest->id,
                'request_snapshot' => json_encode(array_merge(json_decode($snapshot, true), [
                    'distance_to_patient' => round($nurse->distance, 2)
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
            
            // Optionally, update the CareRequest status to SEARCHING
            $this->careRequest->update([
                'status' => CareRequest::STATUS_SEARCHING
            ]);
        }
    }
}
