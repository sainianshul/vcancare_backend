<?php

namespace App\DataTables\Nurses;

use App\Models\User;
use App\Models\NurseProfile;

class AllNursesDataTable extends BaseNursesDataTable
{
    // No profileStatuses filter — sab aayenge

    public function dataTable($query)
    {
        $dt = datatables()->eloquent($query);

        $dt = $this->nurseColumn($dt);
        $dt = $this->locationColumn($dt);

        // Bookings
        $dt->addColumn('bookings', function (User $user) {
            $completed = $user->nurseProfile?->total_bookings_completed ?? 0;
            $cancelled = $user->nurseProfile?->total_bookings_cancelled ?? 0;
            $total = $completed + $cancelled;

            return '
                <div class="d-flex flex-column">
                    <div class="fw-bold text-gray-800 fs-6">' . $completed . ' / ' . $total . '</div>
                    <span class="text-muted fs-7">Completed / Total</span>
                </div>
            ';
        });

        $dt = $this->profileStatusColumn($dt);
        $dt = $this->joinedColumn($dt);
        $dt = $this->actionsColumn($dt);
        $dt = $this->filterByName($dt);

        return $dt->rawColumns(['nurse', 'location', 'bookings', 'profile_status', 'created_at', 'actions']);
    }

    public function query(\App\Models\User $model)
    {
        $query = parent::query($model);

        // Status dropdown filter — sirf All page pe
        if (request()->filled('profile_status')) {
            $query->whereHas('nurseProfile', function ($q) {
                $q->where('status', request('profile_status'));
            });
        }

        return $query;
    }
}