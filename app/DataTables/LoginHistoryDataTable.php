<?php

namespace App\DataTables;

use App\Models\LoginHistory;
use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class LoginHistoryDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)

            // ── User ───────────────────────────────────────────────
            ->addColumn('user', function (LoginHistory $history) {

                $user = $history->user;

                $name = trim((string) ($user?->name));

                $phone = trim((string) ($user?->phone));

                $email = trim((string) ($user?->email));

                // ── Safe Fallbacks ────────────────────────────────
                if ($name === '') {

                    $name = 'Unknown User';
                }

                // Phone first (system is phone based)
                $contact = $phone !== ''
                    ? $phone
                    : ($email !== '' ? $email : 'No contact available');

                // ── Role Badge ────────────────────────────────────
                $role = (int) ($user?->role);

                $roleBadge = '
                    <span class="badge badge-light-secondary border border-secondary fw-bold px-2 py-1 fs-8">
                        <i class="ki-outline ki-user fs-7 text-secondary me-1"></i> Guest
                    </span>
                ';

                if ($role === User::ROLE_ADMIN) {

                    $roleBadge = '
                        <span class="badge badge-light-danger border border-danger fw-bold px-2 py-1 fs-8">
                            <i class="ki-outline ki-shield-tick fs-7 text-danger me-1"></i> Admin
                        </span>
                    ';
                } elseif ($role === User::ROLE_USER) {

                    $roleBadge = '
                        <span class="badge badge-light-warning border border-warning fw-bold px-2 py-1 fs-8">
                            <i class="ki-outline ki-profile-user fs-7 text-warning me-1"></i> Patient
                        </span>
                    ';
                } elseif ($role === User::ROLE_NURSE) {

                    $roleBadge = '
                        <span class="badge badge-light-info border border-info fw-bold px-2 py-1 fs-8">
                            <i class="ki-outline ki-heart fs-7 text-info me-1"></i> Nurse
                        </span>
                    ';
                }

                return '
                    <div class="d-flex flex-column">

                        <div class="d-flex align-items-center gap-2 mb-1">

                            <span class="text-gray-800 fw-semibold fs-6">
                                ' . e($name) . '
                            </span>

                            ' . $roleBadge . '

                        </div>

                        <span class="text-muted fw-normal fs-7">
                            ' . e($contact) . '
                        </span>

                    </div>
                ';
            })

            // ── Device ─────────────────────────────────────────────
            ->addColumn('device', function (LoginHistory $history) {

                $device = trim((string) $history->device_type);

                $platform = trim((string) $history->platform);

                // Avoid ugly "Unknown Device"
                if ($device === '') {

                    $device = '—';
                }

                if ($platform === '') {

                    $platform = '—';
                }

                return '
                    <div class="d-flex flex-column">

                        <span class="fw-semibold text-gray-800">
                            ' . e($device) . '
                        </span>

                        <span class="text-muted fs-7">
                            ' . e($platform) . '
                        </span>

                    </div>
                ';
            })

            // ── IP Address ─────────────────────────────────────────
            ->addColumn('ip', function (LoginHistory $history) {

                $ip = trim((string) $history->ip_address);

                if ($ip === '') {

                    $ip = '—';
                }

                return '
                    <div class="d-flex flex-column">

                        <span class="fw-bold text-gray-800 fs-7">
                            ' . e($ip) . '
                        </span>

                        <span class="text-muted fs-8">
                            Login IP
                        </span>

                    </div>
                ';
            })

            // ── Status ─────────────────────────────────────────────
            ->addColumn('status_badge', function (LoginHistory $history) {

                return (int) $history->status === 1

                    ? '
                        <span class="badge badge-light-success border border-success fw-bold px-3 py-2">
                            <i class="ki-outline ki-check-circle fs-6 text-success me-1"></i> Success
                        </span>
                    '

                    : '
                        <span class="badge badge-light-danger border border-danger fw-bold px-3 py-2">
                            <i class="ki-outline ki-cross-circle fs-6 text-danger me-1"></i> Failed
                        </span>
                    ';
            })

            // ── Login Time ─────────────────────────────────────────
            ->editColumn('logged_in_at', function (LoginHistory $history) {

                if (!$history->logged_in_at) {

                    return '
                        <span class="text-muted">
                            —
                        </span>
                    ';
                }

                return '
                    <div class="fw-semibold text-gray-800">
                        ' . $history->logged_in_at->format('d M Y') . '
                    </div>

                    <div class="text-muted fs-7">
                        ' . $history->logged_in_at->format('h:i A') . '
                    </div>
                ';
            })

            // ── Search By User Name or Phone ──────────────────────────
            ->filterColumn('user', function ($query, $keyword) {

                $query->whereHas('user', function ($q) use ($keyword) {

                    $q->where('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('phone', 'LIKE', "%{$keyword}%");
                });
            })

            // ── Actions ───────────────────────────────────────────
            ->addColumn('actions', function (LoginHistory $history) {

                return '
                    <div class="d-flex gap-1 justify-content-end">

                        <a href="' . route('admin.login-history.show', $history->id) . '"
                            class="btn btn-sm btn-icon btn-light-primary border border-primary w-30px h-30px"
                            title="View">
                            <i class="ki-outline ki-eye fs-5"></i>
                        </a>

                    </div>
                ';
            })

            ->rawColumns([
                'user',
                'device',
                'ip',
                'status_badge',
                'logged_in_at',
                'actions',
            ]);
    }

    public function query(LoginHistory $model)
    {
        $query = $model->newQuery()
            ->with('user')
            ->select('login_histories.*');

        // ── Search By User Name or Phone ─────────────────────────────
        if ($search = request('search.value')) {

            $query->whereHas('user', function ($q) use ($search) {

                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // ── Status Filter ────────────────────────────────────────
        if (request()->filled('status')) {

            $query->where('status', request('status'));
        }

        return $query;
    }

    public function filename(): string
    {
        return 'Login_History_' . date('Y_m_d_His');
    }
}