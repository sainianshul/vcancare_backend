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
                    <span class="badge badge-light-secondary fw-bold px-2 py-1 fs-8">
                        Guest
                    </span>
                ';

                if ($role === User::ROLE_ADMIN) {

                    $roleBadge = '
                        <span class="badge badge-light-danger fw-bold px-2 py-1 fs-8">
                            Admin
                        </span>
                    ';
                } elseif ($role === User::ROLE_USER) {

                    $roleBadge = '
                        <span class="badge badge-light-primary fw-bold px-2 py-1 fs-8">
                            Patient
                        </span>
                    ';
                } elseif ($role === User::ROLE_NURSE) {

                    $roleBadge = '
                        <span class="badge badge-light-success fw-bold px-2 py-1 fs-8">
                            Nurse
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
                        <span class="badge badge-light-success fw-bold px-3 py-2">
                            Success
                        </span>
                    '

                    : '
                        <span class="badge badge-light-danger fw-bold px-3 py-2">
                            Failed
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

            // ── Search Only By User Name ──────────────────────────
            ->filterColumn('user', function ($query, $keyword) {

                $query->whereHas('user', function ($q) use ($keyword) {

                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })

            // ── Actions ───────────────────────────────────────────
            ->addColumn('actions', function (LoginHistory $history) {

                return '
                    <div class="d-flex gap-1 justify-content-end">

                        <a href="' . route('admin.login-history.index') . '"
                            class="btn btn-sm btn-icon btn-light-primary w-30px h-30px"
                            title="View">

                            <i class="ki-duotone ki-eye fs-5">

                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>

                            </i>

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

        // ── Search Only By User Name ─────────────────────────────
        if ($search = request('search.value')) {

            $query->whereHas('user', function ($q) use ($search) {

                $q->where('name', 'LIKE', "%{$search}%");
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