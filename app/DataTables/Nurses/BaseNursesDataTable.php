<?php

namespace App\DataTables\Nurses;

use App\Models\User;
use App\Models\NurseProfile;
use Yajra\DataTables\Services\DataTable;

abstract class BaseNursesDataTable extends DataTable
{
    // Override in subclass to auto-filter by nurse_profile.status
    protected array $profileStatuses = [];

    // ── Common Columns ─────────────────────────────────────

    protected function nurseColumn($dt)
    {
        return $dt->addColumn('nurse', function (User $user) {
            $contact = e($user->email ?? $user->phone ?? '—');
            $avatar = '<div class="symbol symbol-38px symbol-circle">' . $user->avatar_html . '</div>';

            return '
                <div class="d-flex align-items-center gap-3">
                    ' . $avatar . '
                    <div class="d-flex flex-column">
                        <span class="text-gray-800 fw-semibold fs-6 lh-1 mb-1">' . e($user->name) . '</span>
                        <span class="text-muted fw-normal fs-7">' . $contact . '</span>
                    </div>
                </div>
            ';
        });
    }

    protected function locationColumn($dt)
    {
        return $dt->addColumn('location', function (User $user) {

            $profile = $user->nurseProfile;
            $parts = array_filter([$profile?->city, $profile?->state]);

            return '
                <div class="d-flex flex-column">
                    <span class="fw-semibold text-gray-800">' . e(implode(', ', $parts) ?: '—') . '</span>
                    <span class="text-muted fs-7">' . e($profile?->country ?? '—') . '</span>
                </div>
            ';
        });
    }

    protected function joinedColumn($dt)
    {
        return $dt->editColumn('created_at', function (User $user) {
            return '
                <div class="fw-semibold text-gray-800">' . $user->created_at->format('d M Y') . '</div>
                <div class="text-muted fs-7">' . $user->created_at->diffForHumans() . '</div>
            ';
        });
    }

    protected function actionsColumn($dt)
    {
        return $dt->addColumn('actions', function (User $user) {

            $viewUrl = route('admin.nurses.show', $user->id);
            $editUrl = route('admin.nurses.edit', $user->id);

            return '
                <div class="d-flex gap-1 justify-content-end">
                    <a href="' . $viewUrl . '" class="btn btn-sm btn-icon btn-light-primary border border-primary w-30px h-30px me-1" title="View">
                        <i class="ki-outline ki-eye fs-5"></i>
                    </a>
                    <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-light-warning border border-warning w-30px h-30px me-1" title="Edit">
                        <i class="ki-outline ki-pencil fs-5"></i>
                    </a>
                    <button type="button"
                        class="btn btn-sm btn-icon btn-light-danger border border-danger w-30px h-30px btn-delete"
                        data-id="' . $user->id . '"
                        title="Delete">
                        <i class="ki-outline ki-trash fs-5"></i>
                    </button>
                </div>
            ';
        });
    }

    protected function profileStatusColumn($dt)
    {
        return $dt->addColumn('profile_status', function (User $user) {

            $status = $user->nurseProfile?->status;
            $map = [
                NurseProfile::STATUS_PENDING => ['Pending', 'warning', 'ki-time'],
                NurseProfile::STATUS_UNDER_REVIEW => ['Under Review', 'info', 'ki-information-5'],
                NurseProfile::STATUS_APPROVED => ['Approved', 'success', 'ki-check-circle'],
                NurseProfile::STATUS_REJECTED => ['Rejected', 'danger', 'ki-cross-circle'],
                NurseProfile::STATUS_SUSPENDED => ['Suspended', 'dark', 'ki-cross-circle'],
            ];

            if (!array_key_exists($status, $map)) {
                $status = NurseProfile::STATUS_PENDING;
            }

            return '<span class="badge badge-light-' . $map[$status][1] . ' border border-' . $map[$status][1] . ' fw-bold px-3 py-2">'
                . '<i class="ki-outline ' . $map[$status][2] . ' fs-6 text-' . $map[$status][1] . ' me-1"></i>'
                . e($map[$status][0]) . '</span>';
        });
    }

    // ── Shared Query ───────────────────────────────────────

    public function query(User $model)
    {
        $query = $model->newQuery()
            ->with('nurseProfile')
            ->where('users.role', 2)
            ->select('users.*');

        // Subclass se profileStatuses aayega
        if (!empty($this->profileStatuses)) {
            $query->whereHas('nurseProfile', function ($q) {
                $q->whereIn('status', $this->profileStatuses);
            });
        }

        // Subclass se userStatus aayega
        if (!empty($this->userStatus)) {
            $query->where('users.status', $this->userStatus);
        }

        // Name search
        if ($search = request('search.value')) {
            $query->where('users.name', 'LIKE', "%{$search}%");
        }

        return $query;
    }

    protected function filterByName($dt)
    {
        return $dt->filterColumn('nurse', function ($query, $keyword) {
            $query->where('users.name', 'LIKE', "%{$keyword}%");
        });
    }

    public function filename(): string
    {
        return 'Nurses_' . date('Y_m_d_His');
    }
}