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
        private readonly OnboardingService $onboardingService,
        private readonly \App\Services\Admin\NurseService $nurseService
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
        return $dt->render('admin.nurses.pending_approval');
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

    public function pendingCount()
    {
        $count = \App\Models\NurseProfile::where('status', \App\Models\NurseProfile::STATUS_UNDER_REVIEW)->count();
        return response()->json(['count' => $count]);
    }

    // ── Show Profile ──────────────────────────────────────
    public function show($id)
    {
        $user = User::with(['nurseProfile.verifications', 'nurseProfile.careTypes'])->findOrFail($id);

        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');

        $profile = $user->nurseProfile;
        $apiToken = $user->tokens()->latest()->first();

        // Route based on status
        if ($profile->status === NurseProfile::STATUS_APPROVED) {
            return view('admin.nurses.show-approved', compact('user', 'profile', 'apiToken'));
        } elseif ($profile->status === NurseProfile::STATUS_UNDER_REVIEW) {
            return view('admin.nurses.show-review', compact('user', 'profile', 'apiToken'));
        } elseif ($profile->status === NurseProfile::STATUS_REJECTED) {
            return view('admin.nurses.show-review', compact('user', 'profile', 'apiToken'));
        } else {
            // PENDING or SUSPENDED
            return view('admin.nurses.show-pending', compact('user', 'profile', 'apiToken'));
        }
    }

    // ── Edit Profile ──────────────────────────────────────
    public function edit($id)
    {
        $user = User::with(['nurseProfile', 'nurseProfile.careTypes'])->findOrFail($id);
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');

        $careTypes = \App\Models\CareType::where('status', 1)->get();

        return view('admin.nurses.edit', compact('user', 'careTypes'));
    }

    // ── Update Profile ────────────────────────────────────
    public function update(\App\Http\Requests\Admin\UpdateNurseRequest $request, $id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');

        try {
            $this->nurseService->updateNurse($user, $request->validated());

            try {
                ActivityLogger::log(
                    Activity::ACTION_UPDATED,
                    'Admin updated nurse profile details.',
                    $user->nurseProfile
                );
            } catch (\Exception $e) {
                // Ignore logging errors to ensure the main transaction remains stable
            }

            return redirect()->route('admin.nurses.edit', $user->id)
                ->with('success', 'Nurse profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update nurse profile. Please try again.');
        }
    }

    public function stats($id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');
        
        $profileId = $user->nurseProfile->id;

        $totalReviews = \App\Models\NurseReview::where('nurse_id', $profileId)->count();
        $avgRating = \App\Models\NurseReview::where('nurse_id', $profileId)->avg('rating') ?? 0;
        
        $totalBookings = \App\Models\Booking::where('nurse_id', $profileId)->count();
        $completedBookings = \App\Models\Booking::where('nurse_id', $profileId)->where('status', \App\Models\Booking::STATUS_COMPLETED)->count();
        
        $trustScore = $user->nurseProfile->trust_score ?? 100;
        if ($totalBookings > 0) {
            $trustScore = round(($completedBookings / $totalBookings) * 100);
        }

        return response()->json([
            'avg_rating' => number_format($avgRating, 1),
            'total_reviews' => $totalReviews,
            'trust_score' => $trustScore,
            'jobs_done' => $completedBookings,
        ]);
    }

    public function reviews(\Illuminate\Http\Request $request, $id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');

        return view('admin.nurses.tabs.reviews', compact('user'));
    }

    public function reviewsData($id)
    {
        $user = User::findOrFail($id);
        
        $reviews = \App\Models\NurseReview::with(['user', 'booking'])
            ->where('nurse_id', $user->nurseProfile->id)
            ->latest();

        return datatables()->of($reviews)
            ->addColumn('user', function ($review) {
                return '<div class="d-flex align-items-center gap-3">
                            <div class="d-flex flex-column">
                                <a href="' . route('admin.patients.show', $review->user->id) . '" class="text-gray-900 text-hover-primary fw-bold fs-7">' . $review->user->name . '</a>
                                <span class="text-gray-500 fs-8">' . $review->user->email . '</span>
                            </div>
                        </div>';
            })
            ->editColumn('booking_id', function ($review) {
                return '<a href="' . route('admin.bookings.show', $review->booking_id) . '" class="text-primary fw-semibold fs-7 hover-underline">#' . $review->booking_id . '</a>';
            })
            ->editColumn('rating', function ($review) {
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $color = $i <= $review->rating ? 'text-warning' : 'text-gray-300';
                    $stars .= '<i class="ki-solid ki-star fs-6 ' . $color . '"></i>';
                }
                return '<div class="d-flex align-items-center gap-1">' . $stars . '<span class="ms-1 fw-bold text-gray-800 fs-7">' . $review->rating . '.0</span></div>';
            })
            ->editColumn('review', function ($review) {
                if ($review->review) {
                    return '<span class="text-gray-700 fs-7">' . htmlspecialchars($review->review) . '</span>';
                }
                return '<span class="text-gray-400 fs-7 fst-italic">No text review</span>';
            })
            ->editColumn('created_at', function ($review) {
                return '<span class="text-gray-600 fs-7">' . $review->created_at->format('d M Y') . '<br><span class="fs-8 text-gray-500">' . $review->created_at->format('h:i A') . '</span></span>';
            })
            ->rawColumns(['user', 'booking_id', 'rating', 'review', 'created_at'])
            ->make(true);
    }

    public function bids($id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');

        return view('admin.nurses.tabs.bids', compact('user'));
    }

    public function bidsData($id)
    {
        $user = User::findOrFail($id);
        
        $bids = \App\Models\RequestBid::with('careRequest')->where('nurse_id', $user->id)->latest();

        return datatables()->of($bids)
            ->addColumn('request', function ($bid) {
                return '<a href="' . route('admin.requests.show', $bid->care_request_id) . '" class="text-primary fw-bold text-hover-primary mb-1 fs-6">#' . $bid->care_request_id . '</a>';
            })
            ->editColumn('amount', function ($bid) {
                return '<span class="fw-bold text-success fs-6">$' . number_format($bid->total_amount, 2) . '</span>';
            })
            ->editColumn('status', function ($bid) {
                $statusMap = [
                    \App\Models\RequestBid::STATUS_PENDING => ['class' => 'badge-light-warning', 'text' => 'Pending'],
                    \App\Models\RequestBid::STATUS_SELECTED => ['class' => 'badge-light-success', 'text' => 'Selected'],
                    \App\Models\RequestBid::STATUS_REJECTED => ['class' => 'badge-light-danger', 'text' => 'Rejected'],
                    \App\Models\RequestBid::STATUS_EXPIRED => ['class' => 'badge-light-secondary', 'text' => 'Expired'],
                    \App\Models\RequestBid::STATUS_CANCELLED => ['class' => 'badge-light-danger', 'text' => 'Cancelled'],
                ];
                
                $status = $statusMap[$bid->status] ?? ['class' => 'badge-light-secondary', 'text' => 'Unknown'];
                
                return '<span class="badge ' . $status['class'] . '">' . $status['text'] . '</span>';
            })
            ->editColumn('created_at', function ($bid) {
                return '<span class="text-gray-600 fs-7">' . $bid->created_at->format('d M Y') . '<br><span class="fs-8 text-gray-500">' . $bid->created_at->format('h:i A') . '</span></span>';
            })
            ->rawColumns(['request', 'amount', 'status', 'created_at'])
            ->make(true);
    }

    public function showApplication($id)
    {
        $user = User::with('nurseProfile')->findOrFail($id);
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');
        $profile = $user->nurseProfile;

        return view('admin.nurses.show-review', compact('user', 'profile'));
    }

    // ── Review Processing ─────────────────────────────────
    public function reviewStep(Request $request, $id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');

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
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');
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
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');

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

    public function bookings($id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');

        return view('admin.nurses.tabs.bookings', compact('user'));
    }

    public function bookingsData($id)
    {
        $user = User::findOrFail($id);
        $profile = $user->nurseProfile;

        $bookings = \App\Models\Booking::with(['user'])
            ->where('nurse_id', $profile->id)
            ->latest();

        return datatables()->of($bookings)
            ->editColumn('reference_id', function ($booking) {
                return '<a href="' . route('admin.bookings.show', $booking->id) . '" class="text-primary fw-bold text-hover-primary mb-1 fs-6">#' . $booking->reference_id . '</a>';
            })
            ->editColumn('user', function ($booking) {
                if (!$booking->user) return '<span class="text-muted">Unassigned</span>';
                $patientUser = $booking->user;
                $img = $patientUser->profile_photo ? '<img src="' . \Storage::url($patientUser->profile_photo) . '" alt="avatar" />' : '<span class="symbol-label bg-light-primary text-primary fw-bold">' . mb_strtoupper(mb_substr($patientUser->name, 0, 1)) . '</span>';
                return '
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-30px symbol-circle">' . $img . '</div>
                    <a href="' . route('admin.patients.show', $patientUser->id) . '" class="text-gray-900 text-hover-primary fw-bold fs-7">' . $patientUser->name . '</a>
                </div>';
            })
            ->editColumn('status', function ($booking) {
                $statusColors = [
                    0 => 'warning',
                    1 => 'primary',
                    2 => 'info',
                    3 => 'success',
                    4 => 'danger'
                ];
                $color = $statusColors[$booking->status] ?? 'dark';
                return '<span class="badge badge-light-' . $color . ' border border-' . $color . '">' . $booking->status_text . '</span>';
            })
            ->editColumn('payment_status', function ($booking) {
                $statusColors = [
                    0 => 'warning',
                    1 => 'success',
                    2 => 'danger',
                    3 => 'info'
                ];
                $color = $statusColors[$booking->payment_status] ?? 'dark';
                return '<span class="badge badge-light-' . $color . ' border border-' . $color . '">' . $booking->payment_status_text . '</span>';
            })
            ->editColumn('total_amount', function ($booking) {
                return '<span class="fw-bold text-success">₹' . number_format($booking->total_amount, 2) . '</span>';
            })
            ->editColumn('created_at', function ($booking) {
                return '<span class="text-gray-600 fs-7">' . $booking->created_at->format('d M Y') . '</span>';
            })
            ->rawColumns(['reference_id', 'user', 'status', 'payment_status', 'total_amount', 'created_at'])
            ->make(true);
    }

    public function loginHistory($id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isNurse() && $user->nurseProfile, 404, 'Nurse profile not found.');

        return view('admin.nurses.tabs.login-history', compact('user'));
    }

    public function loginHistoryData($id)
    {
        $user = User::findOrFail($id);
        
        $logins = \App\Models\LoginHistory::where('user_id', $user->id)->latest();

        return datatables()->of($logins)
            ->editColumn('ip_address', function ($login) {
                return '<span class="fw-bold text-gray-800">' . $login->ip_address . '</span>';
            })
            ->editColumn('user_agent', function ($login) {
                $icon = 'ki-screen';
                if (stripos($login->user_agent, 'mobile') !== false || stripos($login->user_agent, 'android') !== false || stripos($login->user_agent, 'iphone') !== false) {
                    $icon = 'ki-phone';
                }
                return '<div class="d-flex align-items-center"><i class="ki-outline ' . $icon . ' fs-3 me-2 text-primary"></i><span class="text-truncate d-inline-block" style="max-width:250px;" title="' . htmlspecialchars($login->user_agent) . '">' . \Str::limit($login->user_agent, 40) . '</span></div>';
            })
            ->editColumn('created_at', function ($login) {
                return '<span class="text-gray-600 fs-7">' . $login->created_at->format('d M Y, h:i A') . '</span>';
            })
            ->addColumn('action', function ($login) {
                return '<a href="' . route('admin.login-history.show', $login->id) . '" class="btn btn-sm btn-icon btn-light-info border border-info"><i class="ki-outline ki-eye fs-4"></i></a>';
            })
            ->rawColumns(['ip_address', 'user_agent', 'created_at', 'action'])
            ->make(true);
    }
}