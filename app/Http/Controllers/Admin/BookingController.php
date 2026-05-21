<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Booking\BookingDataTable;
use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Display a listing of all bookings.
     */
    public function index(BookingDataTable $dataTable)
    {
        return $dataTable->render('admin.booking.index');
    }

    /**
     * Get data for datatable (AJAX)
     */
    public function data(BookingDataTable $dataTable)
    {
        return $dataTable->ajax();
    }

    /**
     * Display the specified booking with all details.
     */
    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'nurse.user',
            'careRequest.careType',
            'careRequest.bids.nurse.user', // <-- added
            'bid',
            'sessions',
            'paymentLogs',
            'parentBooking',
            'extensions',
        ])->findOrFail($id);

        return view('admin.booking.show', compact('booking'));
    }

    /**
     * Get sessions data for a booking (AJAX).
     */
    public function sessionsData($id)
    {
        $booking = Booking::findOrFail($id);
        $sessions = $booking->sessions()->orderBy('session_number')->get();

        $statusColors = [
            0 => 'warning',   // pending / upcoming
            1 => 'primary',   // normal / started
            2 => 'success',   // completed
            3 => 'danger',    // missed
            4 => 'danger',    // cancelled
        ];

        $data = $sessions->map(function ($session) use ($statusColors) {
            $color = $statusColors[$session->status] ?? 'dark';
            return [
                'session_number' => $session->session_number,
                'session_date' => $session->session_date ? $session->session_date->format('d M Y') : '—',
                'start_time' => $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('h:i A') : '—',
                'end_time' => $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('h:i A') : '—',
                'started_at' => $session->started_at ? $session->started_at->format('d M Y h:i A') : '—',
                'ended_at' => $session->ended_at ? $session->ended_at->format('d M Y h:i A') : '—',
                'status' => '<span class="badge badge-light-' . $color . ' border border-' . $color . ' fw-bold px-3 py-2">' . e($session->status_text) . '</span>',
                'otp_verified' => $session->otp_verified_at
                    ? '<span class="badge badge-light-success border border-success fw-bold px-2 py-1"><i class="ki-outline ki-check fs-6 me-1"></i>Verified</span>'
                    : '<span class="badge badge-light-secondary border border-secondary fw-bold px-2 py-1">Pending</span>',
                'nurse_notes' => e($session->nurse_notes ?? '—'),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Get payment logs for a booking (AJAX).
     */
    public function paymentLogsData($id)
    {
        $booking = Booking::findOrFail($id);
        $logs = $booking->paymentLogs()->orderByDesc('created_at')->get();

        $eventColors = [
            1 => 'primary',   // order created
            2 => 'success',   // payment success
            3 => 'danger',    // payment failed
            4 => 'warning',   // refund initiated
            5 => 'info',      // refund completed
            6 => 'primary',   // payout initiated
            7 => 'success',   // payout completed
            8 => 'danger',    // payout failed
            9 => 'secondary', // webhook
        ];

        $eventIcons = [
            1 => 'ki-outline ki-plus-square',
            2 => 'ki-outline ki-check-circle',
            3 => 'ki-outline ki-cross-circle',
            4 => 'ki-outline ki-arrow-circle-left',
            5 => 'ki-outline ki-verify',
            6 => 'ki-outline ki-send',
            7 => 'ki-outline ki-double-check',
            8 => 'ki-outline ki-disconnect',
            9 => 'ki-outline ki-notification',
        ];

        $data = $logs->map(function ($log) use ($eventColors, $eventIcons) {
            $color = $eventColors[$log->event_type] ?? 'dark';
            $icon = $eventIcons[$log->event_type] ?? 'ki-outline ki-information';

            return [
                'event' => '<div class="d-flex align-items-center gap-2">
                    <i class="' . $icon . ' fs-4 text-' . $color . '"></i>
                    <span class="badge badge-light-' . $color . ' border border-' . $color . ' fw-bold px-3 py-2">' . e($log->event_type_text) . '</span>
                </div>',
                'amount' => '<span class="fw-bold text-gray-900">₹' . number_format($log->amount, 2) . '</span>',
                'gateway' => '<span class="text-gray-700 fw-semibold">' . e($log->gateway_name ?? '—') . '</span>',
                'gateway_order_id' => '<span class="text-gray-600 fs-8 font-monospace">' . e($log->gateway_order_id ?? '—') . '</span>',
                'gateway_payment_id' => '<span class="text-gray-600 fs-8 font-monospace">' . e($log->gateway_payment_id ?? '—') . '</span>',
                'status' => '<span class="text-gray-700 fw-semibold">' . e($log->gateway_status ?? '—') . '</span>',
                'created_at' => $log->created_at ? $log->created_at->format('d M Y h:i A') : '—',
            ];
        });

        return response()->json(['data' => $data]);
    }
}
