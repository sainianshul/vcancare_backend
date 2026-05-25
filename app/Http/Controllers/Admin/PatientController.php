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
            'phone' => 'required|string|max:20|unique:users,phone,' . $patient->id,
            'email' => 'nullable|email|unique:users,email,' . $patient->id,
            'status' => 'required|integer|in:' . implode(',', array_keys(User::getStatusList())),
        ]);

        $patient->update([
            'phone' => $request->phone,
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
    public function stats(User $patient)
    {
        $totalRequests = \App\Models\CareRequest::where('user_id', $patient->id)->count();
        $completedRequests = \App\Models\CareRequest::where('user_id', $patient->id)->where('status', \App\Models\CareRequest::STATUS_COMPLETED)->count();

        return response()->json([
            'total_requests' => $totalRequests,
            'completed' => $completedRequests,
        ]);
    }

    public function requests(User $patient)
    {
        return view('admin.patients.tabs.requests', compact('patient'));
    }

    public function requestsData(User $patient, \App\DataTables\Request\RequestDataTable $dataTable)
    {
        request()->merge(['user_id' => $patient->id]);
        return $dataTable->ajax();
    }

    public function bookings(User $patient)
    {
        return view('admin.patients.tabs.bookings', compact('patient'));
    }

    public function bookingsData(User $patient, \App\DataTables\Booking\BookingDataTable $dataTable)
    {
        request()->merge(['user_id' => $patient->id]);
        return $dataTable->ajax();
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