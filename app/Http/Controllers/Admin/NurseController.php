<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Nurses\AllNursesDataTable;
use App\DataTables\Nurses\UnderReviewNurseDataTable;
use App\DataTables\Nurses\ApprovedNursesDataTable;
use App\DataTables\Nurses\RejectedNursesDataTable;

class NurseController extends Controller
{
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
        $user = \App\Models\User::with(['nurseProfile.verifications', 'nurseProfile.careTypes'])->findOrFail($id);

        if (!$user->nurseProfile) {
            abort(404, 'Nurse profile not found.');
        }

        $profile = $user->nurseProfile;

        // Route based on status
        if ($profile->status === \App\Models\NurseProfile::STATUS_APPROVED) {
            return view('admin.nurses.show-approved', compact('user', 'profile'));
        } elseif ($profile->status === \App\Models\NurseProfile::STATUS_UNDER_REVIEW) {
            return view('admin.nurses.show-review', compact('user', 'profile'));
        } elseif ($profile->status === \App\Models\NurseProfile::STATUS_REJECTED) {
            return view('admin.nurses.show-review', compact('user', 'profile'));
        } else {
            // PENDING or SUSPENDED
            return view('admin.nurses.show-pending', compact('user', 'profile'));
        }
    }

    public function showApplication($id)
    {
        $user = \App\Models\User::with('nurseProfile')->findOrFail($id);
        $profile = $user->nurseProfile;

        return view('admin.nurses.show-review', compact('user', 'profile'));
    }

    // ── Review Processing ─────────────────────────────────
    public function reviewStep(\Illuminate\Http\Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $request->validate([
            'step_id' => 'required|integer',
            'status' => 'required|integer',
            'reason' => 'nullable|string',
        ]);

        $service = app(\App\Services\OnboardingService::class);
        $service->reviewStep($user, $request->step_id, $request->status, $request->reason);

        return response()->json(['success' => true, 'message' => 'Step verification updated.']);
    }

    public function getReviewStepView(\Illuminate\Http\Request $request, $id, $step)
    {
        $user = \App\Models\User::findOrFail($id);
        $profile = $user->nurseProfile;

        if ($step === 'final') {
            return view('admin.nurses.review-steps.final', compact('user', 'profile'));
        }

        $stepId = (int)$step;
        $service = app(\App\Services\OnboardingService::class);
        $sectionData = $service->getStepData($user, $stepId);
        
        $verification = $profile->verifications->where('step_id', $stepId)->first();
        $status = $verification ? $verification->status : \App\Models\NurseProfileVerification::STATUS_PENDING;

        $viewMap = [
            \App\Models\NurseProfile::STEP_BASIC_PROFILE => 'basic_profile',
            \App\Models\NurseProfile::STEP_CARE_TYPES => 'care_types',
            \App\Models\NurseProfile::STEP_EDUCATION => 'education',
            \App\Models\NurseProfile::STEP_WORK_HISTORY => 'work_history',
            \App\Models\NurseProfile::STEP_DOCUMENTS => 'documents',
            \App\Models\NurseProfile::STEP_AVAILABILITY => 'availability',
        ];

        $viewName = $viewMap[$stepId] ?? 'basic_profile';
        
        $isReadOnly = $request->query('readonly', 0) == 1;

        return view('admin.nurses.review-steps.' . $viewName, compact('user', 'profile', 'stepId', 'sectionData', 'status', 'verification', 'isReadOnly'));
    }

    public function finalizeReview(\Illuminate\Http\Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'status' => 'required|integer',
            'reason' => 'nullable|string',
            'can_reapply' => 'nullable|boolean',
        ]);

        $service = app(\App\Services\OnboardingService::class);
        $service->finalizeReview($user, $request->status, $request->reason, $request->can_reapply);

        return response()->json(['success' => true, 'message' => 'Application review finalized.']);
    }
}