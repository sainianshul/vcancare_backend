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

            $initial = mb_strtoupper(mb_substr($user->name, 0, 2));
            $contact = e($user->email ?? $user->phone ?? '—');
            $colors = [
                'bg-light-primary text-primary',
                'bg-light-success text-success',
                'bg-light-warning text-warning',
                'bg-light-danger text-danger',
                'bg-light-info text-info',
            ];
            $colorClass = $colors[ord($initial) % count($colors)];

            return '
                <div class="d-flex align-items-center gap-3">
                    <span class="symbol symbol-38px">
                        <span class="symbol-label rounded-2 fw-bold fs-6 ' . $colorClass . '">
                            ' . e($initial) . '
                        </span>
                    </span>
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

            $viewUrl = route('admin.nurses.index');
            $editUrl = route('admin.nurses.index');

            return '
                <div class="d-flex gap-1 justify-content-end">
                    <a href="' . $viewUrl . '" class="btn btn-sm btn-icon btn-light-primary w-30px h-30px" title="View">
                        <i class="ki-duotone ki-eye fs-5">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                    </a>
                    <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-light-warning w-30px h-30px" title="Edit">
                        <i class="ki-duotone ki-pencil fs-5">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </a>
                    <button type="button"
                        class="btn btn-sm btn-icon btn-light-danger w-30px h-30px btn-delete"
                        data-id="' . $user->id . '"
                        title="Delete">
                        <i class="ki-duotone ki-trash fs-5">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
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
                NurseProfile::STATUS_PENDING => ['Pending', 'warning'],
                NurseProfile::STATUS_UNDER_REVIEW => ['Under Review', 'info'],
                NurseProfile::STATUS_APPROVED => ['Approved', 'success'],
                NurseProfile::STATUS_REJECTED => ['Rejected', 'danger'],
                NurseProfile::STATUS_SUSPENDED => ['Suspended', 'dark'],
            ];

            if (!array_key_exists($status, $map)) {
                $status = NurseProfile::STATUS_PENDING;
            }

            return '<span class="badge badge-light-' . $map[$status][1] . ' fw-bold px-3 py-2">'
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