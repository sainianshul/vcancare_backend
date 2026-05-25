<?php

namespace App\DataTables\Support;

use App\Models\SupportTicket;
use Yajra\DataTables\Services\DataTable;

class TicketDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)

            // ── Reference ID ─────────────────────────────────────────
            ->editColumn('reference_id', function (SupportTicket $ticket) {
                return '<span class="fw-bold text-gray-800 text-nowrap">' . e($ticket->reference_id) . '</span>';
            })

            // ── User Info ────────────────────────────────────────────
            ->filterColumn('user', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%")
                      ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('user', function (SupportTicket $ticket) {
                $user = $ticket->user;
                if (!$user) return '<span class="text-muted">Unknown</span>';

                $avatar = '<div class="symbol symbol-38px symbol-circle">' . $user->avatar_html . '</div>';

                return '
                    <div class="d-flex align-items-center gap-3">
                        ' . $avatar . '
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-semibold fs-6 lh-1 mb-1">' . e($user->name) . '</span>
                            <span class="text-muted fw-normal fs-7">' . e($user->email) . '</span>
                        </div>
                    </div>
                ';
            })

            // ── Subject & Category ───────────────────────────────────
            ->addColumn('subject_category', function (SupportTicket $ticket) {
                return '
                    <div class="d-flex flex-column gap-1">
                        <div class="fw-bold text-gray-900 text-truncate" style="max-width: 250px;" title="' . e($ticket->subject) . '">' . e($ticket->subject) . '</div>
                        <div>
                            <span class="badge badge-light-primary border border-primary text-uppercase fw-bold fs-9 px-2 py-1"><i class="ki-outline ki-category fs-8 text-primary me-1"></i> ' . e($ticket->category ?? 'General') . '</span>
                        </div>
                    </div>
                ';
            })

            // ── Priority ─────────────────────────────────────────────
            ->addColumn('priority', function (SupportTicket $ticket) {
                $priorityMap = [
                    SupportTicket::PRIORITY_LOW => ['class' => 'text-success', 'icon' => 'ki-arrow-down'],
                    SupportTicket::PRIORITY_MEDIUM => ['class' => 'text-warning', 'icon' => 'ki-minus'],
                    SupportTicket::PRIORITY_HIGH => ['class' => 'text-danger', 'icon' => 'ki-arrow-up'],
                ];
                
                $prio = $priorityMap[$ticket->priority] ?? $priorityMap[SupportTicket::PRIORITY_LOW];
                
                return '<div class="d-flex align-items-center"><i class="ki-outline ' . $prio['icon'] . ' fs-3 ' . $prio['class'] . ' me-2"></i> <span class="fw-bold ' . $prio['class'] . '">' . $ticket->priority_text . '</span></div>';
            })

            // ── Status ──────────────────────────────────────────────
            ->addColumn('status', function (SupportTicket $ticket) {
                return '
                    <span class="badge badge-light-' . $ticket->status_color . ' border border-' . $ticket->status_color . ' fw-bold px-3 py-2">
                        ' . e($ticket->status_text) . '
                    </span>
                ';
            })

            // ── Created At ──────────────────────────────────────────
            ->editColumn('created_at', function (SupportTicket $ticket) {
                return '
                    <div class="fw-semibold text-gray-800">' . $ticket->created_at->format('d M Y') . '</div>
                    <div class="text-muted fs-7">' . $ticket->created_at->format('h:i A') . '</div>
                ';
            })

            // ── Actions ─────────────────────────────────────────────
            ->addColumn('actions', function (SupportTicket $ticket) {
                $viewUrl = route('admin.support.show', $ticket->id);

                return '
                    <div class="d-flex gap-1 justify-content-end">
                        <a href="' . $viewUrl . '"
                            class="btn btn-sm btn-icon btn-light-primary border border-primary w-30px h-30px"
                            title="View">
                            <i class="ki-outline ki-eye fs-5"></i>
                        </a>
                    </div>
                ';
            })

            ->rawColumns([
                'reference_id',
                'user',
                'subject_category',
                'priority',
                'status',
                'created_at',
                'actions',
            ]);
    }

    public function query(SupportTicket $model)
    {
        $query = $model->newQuery()->with(['user'])->select('support_tickets.*');

        // Filter by user_id
        if (request()->filled('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // Filter by status
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Filter by category
        if (request()->filled('category')) {
            $query->where('category', request('category'));
        }

        // Filter by date
        if (request()->filled('date')) {
            $query->whereDate('support_tickets.created_at', request('date'));
        }

        return $query;
    }

    public function filename(): string
    {
        return 'SupportTickets_' . date('Y_m_d_His');
    }
}

