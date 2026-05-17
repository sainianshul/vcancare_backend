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
        // Ensure the user is a patient
        abort_unless($patient->isUser(), 404);

        return view('admin.patients.show', compact('patient'));
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
}