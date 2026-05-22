<?php

namespace App\DataTables\Bid;

use App\Models\RequestBid;
use Yajra\DataTables\Services\DataTable;

class BidDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)

            // ── ID ───────────────────────────────────────────────────
            ->addColumn('id', function (RequestBid $bid) {
                return '<span class="fw-bold text-gray-800">#' . $bid->id . '</span>';
            })

            // ── Care Request Info ────────────────────────────────────
            ->addColumn('care_request', function (RequestBid $bid) {
                $careRequest = $bid->careRequest;
                if (!$careRequest) return '<span class="text-muted">Unknown</span>';
                
                $title = $careRequest->title ?? 'Request #' . $careRequest->id;
                $url = route('admin.requests.show', $careRequest->id);
                
                return '
                    <div class="d-flex flex-column">
                        <a href="' . $url . '" class="text-gray-800 text-hover-primary fw-semibold fs-6 lh-1 mb-1">' . e($title) . '</a>
                        <span class="text-muted fw-normal fs-7">Req ID: ' . e($careRequest->id) . '</span>
                    </div>
                ';
            })

            // ── Nurse Info ───────────────────────────────────────────
            ->addColumn('nurse', function (RequestBid $bid) {
                $nurse = $bid->nurse;
                if (!$nurse || !$nurse->user) return '<span class="text-muted">Unknown</span>';

                $user = $nurse->user;
                $initial = mb_strtoupper(mb_substr($user->name, 0, 2));
                $colors = ['bg-light-info text-info', 'bg-light-primary text-primary', 'bg-light-success text-success', 'bg-light-warning text-warning', 'bg-light-danger text-danger'];
                $colorClass = $colors[ord($initial) % count($colors)];

                $url = route('admin.nurses.show', $user->id);

                $avatar = '';
                if ($user->profile_photo) {
                    $avatar = '<div class="symbol symbol-38px symbol-circle"><img src="' . \Illuminate\Support\Facades\Storage::url($user->profile_photo) . '" class="object-fit-cover" alt="Pic"></div>';
                } else {
                    $avatar = '<span class="symbol symbol-38px symbol-circle"><span class="symbol-label fw-bold fs-6 ' . $colorClass . '">' . e($initial) . '</span></span>';
                }

                return '
                    <div class="d-flex align-items-center gap-3">
                        ' . $avatar . '
                        <div class="d-flex flex-column">
                            <a href="' . $url . '" class="text-gray-800 text-hover-primary fw-semibold fs-6 lh-1 mb-1">' . e($user->name) . '</a>
                            <span class="text-muted fw-normal fs-7">ID: ' . e($nurse->id) . '</span>
                        </div>
                    </div>
                ';
            })

            // ── Amount ──────────────────────────────────────────────
            ->addColumn('amount', function (RequestBid $bid) {
                return '
                    <div class="fw-bold text-gray-900">₹' . number_format($bid->total_amount, 2) . '</div>
                    <div class="text-muted fs-8">Nurse: ₹' . number_format($bid->nurse_amount, 2) . '</div>
                ';
            })

            // ── Status ──────────────────────────────────────────────
            ->addColumn('status', function (RequestBid $bid) {
                $statusColors = [
                    RequestBid::STATUS_PENDING => 'warning',
                    RequestBid::STATUS_SELECTED => 'success',
                    RequestBid::STATUS_REJECTED => 'danger',
                    RequestBid::STATUS_EXPIRED => 'secondary',
                    RequestBid::STATUS_CANCELLED => 'dark',
                ];
                $color = $statusColors[$bid->status] ?? 'dark';

                return '
                    <span class="badge badge-light-' . $color . ' border border-' . $color . ' fw-bold px-3 py-2">
                        ' . e($bid->status_text) . '
                    </span>
                ';
            })

            // ── Created At ──────────────────────────────────────────
            ->editColumn('created_at', function (RequestBid $bid) {
                return '
                    <div class="fw-semibold text-gray-800">' . $bid->created_at->format('d M Y') . '</div>
                    <div class="text-muted fs-7">' . $bid->created_at->format('h:i A') . '</div>
                ';
            })

            // ── Actions ─────────────────────────────────────────────
            ->addColumn('actions', function (RequestBid $bid) {
                $viewUrl = route('admin.bids.show', $bid->id);

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
                'id',
                'care_request',
                'nurse',
                'amount',
                'status',
                'created_at',
                'actions',
            ]);
    }

    public function query(RequestBid $model)
    {
        $query = $model->newQuery()->with(['careRequest', 'nurse.user'])->select('request_bids.*');

        // Filter by status
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Filter by date
        if (request()->filled('date')) {
            $query->whereDate('request_bids.created_at', request('date'));
        }

        // Filter today
        if (request()->filled('today') && request('today') == true) {
            $query->whereDate('request_bids.created_at', today());
        }

        return $query;
    }

    public function filename(): string
    {
        return 'Bids_' . date('Y_m_d_His');
    }
}
