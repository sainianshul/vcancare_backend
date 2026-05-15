<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class PatientDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)

            // ── User Info ─────────────────────────────────────────────
            ->addColumn('name', function (User $user) {

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

                $avatar = '
                    <span class="symbol symbol-38px">
                        <span class="symbol-label rounded-2 fw-bold fs-6 ' . $colorClass . '">
                            ' . e($initial) . '
                        </span>
                    </span>
                ';

                return '
                    <div class="d-flex align-items-center gap-3">
                        ' . $avatar . '
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-semibold fs-6 lh-1 mb-1">
                                ' . e($user->name) . '
                            </span>
                            <span class="text-muted fw-normal fs-7">
                                ' . $contact . '
                            </span>
                        </div>
                    </div>
                ';
            })

            // ── Status ───────────────────────────────────────────────
            ->addColumn('status', function (User $user) {

                $colorMap = [
                    User::STATUS_ACTIVE => 'success',
                    User::STATUS_INACTIVE => 'secondary',
                    User::STATUS_BLOCKED => 'danger',
                ];

                $statusList = User::getStatusList();

                $status = $user->status;

                $label = $statusList[$status] ?? 'Unknown';

                $color = $colorMap[$status] ?? 'secondary';

                return '
                    <span class="badge badge-light-' . $color . ' fw-bold px-3 py-2">
                        ' . e($label) . '
                    </span>
                ';
            })

            // ── Last Login ───────────────────────────────────────────
            ->addColumn('last_login_at', function (User $user) {

                if (!$user->last_login_at) {
                    return '<span class="text-muted">Never</span>';
                }

                return '
                    <div class="fw-semibold text-gray-800">
                        ' . $user->last_login_at->format('d M Y') . '
                    </div>

                    <div class="text-muted fs-7">
                        ' . $user->last_login_at->diffForHumans() . '
                    </div>
                ';
            })

            // ── Joined Date ──────────────────────────────────────────
            ->editColumn('created_at', function (User $user) {

                return '
                    <div class="fw-semibold text-gray-800">
                        ' . $user->created_at->format('d M Y') . '
                    </div>

                    <div class="text-muted fs-7">
                        ' . $user->created_at->diffForHumans() . '
                    </div>
                ';
            })

            // ── Actions ──────────────────────────────────────────────
            ->addColumn('actions', function (User $user) {

                $viewUrl = route('admin.patients.index', $user->id);

                $editUrl = route('admin.patients.index', $user->id);

                return '
                    <div class="d-flex gap-1 justify-content-end">

                        <a href="' . $viewUrl . '"
                            class="btn btn-sm btn-icon btn-light-primary w-30px h-30px"
                            title="View">

                            <i class="ki-duotone ki-eye fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </a>

                        <a href="' . $editUrl . '"
                            class="btn btn-sm btn-icon btn-light-warning w-30px h-30px"
                            title="Edit">

                            <i class="ki-duotone ki-pencil fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-sm btn-icon btn-light-danger w-30px h-30px btn-delete"
                            data-id="' . $user->id . '"
                            title="Delete">

                            <i class="ki-duotone ki-trash fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </button>

                    </div>
                ';
            })

            ->rawColumns([
                'name',
                'status',
                'last_login_at',
                'created_at',
                'actions',
            ]);
    }

    public function query(User $model)
    {
        $query = $model->newQuery()
            ->where('role', User::ROLE_USER)
            ->select('users.*');

        // Filter by status
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        return $query;
    }

    public function filename(): string
    {
        return 'Users_' . date('Y_m_d_His');
    }
}