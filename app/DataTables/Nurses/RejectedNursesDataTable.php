<?php

namespace App\DataTables\Nurses;

use App\Models\User;
use App\Models\NurseProfile;

class RejectedNursesDataTable extends BaseNursesDataTable
{
    protected array $profileStatuses = [
        NurseProfile::STATUS_REJECTED, // 3
    ];

    // User status koi bhi ho — rejected me sab dikhao

    public function dataTable($query)
    {
        $dt = datatables()->eloquent($query);

        $dt = $this->nurseColumn($dt);
        $dt = $this->locationColumn($dt);

        $dt->addColumn('rejection_reason', function (User $user) {
            $reason = $user->nurseProfile?->rejection_reason;

            return $reason
                ? '<span class="text-gray-700 fs-7">' . e($reason) . '</span>'
                : '<span class="text-muted fs-7">—</span>';
        });

        $dt->addColumn('rejected_at', function (User $user) {
            $date = $user->nurseProfile?->rejected_at;
            if (!$date)
                return '<span class="text-muted">—</span>';

            return '
                <div class="fw-semibold text-gray-800">' . $date->format('d M Y') . '</div>
                <div class="text-muted fs-7">' . $date->diffForHumans() . '</div>
            ';
        });

        $dt = $this->joinedColumn($dt);
        $dt = $this->actionsColumn($dt);
        $dt = $this->filterByName($dt);

        return $dt->rawColumns(['nurse', 'location', 'rejection_reason', 'rejected_at', 'created_at', 'actions']);
    }
}