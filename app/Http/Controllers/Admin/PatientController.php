<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PatientDataTable;
use App\DataTables\BlockedPatientDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(PatientDataTable $dataTable)
    {
        return $dataTable->render('admin.patients.index');
    }

    public function data(PatientDataTable $dataTable)
    {
        return $dataTable->ajax();
    }

    public function show(User $patient)
    {
        abort_unless($patient->isUser(), 404);

        $apiToken = $patient->tokens()->latest()->first();

        return view('admin.patients.show', compact('patient', 'apiToken'));
    }

    public function edit(User $patient)
    {
        abort_unless($patient->isUser(), 404);

        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, User $patient)
    {
        abort_unless($patient->isUser(), 404);

        $request->validate([
            'email' => 'nullable|email|unique:users,email,' . $patient->id,
            'status' => 'required|integer|in:' . implode(',', array_keys(User::getStatusList())),
        ]);

        $patient->update([
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Patient updated successfully.');
    }

    public function destroy(User $patient)
    {
        abort_unless($patient->isUser(), 404);

        $patient->delete();

        return response()->json(['success' => true, 'message' => 'Patient deleted successfully.']);
    }

    public function blocked(BlockedPatientDataTable $dataTable)
    {
        return $dataTable->render('admin.patients.blocked');
    }

    public function blockedData(BlockedPatientDataTable $dataTable)
    {
        return $dataTable->ajax();
    }

    public function unblock(User $patient)
    {
        abort_unless($patient->isUser(), 404);

        $patient->update([
            'status' => User::STATUS_ACTIVE,
            'blocked_reason' => null,
            // 'blocked_at' => null // If we add this to DB later
        ]);

        return response()->json(['success' => true, 'message' => 'Patient unblocked successfully.']);
    }
    public function bookings(User $patient)
    {
        return view('admin.patients.tabs.bookings', compact('patient'));
    }

    public function bookingsData(User $patient)
    {
        $bookings = \App\Models\Booking::with(['nurse', 'nurse.user'])
            ->where('user_id', $patient->id)
            ->latest();

        return datatables()->of($bookings)
            ->editColumn('reference_id', function ($booking) {
                return '<a href="' . route('admin.bookings.show', $booking->id) . '" class="text-primary fw-bold text-hover-primary mb-1 fs-6">#' . $booking->reference_id . '</a>';
            })
            ->editColumn('nurse', function ($booking) {
                if (!$booking->nurse || !$booking->nurse->user) return '<span class="text-muted">Unassigned</span>';
                $nurseUser = $booking->nurse->user;
                $img = $nurseUser->profile_photo ? '<img src="' . \Storage::url($nurseUser->profile_photo) . '" alt="avatar" />' : '<span class="symbol-label bg-light-info text-info fw-bold">' . mb_strtoupper(mb_substr($nurseUser->name, 0, 1)) . '</span>';
                return '
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-30px symbol-circle">' . $img . '</div>
                    <a href="' . route('admin.nurses.show', $nurseUser->id) . '" class="text-gray-900 text-hover-primary fw-bold fs-7">' . $nurseUser->name . '</a>
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
            ->rawColumns(['reference_id', 'nurse', 'status', 'payment_status', 'total_amount', 'created_at'])
            ->make(true);
    }

    public function loginHistory(User $patient)
    {
        return view('admin.patients.tabs.login-history', compact('patient'));
    }

    public function loginHistoryData(User $patient)
    {
        $logins = \App\Models\LoginHistory::where('user_id', $patient->id)->latest();

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