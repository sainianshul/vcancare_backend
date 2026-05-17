<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\DataTables\Nurses\AllNursesDataTable;
use App\DataTables\Nurses\UnderReviewNurseDataTable;
use App\DataTables\Nurses\ApprovedNursesDataTable;
use App\DataTables\Nurses\RejectedNursesDataTable;
use App\Models\Activity;
use App\Models\NurseProfile;
use App\Models\NurseProfileVerification;
use App\Models\User;
use App\Services\OnboardingService;
use Illuminate\Http\Request;

class NurseController extends Controller
{
    public function __construct(
        private readonly OnboardingService $onboardingService
    ) {
    }
    // ── All ────────────────────────────────────────────────
    public function index(AllNursesDataTable $dt)
    {
        return $dt->render('admin.nurses.index');
    }

    public function indexData(AllNursesDataTable $dt)
    {
        return $dt->ajax();
    }

    // ── Pending Approval ───────────────────────────────────
    public function pending(UnderReviewNurseDataTable $dt)
    {
        return $dt->render('admin.nurses.pending');
    }

    public function pendingData(UnderReviewNurseDataTable $dt)
    {
        return $dt->ajax();
    }

    // ── Approved ──────────────────────────────────────────
    public function approved(ApprovedNursesDataTable $dt)
    {
        return $dt->render('admin.nurses.approved');
    }

    public function approvedData(ApprovedNursesDataTable $dt)
    {
        return $dt->ajax();
    }

    // ── Rejected ──────────────────────────────────────────
    public function rejected(RejectedNursesDataTable $dt)
    {
        return $dt->render('admin.nurses.rejected');
    }

    public function rejectedData(RejectedNursesDataTable $dt)
    {
        return $dt->ajax();
    }

    // ── Delete ────────────────────────────────────────────
    public function destroy($id)
    {
        $user = \App\Models\User::where('role', 2)->findOrFail($id);
        $user->delete();

        return response()->json(['success' => true]);
    }

    // ── Show Profile ──────────────────────────────────────
    public function show($id)
    {
        $user = User::with(['nurseProfile.verifications', 'nurseProfile.careTypes'])->findOrFail($id);

        if (!$user->nurseProfile) {
            abort(404, 'Nurse profile not found.');
        }

        $profile = $user->nurseProfile;

        // Route based on status
        if ($profile->status === NurseProfile::STATUS_APPROVED) {
            return view('admin.nurses.show-approved', compact('user', 'profile'));
        } elseif ($profile->status === NurseProfile::STATUS_UNDER_REVIEW) {
            return view('admin.nurses.show-review', compact('user', 'profile'));
        } elseif ($profile->status === NurseProfile::STATUS_REJECTED) {
            return view('admin.nurses.show-review', compact('user', 'profile'));
        } else {
            // PENDING or SUSPENDED
            return view('admin.nurses.show-pending', compact('user', 'profile'));
        }
    }

    public function showApplication($id)
    {
        $user = User::with('nurseProfile')->findOrFail($id);
        $profile = $user->nurseProfile;

        return view('admin.nurses.show-review', compact('user', 'profile'));
    }

    // ── Review Processing ─────────────────────────────────
    public function reviewStep(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'step_id' => 'required|integer',
            'status' => 'required|integer',
            'reason' => 'nullable|string',
        ]);

        $this->onboardingService->reviewStep($user, $request->step_id, $request->status, $request->reason);

        return response()->json(['success' => true, 'message' => 'Step verification updated.']);
    }

    public function getReviewStepView(Request $request, $id, $step)
    {
        $user = User::findOrFail($id);
        $profile = $user->nurseProfile;

        if ($step === 'final') {
            return view('admin.nurses.review-steps.final', compact('user', 'profile'));
        }

        $stepId = (int) $step;
        $sectionData = $this->onboardingService->getStepData($user, $stepId);

        $verification = $profile->verifications->where('step_id', $stepId)->first();
        $status = $verification ? $verification->status : NurseProfileVerification::STATUS_PENDING;

        $viewMap = [
            NurseProfile::STEP_BASIC_PROFILE => 'basic_profile',
            NurseProfile::STEP_CARE_TYPES => 'care_types',
            NurseProfile::STEP_EDUCATION => 'education',
            NurseProfile::STEP_WORK_HISTORY => 'work_history',
            NurseProfile::STEP_DOCUMENTS => 'documents',
            NurseProfile::STEP_AVAILABILITY => 'availability',
        ];

        $viewName = $viewMap[$stepId] ?? 'basic_profile';

        $isReadOnly = $request->query('readonly', 0) == 1;

        return view('admin.nurses.review-steps.' . $viewName, compact('user', 'profile', 'stepId', 'sectionData', 'status', 'verification', 'isReadOnly'));
    }

    public function finalizeReview(\Illuminate\Http\Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'status' => 'required|integer',
            'reason' => 'nullable|string',
            'can_reapply' => 'nullable|boolean',
        ]);

        $this->onboardingService->finalizeReview($user, $request->status, $request->reason, $request->can_reapply);

        $actionType = $request->status == NurseProfile::STATUS_APPROVED
            ? Activity::ACTION_APPROVED
            : Activity::ACTION_REJECTED;

        ActivityLogger::log(
            $actionType,
            'Admin finalized application review.',
            $user->nurseProfile,
            [
                'status' => $request->status,
                'reason' => $request->reason
            ]
        );

        return response()->json(['success' => true, 'message' => 'Application review finalized.']);
    }
}