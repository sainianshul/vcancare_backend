<?php

namespace App\DataTables\Nurses;

use App\Models\User;
use App\Models\NurseProfile;

class ApprovedNursesDataTable extends BaseNursesDataTable
{
    protected array $profileStatuses = [
        NurseProfile::STATUS_APPROVED,
    ];

    protected $userStatus = User::STATUS_ACTIVE;

    public function dataTable($query)
    {
        $dt = datatables()->eloquent($query);

        $dt = $this->nurseColumn($dt);
        $dt = $this->locationColumn($dt);

        $dt->addColumn('rating', function (User $user) {
            $rating = number_format($user->nurseProfile?->avg_rating ?? 0, 1);
            $reviews = $user->nurseProfile?->total_reviews ?? 0;

            return '
                <div class="d-flex flex-column">
                    <span class="fw-bold text-gray-800">⭐ ' . $rating . '</span>
                    <span class="text-muted fs-7">' . $reviews . ' reviews</span>
                </div>
            ';
        });

        $dt->addColumn('bookings', function (User $user) {
            $completed = $user->nurseProfile?->total_bookings_completed ?? 0;
            $cancelled = $user->nurseProfile?->total_bookings_cancelled ?? 0;

            return '
                <div class="d-flex flex-column">
                    <div class="fw-bold text-gray-800 fs-6">' . $completed . ' / ' . ($completed + $cancelled) . '</div>
                    <span class="text-muted fs-7">Completed / Total</span>
                </div>
            ';
        });

        $dt->addColumn('approved_at', function (User $user) {
            $date = $user->nurseProfile?->approved_at;
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

        return $dt->rawColumns(['nurse', 'location', 'rating', 'bookings', 'approved_at', 'created_at', 'actions']);
    }
}