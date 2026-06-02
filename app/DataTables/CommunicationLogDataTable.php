<?php

namespace App\DataTables;

use App\Models\CommunicationLog;
use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class CommunicationLogDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)

            // ── User ───────────────────────────────────────────────
            ->addColumn('user', function (CommunicationLog $log) {
                $notifiable = $log->notifiable;
                $user = null;
                
                if ($notifiable instanceof User) {
                    $user = $notifiable;
                }

                if (!$user) {
                    return '<span class="text-muted">System / Deleted User</span><br><span class="fs-8 text-muted">' . e($log->destination) . '</span>';
                }

                $name = trim((string) ($user->name));
                $phone = trim((string) ($user->phone));
                $email = trim((string) ($user->email));

                if ($name === '') {
                    $name = 'Unknown User';
                }

                $contact = $phone !== '' ? $phone : ($email !== '' ? $email : 'No contact available');
                $role = (int) ($user->role);

                $roleBadge = '<span class="badge badge-light-secondary border border-secondary fw-bold px-2 py-1 fs-8">Guest</span>';
                if ($role === User::ROLE_ADMIN) {
                    $roleBadge = '<span class="badge badge-light-danger border border-danger fw-bold px-2 py-1 fs-8">Admin</span>';
                } elseif ($role === User::ROLE_USER) {
                    $roleBadge = '<span class="badge badge-light-warning border border-warning fw-bold px-2 py-1 fs-8">Patient</span>';
                } elseif ($role === User::ROLE_NURSE) {
                    $roleBadge = '<span class="badge badge-light-info border border-info fw-bold px-2 py-1 fs-8">Nurse</span>';
                }

                return '
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="text-gray-800 fw-semibold fs-6">' . e($name) . '</span>
                            ' . $roleBadge . '
                        </div>
                        <span class="text-muted fw-normal fs-7">' . e($contact) . '</span>
                        <span class="text-muted fw-normal fs-8">' . e($log->destination) . '</span>
                    </div>
                ';
            })

            // ── Channel & Notification Type ──────────────────────────
            ->addColumn('notification', function (CommunicationLog $log) {
                $channel = strtoupper($log->channel);
                $typeParts = explode('\\', $log->type);
                $type = end($typeParts); // Get class name only

                $channelColor = 'primary';
                if ($log->channel === 'twilio' || $log->channel === 'sms') $channelColor = 'info';
                if ($log->channel === 'mail') $channelColor = 'warning';
                if ($log->channel === 'fcm' || $log->channel === 'push') $channelColor = 'success';

                return '
                    <div class="d-flex flex-column">
                        <div class="mb-1">
                            <span class="badge badge-light-' . $channelColor . ' border border-' . $channelColor . ' fw-bold px-2 py-1">' . $channel . '</span>
                        </div>
                        <span class="fw-semibold text-gray-800 fs-7">' . e($type) . '</span>
                    </div>
                ';
            })

            // ── Content ───────────────────────────────────────────
            ->addColumn('content', function (CommunicationLog $log) {
                $content = $log->content;
                if (!$content) return '—';

                // Truncate to 50 characters
                $truncated = mb_strimwidth($content, 0, 50, '...');
                return '
                    <span class="text-gray-700 fs-7" title="' . e($content) . '">' . e($truncated) . '</span>
                ';
            })

            // ── Status ─────────────────────────────────────────────
            ->addColumn('status_badge', function (CommunicationLog $log) {
                return strtolower($log->status) === 'success'
                    ? '<span class="badge badge-light-success border border-success fw-bold px-3 py-2"><i class="ki-outline ki-check-circle fs-6 text-success me-1"></i> Sent</span>'
                    : '<span class="badge badge-light-danger border border-danger fw-bold px-3 py-2"><i class="ki-outline ki-cross-circle fs-6 text-danger me-1"></i> Failed</span>';
            })

            // ── Created At ─────────────────────────────────────────
            ->editColumn('created_at', function (CommunicationLog $log) {
                if (!$log->created_at) {
                    return '<span class="text-muted">—</span>';
                }

                return '
                    <div class="fw-semibold text-gray-800">' . $log->created_at->format('d M Y') . '</div>
                    <div class="text-muted fs-7">' . $log->created_at->format('h:i A') . '</div>
                ';
            })

            ->filterColumn('notification', function ($query, $keyword) {
                $query->where('type', 'LIKE', "%{$keyword}%")
                      ->orWhere('channel', 'LIKE', "%{$keyword}%");
            })

            ->rawColumns([
                'user',
                'notification',
                'content',
                'status_badge',
                'created_at',
            ]);
    }

    public function query(CommunicationLog $model)
    {
        $query = $model->newQuery()
            ->with('notifiable')
            ->orderBy('created_at', 'desc');

        if ($search = request('search.value')) {
            $query->where('type', 'LIKE', "%{$search}%")
              ->orWhere('content', 'LIKE', "%{$search}%")
              ->orWhere('destination', 'LIKE', "%{$search}%");
        }

        if (request()->filled('channel')) {
            $query->where('channel', request('channel'));
        }
        
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        return $query;
    }

    public function filename(): string
    {
        return 'Communication_Logs_' . date('Y_m_d_His');
    }
}
