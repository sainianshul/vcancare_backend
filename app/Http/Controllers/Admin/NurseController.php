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
}