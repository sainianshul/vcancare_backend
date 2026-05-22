@extends('admin.layouts.app')

@section('title', 'Booking Details')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Bookings', 'url' => route('admin.bookings.index')],
        ['label' => 'View Booking: ' . $booking->reference_id],
    ]" />

    @php
        $statusColors = [
            \App\Models\Booking::STATUS_PENDING_PAYMENT => 'warning',
            \App\Models\Booking::STATUS_CONFIRMED => 'primary',
            \App\Models\Booking::STATUS_ACTIVE => 'info',
            \App\Models\Booking::STATUS_COMPLETED => 'success',
            \App\Models\Booking::STATUS_CANCELLED => 'danger',
        ];
        $payColors = [
            \App\Models\Booking::PAYMENT_UNPAID => 'danger',
            \App\Models\Booking::PAYMENT_PAID => 'success',
            \App\Models\Booking::PAYMENT_REFUND_INITIATED => 'warning',
            \App\Models\Booking::PAYMENT_REFUNDED => 'info',
            \App\Models\Booking::PAYMENT_PARTIALLY_REFUNDED => 'primary',
        ];
        $statusColor = $statusColors[$booking->status] ?? 'dark';
        $payColor = $payColors[$booking->payment_status] ?? 'dark';
    @endphp

    <div class="d-flex flex-column gap-7 gap-lg-10">

        {{-- ── HEADER ───────────────────────────────────────────────────────── --}}
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-icon btn-light btn-active-secondary btn-sm">
                    <i class="ki-outline ki-arrow-left fs-4"></i>
                </a>
                <h1 class="fw-bold text-gray-900 fs-4 mb-0">
                    Booking <span class="text-primary">#{{ $booking->reference_id }}</span>
                </h1>
                <span class="badge badge-light-{{ $statusColor }} fs-8 px-3 py-1 border border-{{ $statusColor }}">
                    {{ $booking->status_text }}
                </span>
                <span class="badge badge-light-{{ $payColor }} fs-8 px-3 py-1 border border-{{ $payColor }}">
                    {{ $booking->payment_status_text }}
                </span>
            </div>

            <div class="d-flex align-items-center gap-3">
                @if($booking->careRequest)
                    <a href="{{ route('admin.requests.show', $booking->care_request_id) }}"
                       class="btn btn-light-info border border-info btn-sm fw-bold fs-8 px-4 py-2">
                        <i class="ki-outline ki-clipboard fs-4 me-1"></i> View Request
                    </a>
                @endif
            </div>
        </div>

        <div class="row g-7">
            {{-- ── LEFT COLUMN ──────────────────────────────────────────────── --}}
            <div class="col-lg-8">

                {{-- Financial & Commission Overview --}}
                <div class="card shadow-sm mb-7 border border-primary border-dashed bg-light-primary">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-primary"><i class="ki-outline ki-bill fs-3 text-primary me-2"></i> Financial & Commission Overview</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="row g-4">
                            <div class="col-sm-3">
                                <div class="bg-body rounded p-3 border border-gray-300">
                                    <span class="text-gray-600 fw-semibold d-block fs-8 mb-1">Total Amount</span>
                                    <span class="fw-bold fs-5 text-gray-900">₹{{ number_format($booking->total_amount, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="bg-body rounded p-3 border border-gray-300">
                                    <span class="text-gray-600 fw-semibold d-block fs-8 mb-1">Commission Type</span>
                                    <span class="fw-bold fs-6 text-gray-900">
                                        @if($booking->commission_type == 1)
                                            Percentage
                                        @elseif($booking->commission_type == 2)
                                            Flat
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="bg-body rounded p-3 border border-gray-300">
                                    <span class="text-gray-600 fw-semibold d-block fs-8 mb-1">Commission Value</span>
                                    <span class="fw-bold fs-5 text-gray-900">
                                        @if($booking->commission_type == 1)
                                            {{ $booking->commission_value }}%
                                        @elseif($booking->commission_type == 2)
                                            ₹{{ $booking->commission_value }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="bg-body rounded p-3 border border-success border-dashed">
                                    <span class="text-success fw-semibold d-block fs-8 mb-1">Earned Commission</span>
                                    <span class="fw-bold fs-5 text-success">₹{{ number_format($booking->commission_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        @if($booking->payment_method)
                            <div class="d-flex align-items-center flex-wrap gap-4 mt-4 pt-4 border-top border-gray-300">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-gray-600 fw-semibold fs-7">Payment Method:</span>
                                    <span class="badge badge-light-primary border border-primary px-3 py-1">{{ $booking->payment_method_text }}</span>
                                </div>
                                @if($booking->wallet_amount_used > 0)
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-gray-600 fw-semibold fs-7">Wallet Used:</span>
                                        <span class="fw-bold fs-6 text-warning">₹{{ number_format($booking->wallet_amount_used, 2) }}</span>
                                    </div>
                                @endif
                                @if($booking->gateway_amount > 0)
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-gray-600 fw-semibold fs-7">Gateway Paid:</span>
                                        <span class="fw-bold fs-6 text-primary">₹{{ number_format($booking->gateway_amount, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Schedule & Service Card --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Schedule & Service</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex flex-wrap gap-4 mb-5">
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-heart fs-3 text-danger me-2"></i>
                                    <div class="fs-6 fw-bold text-gray-900">{{ $booking->careRequest->careType->name ?? 'N/A' }}</div>
                                </div>
                                <div class="fw-semibold fs-8 text-gray-600 mt-1">Care Type</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-calendar fs-3 text-primary me-2"></i>
                                    <div class="fs-6 fw-bold text-gray-900">{{ $booking->total_sessions }} Session{{ $booking->total_sessions > 1 ? 's' : '' }}</div>
                                </div>
                                <div class="fw-semibold fs-8 text-gray-600 mt-1">Total Sessions</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-check-circle fs-3 text-success me-2"></i>
                                    <div class="fs-6 fw-bold text-gray-900">{{ $booking->completed_sessions }} / {{ $booking->total_sessions }}</div>
                                </div>
                                <div class="fw-semibold fs-8 text-gray-600 mt-1">Completed</div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="bg-light-primary rounded p-3 border border-primary border-dashed">
                                    <span class="text-primary fw-semibold d-block fs-8 mb-1">Start Date & Time</span>
                                    <span class="fw-bold fs-6 text-gray-900">{{ $booking->start_date ? $booking->start_date->format('d M Y') : 'N/A' }}</span>
                                    <span class="text-gray-700 fw-semibold fs-7 ms-2">{{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bg-light-danger rounded p-3 border border-danger border-dashed">
                                    <span class="text-danger fw-semibold d-block fs-8 mb-1">End Date & Time</span>
                                    <span class="fw-bold fs-6 text-gray-900">{{ $booking->end_date ? $booking->end_date->format('d M Y') : 'N/A' }}</span>
                                    <span class="text-gray-700 fw-semibold fs-7 ms-2">{{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sessions Table (AJAX) --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-5 mb-0">Sessions</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">{{ $booking->completed_sessions }} of {{ $booking->total_sessions }} completed</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                            @include('admin.layouts.partials._table-skeleton', ['id' => 'sessions-skeleton'])
                            <div id="sessions-table-wrapper" class="d-none">
                                <table id="sessions-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3 w-100">
                                    <thead>
                                        <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                            <th class="ps-3 rounded-start">#</th>
                                            <th class="min-w-100px">Date</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Started At</th>
                                            <th>Ended At</th>
                                            <th>Status</th>
                                            <th>OTP</th>
                                            <th class="rounded-end">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>

                {{-- Bid Details --}}
                @if($booking->bid)
                    <div class="card shadow-sm mb-7 border border-gray-300">
                        <div class="card-header border-0 pt-4 min-h-50px">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Selected Bid</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2 pb-5">
                            @php
                                $bid = $booking->bid;
                                $bidStatusColors = [0 => 'warning', 1 => 'success', 2 => 'danger', 3 => 'secondary', 4 => 'dark'];
                                $bidColor = $bidStatusColors[$bid->status] ?? 'dark';
                            @endphp
                            <div class="d-flex flex-wrap gap-4 mb-4">
                                <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                                    <div class="fs-4 fw-bold text-gray-900">₹{{ number_format($bid->nurse_amount, 2) }}</div>
                                    <div class="fw-semibold fs-8 text-gray-600">Nurse Amount</div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                                    <div class="fs-4 fw-bold text-success">₹{{ number_format($bid->commission_amount, 2) }}</div>
                                    <div class="fw-semibold fs-8 text-gray-600">Commission</div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                                    <div class="fs-4 fw-bold text-primary">₹{{ number_format($bid->total_amount, 2) }}</div>
                                    <div class="fw-semibold fs-8 text-gray-600">Total Amount</div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded py-3 px-4">
                                    <span class="badge badge-light-{{ $bidColor }} border border-{{ $bidColor }} fw-bold px-3 py-2">{{ $bid->status_text }}</span>
                                    <div class="fw-semibold fs-8 text-gray-600 mt-1">Bid Status</div>
                                </div>
                            </div>
                            @if($bid->notes)
                                <div class="bg-light-info border border-info border-dashed rounded p-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="ki-outline ki-message-text fs-4 text-info me-2"></i>
                                        <span class="fw-bold text-gray-900 fs-7">Nurse Notes</span>
                                    </div>
                                    <p class="text-gray-800 fw-medium fs-7 mb-0">{{ $bid->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- All Bids List --}}
                @if($booking->careRequest && $booking->careRequest->bids->count() > 0)
                    <div class="card shadow-sm mb-7 border border-gray-300">
                        <div class="card-header border-0 pt-4 min-h-50px">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-5 mb-0 text-gray-900">All Bids for this Request</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">{{ $booking->careRequest->bids->count() }} bids total</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2 pb-5">
                            @include('admin.layouts.partials._table-skeleton', ['id' => 'bids-skeleton'])
                            <div id="bids-table-wrapper" class="table-responsive d-none">
                                <table id="bids-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3 w-100" data-server-side="true">
                                    <thead>
                                        <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                            <th class="ps-3 rounded-start min-w-150px">Nurse</th>
                                            <th>Nurse Amount</th>
                                            <th>Commission</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="rounded-end min-w-150px">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Ratings & Reviews Table (AJAX) --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Ratings & Reviews</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        @include('admin.layouts.partials._table-skeleton', ['id' => 'ratings-skeleton'])
                        <div id="ratings-table-wrapper" class="table-responsive d-none">
                            <table id="ratings-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3 w-100" data-server-side="true">
                                <thead>
                                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                        <th class="ps-3 rounded-start min-w-200px">User</th>
                                        <th class="min-w-100px">Rating</th>
                                        <th class="min-w-200px">Review</th>
                                        <th class="rounded-end min-w-100px">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Payment Logs Table (AJAX) --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-5 mb-0">Payment Logs</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="table-responsive">
                            <table id="payment-logs-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                        <th class="ps-3 rounded-start">Event</th>
                                        <th>Amount</th>
                                        <th>Gateway</th>
                                        <th>Order ID</th>
                                        <th>Payment ID</th>
                                        <th>Status</th>
                                        <th class="rounded-end">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Cancellation Info --}}
                @if($booking->isCancelled())
                    <div class="card shadow-sm mb-7 bg-light-danger border border-danger border-dashed">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-cross-circle fs-2 text-danger me-2"></i>
                                <span class="fw-bold text-gray-900 fs-5">Cancellation Details</span>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex flex-stack">
                                    <span class="text-gray-600 fw-semibold fs-7">Cancelled By</span>
                                    <span class="fw-bold fs-7 text-gray-900">
                                        @switch($booking->cancelled_by)
                                            @case(1) <span class="badge badge-light-warning border border-warning">User</span> @break
                                            @case(2) <span class="badge badge-light-info border border-info">Nurse</span> @break
                                            @case(3) <span class="badge badge-light-danger border border-danger">Admin</span> @break
                                            @case(4) <span class="badge badge-light-secondary border border-secondary">System</span> @break
                                            @default <span class="text-muted">Unknown</span>
                                        @endswitch
                                    </span>
                                </div>
                                <div class="separator separator-dashed border-gray-300"></div>
                                <div class="d-flex flex-stack">
                                    <span class="text-gray-600 fw-semibold fs-7">Cancelled At</span>
                                    <span class="fw-bold fs-7 text-gray-900">{{ $booking->cancelled_at ? $booking->cancelled_at->format('d M Y, h:i A') : 'N/A' }}</span>
                                </div>
                                @if($booking->cancellation_reason)
                                    <div class="separator separator-dashed border-gray-300"></div>
                                    <div>
                                        <span class="text-gray-600 fw-semibold fs-7 d-block mb-1">Reason</span>
                                        <span class="fw-semibold fs-7 text-gray-900">{{ $booking->cancellation_reason }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Booking Timeline --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Booking Timeline</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="timeline">
                            {{-- Created --}}
                            <div class="timeline-item">
                                <div class="timeline-line w-40px"></div>
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-plus-square fs-3 text-primary"></i>
                                    </div>
                                </div>
                                <div class="timeline-content mb-10 mt-n1">
                                    <div class="pe-3 mb-2">
                                        <div class="fs-6 fw-bold text-gray-900 mb-1">Booking Created</div>
                                        <div class="d-flex align-items-center fs-8 fw-semibold text-gray-600">
                                            <i class="ki-outline ki-time fs-7 me-1"></i>
                                            {{ $booking->created_at->format('d M Y, h:i A') }}
                                            <span class="text-muted ms-2">({{ $booking->created_at->diffForHumans() }})</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge badge-light-primary border border-primary px-2 py-1 fs-9">Ref: {{ $booking->reference_id }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment --}}
                            @if($booking->payment_status >= 1)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-dollar fs-3 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Payment Received</div>
                                            <div class="d-flex align-items-center fs-8 fw-semibold text-gray-600">
                                                <i class="ki-outline ki-time fs-7 me-1"></i>
                                                <span>₹{{ number_format($booking->total_amount, 2) }}</span>
                                                @if($booking->payment_method)
                                                    <span class="badge badge-light-info px-2 py-1 ms-2 fs-9">{{ $booking->payment_method_text }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if($booking->wallet_amount_used > 0)
                                                <span class="badge badge-light-warning border border-warning px-2 py-1 fs-9">Wallet: ₹{{ number_format($booking->wallet_amount_used, 2) }}</span>
                                            @endif
                                            @if($booking->gateway_amount > 0)
                                                <span class="badge badge-light-primary border border-primary px-2 py-1 fs-9">Gateway: ₹{{ number_format($booking->gateway_amount, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Active --}}
                            @if($booking->status >= 2)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-info">
                                            <i class="ki-outline ki-rocket fs-3 text-info"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Booking Activated</div>
                                            <div class="fs-8 fw-semibold text-gray-600">Sessions started being tracked</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Completed --}}
                            @if($booking->status === \App\Models\Booking::STATUS_COMPLETED)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-check-circle fs-3 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Booking Completed</div>
                                            <div class="fs-8 fw-semibold text-gray-600">All {{ $booking->total_sessions }} sessions completed</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Cancelled --}}
                            @if($booking->status === \App\Models\Booking::STATUS_CANCELLED)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-danger">
                                            <i class="ki-outline ki-cross-circle fs-3 text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Booking Cancelled</div>
                                            <div class="d-flex align-items-center fs-8 fw-semibold text-gray-600">
                                                <i class="ki-outline ki-time fs-7 me-1"></i>
                                                {{ $booking->cancelled_at ? $booking->cancelled_at->format('d M Y, h:i A') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Refund --}}
                            @if($booking->refund_amount > 0)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-warning">
                                            <i class="ki-outline ki-arrow-circle-left fs-3 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Refund Processed</div>
                                            <div class="fs-8 fw-semibold text-gray-600">₹{{ number_format($booking->refund_amount, 2) }} refunded</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Updated --}}
                            <div class="timeline-item">
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light">
                                        <i class="ki-outline ki-time fs-3 text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="timeline-content mt-n1">
                                    <div class="pe-3">
                                        <div class="fs-7 fw-semibold text-gray-600">Last Updated: {{ $booking->updated_at->format('d M Y, h:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Comments Component --}}
                <x-comments type="{{ \App\Models\Booking::class }}" :model-id="$booking->id" />

            </div>

            {{-- ── RIGHT COLUMN ─────────────────────────────────────────────── --}}
            <div class="col-lg-4">

                {{-- User (Patient) Card --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Patient / User</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        @if($booking->user)
                            <div class="d-flex flex-center flex-column mb-4">
                                <div class="symbol symbol-60px symbol-circle mb-3 shadow-sm" style="border: 3px solid #fff;">
                                    @if($booking->user->profile_photo)
                                        <img src="{{ Storage::url($booking->user->profile_photo) }}" alt="{{ $booking->user->name }}" class="object-fit-cover" />
                                    @else
                                        <span class="symbol-label bg-light-primary text-primary fs-3 fw-bold border border-primary">
                                            {{ mb_strtoupper(mb_substr($booking->user->name ?? 'U', 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('admin.patients.show', $booking->user->id) }}"
                                    class="fs-6 text-gray-900 text-hover-primary fw-bold mb-1">
                                    {{ $booking->user->name }}
                                </a>
                                <div class="fs-8 fw-semibold text-gray-600 mb-2">ID: {{ $booking->user->id }}</div>
                                <div class="badge badge-light-success border border-success fw-bold px-3 py-1 fs-8">Active Member</div>
                            </div>
                            <div class="separator separator-dashed border-gray-300 my-4"></div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-sms fs-4 text-primary me-2"></i>
                                <div class="fs-7 text-gray-800 fw-semibold">{{ $booking->user->phone ?? $booking->user->email ?? 'N/A' }}</div>
                            </div>
                            <a href="{{ route('admin.patients.show', $booking->user->id) }}"
                                class="btn btn-light-primary border border-primary btn-sm w-100 fw-bold fs-8 px-3 py-2">
                                Go to Profile <i class="ki-outline ki-arrow-right fs-6 ms-1"></i>
                            </a>
                        @else
                            <div class="text-center text-gray-600 fs-7">Unknown User</div>
                        @endif
                    </div>
                </div>

                {{-- Location Card --}}
                @if($booking->careRequest && $booking->careRequest->address)
                    <div class="card shadow-sm mb-7 border border-gray-300">
                        <div class="card-header border-0 pt-4 min-h-50px">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Location</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2 pb-5">
                            <div class="d-flex align-items-start mb-3">
                                <span class="bullet bullet-vertical h-30px bg-success me-3 mt-1"></span>
                                <div class="flex-grow-1">
                                    <span class="text-gray-600 fw-semibold d-block fs-8">Full Address</span>
                                    <span class="fw-bold fs-7 text-gray-900">{{ $booking->careRequest->address }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <span class="bullet bullet-vertical h-30px bg-primary me-3 mt-1"></span>
                                <div class="flex-grow-1">
                                    <span class="text-gray-600 fw-semibold d-block fs-8">City & State</span>
                                    <span class="fw-bold fs-7 text-gray-900">{{ $booking->careRequest->city ?? 'N/A' }},
                                        {{ $booking->careRequest->state ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <span class="bullet bullet-vertical h-30px bg-warning me-3 mt-1"></span>
                                <div class="flex-grow-1">
                                    <span class="text-gray-600 fw-semibold d-block fs-8">Pincode</span>
                                    <span class="fw-bold fs-7 text-gray-900">{{ $booking->careRequest->pincode ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Nurse Card --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Assigned Nurse</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        @if($booking->nurse && $booking->nurse->user)
                            @php $nurseUser = $booking->nurse->user; @endphp
                            <div class="d-flex flex-center flex-column mb-4">
                                <div class="symbol symbol-60px symbol-circle mb-3 shadow-sm" style="border: 3px solid #fff;">
                                    @if($nurseUser->profile_photo)
                                        <img src="{{ Storage::url($nurseUser->profile_photo) }}" alt="{{ $nurseUser->name }}" class="object-fit-cover" />
                                    @else
                                        <span class="symbol-label bg-light-info text-info fs-3 fw-bold border border-info">
                                            {{ mb_strtoupper(mb_substr($nurseUser->name ?? 'N', 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('admin.nurses.show', $nurseUser->id) }}"
                                    class="fs-6 text-gray-900 text-hover-primary fw-bold mb-1">
                                    {{ $nurseUser->name }}
                                </a>
                                <div class="fs-8 fw-semibold text-gray-600 mb-2">Nurse ID: {{ $booking->nurse->id }}</div>
                                <div class="badge badge-light-info border border-info fw-bold px-3 py-1 fs-8">Nurse</div>
                            </div>
                            <div class="separator separator-dashed border-gray-300 my-4"></div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-sms fs-4 text-info me-2"></i>
                                <div class="fs-7 text-gray-800 fw-semibold">{{ $nurseUser->phone ?? $nurseUser->email ?? 'N/A' }}</div>
                            </div>
                            <a href="{{ route('admin.nurses.show', $nurseUser->id) }}"
                                class="btn btn-light-info border border-info btn-sm w-100 fw-bold fs-8 px-3 py-2">
                                Go to Profile <i class="ki-outline ki-arrow-right fs-6 ms-1"></i>
                            </a>
                        @else
                            <div class="text-center text-gray-600 fs-7">No Nurse Assigned</div>
                        @endif
                    </div>
                </div>

                {{-- Financials Card --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Rates</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Per Session Rate</span>
                                <span class="fw-bold fs-7 text-gray-900">₹{{ number_format($booking->per_session_rate, 2) }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Nurse/Session Rate</span>
                                <span class="fw-bold fs-7 text-gray-900">₹{{ number_format($booking->nurse_per_session_rate, 2) }}</span>
                            </div>
                        </div>

                        @if($booking->refund_amount > 0 || $booking->nurse_payout_amount > 0)
                            <div class="separator separator-dashed border-gray-300 my-4"></div>
                            <div class="bg-light-success border border-success border-dashed rounded p-3">
                                <span class="text-success fw-semibold d-block fs-8 mb-2">Other Financials</span>
                                @if($booking->refund_amount > 0)
                                    <div class="d-flex flex-stack mb-2">
                                        <span class="text-gray-600 fs-8">Refund Amount</span>
                                        <span class="fw-bold fs-8 text-danger">₹{{ number_format($booking->refund_amount, 2) }}</span>
                                    </div>
                                @endif
                                @if($booking->nurse_payout_amount > 0)
                                    <div class="d-flex flex-stack">
                                        <span class="text-gray-600 fs-8">Nurse Payout</span>
                                        <span class="fw-bold fs-8 text-info">₹{{ number_format($booking->nurse_payout_amount, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Gateway IDs --}}
                @if($booking->gateway_order_id || $booking->gateway_payment_id)
                    <div class="card shadow-sm mb-7 border border-gray-300">
                        <div class="card-header border-0 pt-4 min-h-50px">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Gateway Info</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2 pb-5">
                            <div class="d-flex flex-column gap-3">
                                @if($booking->gateway_order_id)
                                    <div>
                                        <span class="text-gray-600 fw-semibold d-block fs-8 mb-1">Order ID</span>
                                        <code class="fs-8 text-gray-900 bg-light p-2 rounded d-block">{{ $booking->gateway_order_id }}</code>
                                    </div>
                                @endif
                                @if($booking->gateway_payment_id)
                                    <div>
                                        <span class="text-gray-600 fw-semibold d-block fs-8 mb-1">Payment ID</span>
                                        <code class="fs-8 text-gray-900 bg-light p-2 rounded d-block">{{ $booking->gateway_payment_id }}</code>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Request Link --}}
                @if($booking->careRequest)
                    <div class="card shadow-sm mb-7 border border-gray-300">
                        <div class="card-header border-0 pt-4 min-h-50px">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Linked Request</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2 pb-5">
                            <div class="d-flex align-items-center mb-3">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-primary text-primary border border-primary">
                                        <i class="ki-outline ki-clipboard fs-4"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">#{{ $booking->careRequest->reference_id ?? 'N/A' }}</span>
                                    <span class="text-gray-600 fw-semibold fs-8">Care Request</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.requests.show', $booking->care_request_id) }}"
                                class="btn btn-light-primary border border-primary btn-sm w-100 fw-bold fs-8 px-3 py-2">
                                View Request <i class="ki-outline ki-arrow-right fs-6 ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Parent / Extensions --}}
                @if($booking->parentBooking)
                    <div class="card shadow-sm mb-7 border border-gray-300">
                        <div class="card-header border-0 pt-4 min-h-50px">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Parent Booking</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2 pb-5">
                            <a href="{{ route('admin.bookings.show', $booking->parent_booking_id) }}"
                                class="btn btn-light-warning border border-warning btn-sm w-100 fw-bold fs-8 px-3 py-2">
                                #{{ $booking->parentBooking->reference_id }} <i class="ki-outline ki-arrow-right fs-6 ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endif

                @if($booking->extensions->count() > 0)
                    <div class="card shadow-sm mb-7 border border-gray-300">
                        <div class="card-header border-0 pt-4 min-h-50px">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Extensions</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2 pb-5">
                            <div class="d-flex flex-column gap-2">
                                @foreach($booking->extensions as $ext)
                                    <a href="{{ route('admin.bookings.show', $ext->id) }}"
                                       class="btn btn-light border border-gray-300 btn-sm w-100 fw-bold fs-8 px-3 py-2 text-start">
                                        <i class="ki-outline ki-arrow-right fs-6 me-1 text-primary"></i>
                                        #{{ $ext->reference_id }}
                                        <span class="badge badge-light-{{ $statusColors[$ext->status] ?? 'dark' }} ms-2 fs-9">{{ $ext->status_text }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Metadata --}}
                <div class="card shadow-sm border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Metadata</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Booking ID</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $booking->id }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Request ID</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $booking->care_request_id }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Bid ID</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $booking->bid_id }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Created</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $booking->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Updated</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $booking->updated_at->format('d M Y, h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection

@push('datatables_css')
    @include('admin.layouts.partials._datatable-cdn-css')
@endpush

@push('datatables_js')
    @include('admin.layouts.partials._datatable-cdn-js')
    <script>
        $(document).ready(function () {

            // Common DataTable options to handle serverSide and skeleton seamlessly
            const getDtOpts = (skeletonId, wrapperId) => {
                return {
                    processing: false, // We use skeleton instead of native processing overlay
                    serverSide: true,
                    paging: true,
                    pageLength: 5,
                    lengthMenu: [5, 10, 25],
                    searching: false,
                    info: true,
                    ordering: false,
                    dom:
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'" +
                        "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                        "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
                    language: {
                        emptyTable: "No data available.",
                        info: "Showing _START_ to _END_ of _TOTAL_",
                        infoEmpty: "Showing 0 to 0 of 0",
                        paginate: {
                            previous: '<i class="ki-duotone ki-arrow-left"></i>',
                            next: '<i class="ki-duotone ki-arrow-right"></i>',
                        }
                    },
                    initComplete: function () {
                        $('#' + skeletonId).fadeOut(200, function () {
                            $(this).remove();
                            $('#' + wrapperId).removeClass('d-none').hide().fadeIn(200);
                        });
                    }
                };
            };

            // ── Sessions Table (AJAX) ───────────────────────────────────────
            $('#sessions-table').DataTable(Object.assign({}, getDtOpts('sessions-skeleton', 'sessions-table-wrapper'), {
                ajax: '{{ route('admin.bookings.sessions-data', $booking->id) }}',
                columns: [
                    { data: 'session_number', className: 'ps-3 fw-bold text-gray-800' },
                    { data: 'session_date', className: 'fw-semibold text-gray-800' },
                    { data: 'start_time', className: 'text-gray-700' },
                    { data: 'end_time', className: 'text-gray-700' },
                    { data: 'started_at', className: 'text-gray-700 fs-8' },
                    { data: 'ended_at', className: 'text-gray-700 fs-8' },
                    { data: 'status' },
                    { data: 'otp_verified' },
                    { data: 'nurse_notes', className: 'text-gray-600 fs-8', render: function(data) {
                        return '<div style="max-width:150px; white-space:normal;">' + data + '</div>';
                    }}
                ]
            }));

            // ── Bids Table (AJAX) ──────────────────────────────────────────
            @if($booking->care_request_id)
                $('#bids-table').DataTable(Object.assign({}, getDtOpts('bids-skeleton', 'bids-table-wrapper'), {
                    ajax: '{{ route('admin.bookings.bids-data', $booking->id) }}',
                    columns: [
                        { data: 'nurse', className: 'ps-3' },
                        { data: 'nurse_amount' },
                        { data: 'commission' },
                        { data: 'total' },
                        { data: 'status' },
                        { data: 'notes' }
                    ]
                }));
            @endif

            // ── Ratings & Reviews Table (AJAX) ───────────────────────────────
            $('#ratings-table').DataTable(Object.assign({}, getDtOpts('ratings-skeleton', 'ratings-table-wrapper'), {
                ajax: '{{ route('admin.bookings.reviews-data', $booking->id) }}',
                columns: [
                    { data: 'user', className: 'ps-3' },
                    { data: 'rating' },
                    { data: 'review', className: 'text-gray-700 fs-7 text-wrap' },
                    { data: 'created_at' }
                ]
            }));

            // ── Payment Logs Table (AJAX) ────────────────────────────────────
            $('#payment-logs-table').DataTable(Object.assign({}, getDtOpts('payment-logs-skeleton', 'payment-logs-table-wrapper'), {
                ajax: '{{ route('admin.bookings.payment-logs-data', $booking->id) }}',
                columns: [
                    { data: 'event', className: 'ps-3' },
                    { data: 'amount' },
                    { data: 'gateway' },
                    { data: 'gateway_order_id' },
                    { data: 'gateway_payment_id' },
                    { data: 'status' },
                    { data: 'created_at', className: 'text-gray-700 fs-8' }
                ]
            }));

        });
    </script>
@endpush
