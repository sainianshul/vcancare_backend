<?php
// app/Http/Controllers/Api/Nurse/OnboardingController.php
namespace App\Http\Controllers\Api\Nurse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Nurse\Onboarding\AvailabilityRequest;
use App\Http\Requests\Api\Nurse\Onboarding\BasicProfileRequest;
use App\Helpers\ApiResponse;
use App\Http\Requests\Api\Nurse\Onboarding\CareTypeRequest;
use App\Http\Requests\Api\Nurse\Onboarding\DocumentRequest;
use App\Http\Requests\Api\Nurse\Onboarding\EducationRequest;
use App\Http\Requests\Api\Nurse\Onboarding\WorkHistoryRequest;
use App\Models\NurseProfile;
use App\Services\Nurse\OnboardingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{

    public function __construct(private OnboardingService $onboardingService)
    {
    }

    /**
     * Save Basic Profile
     *
     * Save nurse basic profile information.
     *
     * @group Nurse Onboarding
     * @authenticated
     * @multipart
     */
    public function saveBasicProfile(BasicProfileRequest $request)
    {
        $this->onboardingService->saveBasicProfile($request->user(), $request->validated());

        $nurseProfile = $request->user()->nurseProfile->fresh();

        return ApiResponse::success(
            message: 'Profile saved successfully.',
            data: [
                'onboarding' => $nurseProfile->getOnboardingResponse(),
            ]
        );
    }


    /**
     * Save Care Types
     *
     * Save nurse care types.
     *
     * @bodyParam care_type_ids array required Array of care type IDs.
     * Example request:
     * {
     *   "care_type_ids": [1,2,3]
     * }
     * 
     * @group Nurse Onboarding
     * @authenticated
     */
    public function saveCareTypes(CareTypeRequest $request)
    {
        $this->onboardingService->saveCareTypes($request->user(), $request->validated());

        $nurseProfile = $request->user()->nurseProfile->fresh();

        return ApiResponse::success(
            message: 'Care types saved successfully.',
            data: [
                'onboarding' => $nurseProfile->getOnboardingResponse(),
            ]
        );
    }

    /**
     * Save Education
     *
     * Save nurse education details.
     *
     * Example Request:
     *
     * {
     *   "educations": [
     *     {
     *       "degree_or_course": "B.Sc Nursing",
     *       "institute_name": "PGI Chandigarh",
     *       "field_of_study": "Nursing",
     *       "start_year": 2018,
     *       "end_year": 2022,
     *       "is_currently_studying": false
     *     },
     *     {
     *       "degree_or_course": "ICU Certification",
     *       "institute_name": "AIIMS Delhi",
     *       "field_of_study": "Critical Care",
     *       "start_year": 2023,
     *       "end_year": null,
     *       "is_currently_studying": true
     *     }
     *   ]
     * }
     *
     * @group Nurse Onboarding
     * @authenticated
     */
    public function saveEducation(EducationRequest $request)
    {
        $this->onboardingService->saveEducation($request->user(), $request->validated());

        $nurseProfile = $request->user()->nurseProfile->fresh();

        return ApiResponse::success(
            message: 'Education details saved successfully.',
            data: [
                'onboarding' => $nurseProfile->getOnboardingResponse(),
            ],
        );
    }


    /**
     * Save Work History
     *
     * Save nurse work history details.
     *
     * Example Request:
     *
     * {
     *   "work_histories": [
     *     {
     *       "role_or_position": "ICU Nurse",
     *       "organization_name": "PGI Chandigarh",
     *       "location": "Chandigarh",
     *       "start_date": "2020-01-01",
     *       "end_date": "2023-12-31",
     *       "is_currently_working": false,
     *       "description": "Worked in ICU department."
     *     },
     *     {
     *       "role_or_position": "Senior Nurse",
     *       "organization_name": "AIIMS Delhi",
     *       "location": "Delhi",
     *       "start_date": "2024-01-01",
     *       "end_date": null,
     *       "is_currently_working": true,
     *       "description": "Currently working in emergency ward."
     *     }
     *   ]
     * }
     *
     * @group Nurse Onboarding
     * @authenticated
     */
    public function saveWorkHistory(WorkHistoryRequest $request): JsonResponse
    {
        $this->onboardingService->saveWorkHistory($request->user(), $request->validated());

        $nurseProfile = $request->user()->nurseProfile->fresh();

        return ApiResponse::success(
            message: 'Work history saved successfully.',
            data: [
                'onboarding' => $nurseProfile->getOnboardingResponse()
            ],
        );
    }

    /**
     * Save Documents
     *
     * Save nurse verification documents.
     *
     * Example Request:
     *
     * multipart/form-data
     *
     * aadhar_document = <file>
     * pan_document = <file>
     * nursing_certificate_document = <file>
     *
     * @group Nurse Onboarding
     * @authenticated
     * @multipart
     */
    public function saveDocuments(
        DocumentRequest $request
    ) {
        $this->onboardingService
            ->saveDocuments(
                $request->user(),
                $request->validated()
            );

        $nurseProfile = $request->user()->nurseProfile->fresh();

        return ApiResponse::success(
            message: 'Documents uploaded successfully.',
            data: [
                'onboarding' => $nurseProfile->getOnboardingResponse()
            ]
        );
    }

    /**
     * Save Availability
     *
     * Save nurse availability details.
     *
     * Example Request:
     *
     * ```json
     * {
     *   "available_from": "09:00",
     *   "available_to": "18:00",
     *   "available_days": [
     *     "monday",
     *     "tuesday",
     *     "wednesday",
     *     "thursday",
     *     "friday"
     *   ],
     *   "is_available": true
     * }
     * ```
     *
     * @group Nurse Onboarding
     * @authenticated
     */
    public function saveAvailability(
        AvailabilityRequest $request
    ) {
        $this->onboardingService
            ->saveAvailability(
                $request->user(),
                $request->validated()
            );

        $nurseProfile = $request->user()->nurseProfile->fresh();

        return ApiResponse::success(
            message: 'Availability saved successfully.',
            data: [
                'onboarding' => $nurseProfile->getOnboardingResponse()
            ]
        );
    }


    /**
     * Submit For Review
     *
     * Submit nurse onboarding profile for admin review.
     *
     * @group Nurse Onboarding
     * @authenticated
     */
    public function submitForReview(Request $request)
    {
        $this->onboardingService->submitForReview($request->user());

        return ApiResponse::success(
            message: 'Profile submitted for review successfully.',
            data: [
                'onboarding' => ['is_completed' => true],
                'profile_status' => NurseProfile::STATUS_UNDER_REVIEW,
                'profile_status_name' => NurseProfile::getStatusList()[NurseProfile::STATUS_UNDER_REVIEW],
            ]
        );
    }


    /**
     * Reapply
     *
     * Re-submit rejected onboarding profile.
     *
     * @group Nurse Onboarding
     * @authenticated
     */
    public function reapply(Request $request)
    {
        $this->onboardingService->reapply($request->user());

        $nurseProfile = $request->user()->nurseProfile->fresh();

        return ApiResponse::success(
            message: 'Profile reapplication started successfully.',
            data: [
                'onboarding' => $nurseProfile->getOnboardingResponse(),
                'profile_status' =>
                    NurseProfile::STATUS_PENDING,
                'profile_status_name' =>
                    NurseProfile::getStatusList()[
                        NurseProfile::STATUS_PENDING
                    ],
            ]
        );
    }

    /**
     * Get Step Data
     *
     * Get onboarding step data for edit or prefill.
     *
     * @urlParam step integer required Onboarding step number. Example: 3
     *
     * Available Steps:
     *
     * 1 = Basic Profile
     * 2 = Care Types
     * 3 = Education
     * 4 = Work History
     * 5 = Documents
     * 6 = Availability
     *
     * @group Nurse Onboarding
     * @authenticated
     */
    public function getStepData(Request $request, int $step)
    {
        $data = $this->onboardingService
            ->getStepData(
                $request->user(),
                $step
            );

        return ApiResponse::success(
            message: 'Step data fetched successfully.',
            data: $data
        );
    }




}