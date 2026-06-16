<?php

namespace App\DataTables\Booking;

use App\Models\Booking;
use Yajra\DataTables\Services\DataTable;

class BookingDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)

            // ── Reference ID ─────────────────────────────────────────
            ->editColumn('reference_id', function (Booking $booking) {
                return '<span class="fw-bold text-gray-800 text-nowrap">' . e($booking->reference_id) . '</span>';
            })

            // ── User Info ────────────────────────────────────────────
            ->filterColumn('user', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('user', function (Booking $booking) {
                $user = $booking->user;
                if (!$user)
                    return '<span class="text-muted">Unknown</span>';

                return '
                    <div class="d-flex flex-column">
                        <span class="text-gray-800 fw-semibold fs-6 lh-1 mb-1">' . e($user->name) . '</span>
                        <span class="text-muted fw-normal fs-7">ID: ' . e($user->id) . '</span>
                    </div>
                ';
            })

            // ── Nurse Info ───────────────────────────────────────────
            ->addColumn('nurse', function (Booking $booking) {
                $nurse = $booking->nurse;
                if (!$nurse || !$nurse->user)
                    return '<span class="text-muted">Unassigned</span>';

                $user = $nurse->user;

                return '
                    <div class="d-flex flex-column">
                        <span class="text-gray-800 fw-semibold fs-6 lh-1 mb-1">' . e($user->name) . '</span>
                        <span class="text-muted fw-normal fs-7">ID: ' . e($nurse->id) . '</span>
                    </div>
                ';
            })

            // ── Amount ──────────────────────────────────────────────
            ->addColumn('amount', function (Booking $booking) {
                return '
                    <div class="fw-bold text-gray-900">₹' . number_format($booking->total_amount, 2) . '</div>
                    <div class="text-muted fs-8">Nurse: ₹' . number_format($booking->nurse_amount, 2) . '</div>
                ';
            })

            // ── Status ──────────────────────────────────────────────
            ->addColumn('status', function (Booking $booking) {
                $color = $booking->status_color;

                return '
                    <span class="badge badge-light-' . $color . ' border border-' . $color . ' fw-bold px-3 py-2">
                        ' . e($booking->status_text) . '
                    </span>
                ';
            })

            // ── Payment Status ──────────────────────────────────────
            ->addColumn('payment_status', function (Booking $booking) {
                $color = $booking->payment_status_color;

                return '
                    <span class="badge badge-light-' . $color . ' border border-' . $color . ' fw-bold px-3 py-2">
                        ' . e($booking->payment_status_text) . '
                    </span>
                ';
            })

            // ── Sessions ────────────────────────────────────────────
            ->addColumn('sessions', function (Booking $booking) {
                $pct = $booking->total_sessions > 0
                    ? round(($booking->completed_sessions / $booking->total_sessions) * 100)
                    : 0;
                return '
                    <div class="fw-semibold text-gray-800">' . $booking->completed_sessions . '/' . $booking->total_sessions . '</div>
                    <div class="progress h-6px w-75px mt-1">
                        <div class="progress-bar bg-success" role="progressbar" style="width: ' . $pct . '%"></div>
                    </div>
                ';
            })

            // ── Created At ──────────────────────────────────────────
            ->editColumn('created_at', function (Booking $booking) {
                return '
                    <div class="fw-semibold text-gray-800">' . $booking->created_at->format('d M Y') . '</div>
                    <div class="text-muted fs-7">' . $booking->created_at->diffForHumans() . '</div>
                ';
            })

            // ── Actions ─────────────────────────────────────────────
            ->addColumn('actions', function (Booking $booking) {
                $viewUrl = route('admin.bookings.show', $booking->id);

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
                'nurse',
                'amount',
                'status',
                'payment_status',
                'sessions',
                'created_at',
                'actions',
            ]);
    }

    public function query(Booking $model)
    {
        $query = $model->newQuery()->with(['user', 'nurse.user'])->select('bookings.*');

        // Filter by user_id
        if (request()->filled('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // Filter by status
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Filter by payment status
        if (request()->filled('payment_status')) {
            $query->where('payment_status', request('payment_status'));
        }

        // Filter by date
        if (request()->filled('date')) {
            $query->whereDate('bookings.created_at', request('date'));
        }

        return $query;
    }
}

