<?php

namespace App\DataTables\Nurses;

use App\Models\NurseProfile;

class UnderReviewNurseDataTable extends BaseNursesDataTable
{
    protected array $profileStatuses = [
        NurseProfile::STATUS_UNDER_REVIEW, // 1
    ];

    protected string $userStatus = 'active';

    public function dataTable($query)
    {
        $dt = datatables()->eloquent($query);

        $dt = $this->nurseColumn($dt);
        $dt = $this->locationColumn($dt);
        $dt = $this->profileStatusColumn($dt);
        $dt = $this->joinedColumn($dt);
        $dt = $this->actionsColumn($dt);
        $dt = $this->filterByName($dt);

        return $dt->rawColumns(['nurse', 'location', 'profile_status', 'created_at', 'actions']);
    }
}