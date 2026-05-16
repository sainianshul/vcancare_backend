<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class BlockedPatientDataTable extends DataTable
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
                return '
                    <span class="badge badge-light-' . $user->status_color . ' border border-' . $user->status_color . ' fw-bold px-3 py-2">
                        <i class="ki-outline ' . $user->status_icon . ' fs-6 text-' . $user->status_color . ' me-1"></i>
                        ' . e($user->status_name) . '
                    </span>
                ';
            })

            // ── Blocked Reason ───────────────────────────────────────────
            ->addColumn('blocked_reason', function (User $user) {
                return $user->blocked_reason ? e($user->blocked_reason) : '<span class="text-muted">N/A</span>';
            })

            // ── Blocked At ───────────────────────────────────────────
            ->addColumn('blocked_at', function (User $user) {
                // If blocked_at doesn't exist, we fall back to N/A
                $blockedAt = $user->blocked_at ?? null;

                if (!$blockedAt) {
                    return '<span class="text-muted">N/A</span>';
                }

                // If it's not a carbon instance, maybe parse it
                if (!($blockedAt instanceof \Carbon\Carbon)) {
                    $blockedAt = \Carbon\Carbon::parse($blockedAt);
                }

                return '
                    <div class="fw-semibold text-gray-800">
                        ' . $blockedAt->format('d M Y') . '
                    </div>
                    <div class="text-muted fs-7">
                        ' . $blockedAt->diffForHumans() . '
                    </div>
                ';
            })

            // ── Actions ──────────────────────────────────────────────
            ->addColumn('actions', function (User $user) {

                $viewUrl = route('admin.patients.show', $user->id);

                return '
                    <div class="d-flex gap-1 justify-content-end">

                        <button
                            type="button"
                            class="btn btn-sm btn-light-success border border-success fw-bold me-2 btn-unblock h-30px d-inline-flex align-items-center px-3 py-0"
                            data-id="' . $user->id . '"
                            title="Unblock">
                            <i class="ki-outline ki-check-circle fs-5 me-1"></i>Unblock
                        </button>

                        <a href="' . $viewUrl . '"
                            class="btn btn-sm btn-icon btn-light-primary border border-primary w-30px h-30px me-1"
                            title="View">
                            <i class="ki-outline ki-eye fs-5"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-sm btn-icon btn-light-danger border border-danger w-30px h-30px btn-delete"
                            data-id="' . $user->id . '"
                            title="Delete">
                            <i class="ki-outline ki-trash fs-5"></i>
                        </button>

                    </div>
                ';
            })

            ->rawColumns([
                'name',
                'status',
                'blocked_reason',
                'blocked_at',
                'actions',
            ]);
    }

    public function query(User $model)
    {
        // Only return blocked users
        $query = $model->newQuery()
            ->where('role', User::ROLE_USER)
            ->where('status', User::STATUS_BLOCKED)
            ->select('users.*');

        return $query;
    }

    public function filename(): string
    {
        return 'Blocked_Users_' . date('Y_m_d_His');
    }
}
