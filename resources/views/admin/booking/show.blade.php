@extends('admin.layouts.app')

@section('title', 'Booking Details')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Bookings', 'url' => route('admin.bookings.index')],
        ['label' => 'View Booking: ' . $booking->reference_id],
    ]" />


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
                <span class="badge badge-light-{{ $booking->status_color }} fs-8 px-3 py-1 border border-{{ $booking->status_color }}">
                    {{ $booking->status_text }}
                </span>
                <span class="badge badge-light-{{ $booking->payment_status_color }} fs-8 px-3 py-1 border border-{{ $booking->payment_status_color }}">
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

        {{-- ── INFO CARDS (ABOVE TABS) ──────────────────────────────────────── --}}
        <div class="row g-5 g-xl-8">
            
            {{-- Patient / User Card --}}
            <div class="col-xl-4">
                <div class="card shadow-sm h-100 border border-primary">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                        <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Patient Info</h3>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        @if($booking->user)
                            <div class="d-flex align-items-center mb-4">
                                <div class="symbol symbol-45px symbol-circle me-4">
                                    @if($booking->user->profile_photo)
                                        <img src="{{ Storage::url($booking->user->profile_photo) }}" alt="{{ $booking->user->name }}" class="object-fit-cover" />
                                    @else
                                        <span class="symbol-label bg-white text-gray-700 fs-5 fw-bold border border-gray-200">
                                            {{ mb_strtoupper(mb_substr($booking->user->name ?? 'U', 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('admin.patients.show', $booking->user->id) }}" class="fs-6 text-gray-900 text-hover-primary fw-bold d-block">{{ $booking->user->name }}</a>
                                    <span class="text-gray-600 fs-8">{{ $booking->user->phone ?? $booking->user->email ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge badge-light-success border border-success fw-bold px-2 py-1 fs-8">Active</span>
                                <span class="badge badge-light fw-bold px-2 py-1 fs-8 text-gray-700 border border-gray-200">ID: {{ $booking->user->id }}</span>
                            </div>
                            <a href="{{ route('admin.patients.show', $booking->user->id) }}" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-sm w-100 text-uppercase fw-bold fs-9 px-3 py-2">
                                View Full Profile <i class="ki-outline ki-arrow-right fs-7 ms-2"></i>
                            </a>
                        @else
                            <div class="text-gray-600 fs-7">Unknown User</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Assigned Nurse Card --}}
            <div class="col-xl-4">
                <div class="card shadow-sm h-100 border border-primary">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                        <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Assigned Nurse</h3>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        @if($booking->nurse && $booking->nurse->user)
                            @php $nurseUser = $booking->nurse->user; @endphp
                            <div class="d-flex align-items-center mb-4">
                                <div class="symbol symbol-45px symbol-circle me-4">
                                    @if($nurseUser->profile_photo)
                                        <img src="{{ Storage::url($nurseUser->profile_photo) }}" alt="{{ $nurseUser->name }}" class="object-fit-cover" />
                                    @else
                                        <span class="symbol-label bg-white text-gray-700 fs-5 fw-bold border border-gray-200">
                                            {{ mb_strtoupper(mb_substr($nurseUser->name ?? 'N', 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('admin.nurses.show', $nurseUser->id) }}" class="fs-6 text-gray-900 text-hover-primary fw-bold d-block">{{ $nurseUser->name }}</a>
                                    <span class="text-gray-600 fs-8">{{ $nurseUser->phone ?? $nurseUser->email ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge badge-light-info border border-info fw-bold px-2 py-1 fs-8">Nurse ID: {{ $booking->nurse->id }}</span>
                            </div>
                            <a href="{{ route('admin.nurses.show', $nurseUser->id) }}" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-sm w-100 text-uppercase fw-bold fs-9 px-3 py-2">
                                View Full Profile <i class="ki-outline ki-arrow-right fs-7 ms-2"></i>
                            </a>
                        @else
                            <div class="text-center text-gray-600 fs-7 mt-5">No Nurse Assigned</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Location & References Card --}}
            <div class="col-xl-4">
                <div class="card shadow-sm h-100 border border-primary">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                        <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Location & References</h3>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        @if($booking->careRequest)
                            <div class="mb-3">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9 mb-1">Service Address</span>
                                <span class="fw-bold fs-7 text-gray-900 d-block">{{ $booking->careRequest->address }}</span>
                                <span class="fw-semibold fs-8 text-gray-700">{{ $booking->careRequest->city ?? 'N/A' }}, {{ $booking->careRequest->state ?? 'N/A' }} - {{ $booking->careRequest->pincode ?? 'N/A' }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-200 my-3"></div>
                            <div class="d-flex flex-stack mb-2">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Linked Request</span>
                                <a href="{{ route('admin.requests.show', $booking->care_request_id) }}" class="fw-bold fs-7 text-primary text-hover-primary">#{{ $booking->careRequest->reference_id ?? $booking->care_request_id }}</a>
                            </div>
                        @endif
                        @if($booking->parentBooking)
                            <div class="d-flex flex-stack mb-2">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Parent Booking</span>
                                <a href="{{ route('admin.bookings.show', $booking->parent_booking_id) }}" class="fw-bold fs-7 text-warning text-hover-primary">#{{ $booking->parentBooking->reference_id }}</a>
                            </div>
                        @endif
                        @if($booking->extensions->count() > 0)
                            <div class="mt-2">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9 mb-1">Extensions</span>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($booking->extensions as $ext)
                                        <a href="{{ route('admin.bookings.show', $ext->id) }}" class="badge badge-light-primary border border-primary fw-bold px-2 py-1 fs-8 text-gray-700 text-hover-primary">
                                            #{{ $ext->reference_id }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ── FULL WIDTH TABS ────────────────────────────────────────────────── --}}
        <div>
            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold mb-5" id="booking-tabs">
                <li class="nav-item">
                    <a class="nav-link active text-gray-600 text-active-primary px-4 py-3" data-bs-toggle="tab" href="#tab-overview">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-gray-600 text-active-primary px-4 py-3" data-bs-toggle="tab" href="#tab-sessions">Sessions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-gray-600 text-active-primary px-4 py-3" data-bs-toggle="tab" href="#tab-bids">Bids</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-gray-600 text-active-primary px-4 py-3" data-bs-toggle="tab" href="#tab-reviews">Reviews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-gray-600 text-active-primary px-4 py-3" data-bs-toggle="tab" href="#tab-payments">Payment Logs</a>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content" id="booking-tabs-content">

                {{-- ── Overview Tab ──────────────────────────────────────── --}}
                <div class="tab-pane fade show active" id="tab-overview">

                    <div class="row g-5 g-xl-8">
                        {{-- Financial & Commission --}}
                        <div class="col-xl-6">
                            <div class="card shadow-sm mb-5 border border-primary h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Financial & Rates</h3>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <div class="row g-4 mb-5">
                                        <div class="col-sm-6">
                                            <div class="bg-white rounded p-4 border border-primary border-dashed ">
                                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9 mb-1">Total Amount</span>
                                                <span class="fw-bold fs-4 text-gray-900">₹{{ number_format($booking->total_amount, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="bg-white rounded p-4 border border-primary border-dashed ">
                                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9 mb-1">Earned Commission</span>
                                                <span class="fw-bold fs-4 text-gray-900">₹{{ number_format($booking->commission_amount, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <table class="table table-row-bordered align-middle fs-7 gy-3 mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="text-gray-600 fw-semibold border-0 py-2">Commission Setup</td>
                                                <td class="text-gray-900 fw-semibold text-end border-0 py-2">
                                                    @if($booking->commission_type == 1) {{ $booking->commission_value }}%
                                                    @elseif($booking->commission_type == 2) ₹{{ $booking->commission_value }} (Flat)
                                                    @else N/A @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-600 fw-semibold border-0 py-2">Per Session Rate</td>
                                                <td class="text-gray-900 fw-bold text-end border-0 py-2">₹{{ number_format($booking->per_session_rate, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-600 fw-semibold border-0 py-2">Nurse/Session Rate</td>
                                                <td class="text-gray-900 fw-bold text-end border-0 py-2">₹{{ number_format($booking->nurse_per_session_rate, 2) }}</td>
                                            </tr>
                                            @if($booking->refund_amount > 0)
                                                <tr>
                                                    <td class="text-gray-600 fw-semibold border-0 py-2">Refund Amount</td>
                                                    <td class="text-danger fw-bold text-end border-0 py-2">₹{{ number_format($booking->refund_amount, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if($booking->nurse_payout_amount > 0)
                                                <tr>
                                                    <td class="text-gray-600 fw-semibold border-0 py-2">Nurse Payout</td>
                                                    <td class="text-info fw-bold text-end border-0 py-2">₹{{ number_format($booking->nurse_payout_amount, 2) }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Schedule & Service --}}
                        <div class="col-xl-6">
                            <div class="card shadow-sm mb-5 border border-primary h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Schedule & Service</h3>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <div class="d-flex flex-wrap gap-4 mb-5">
                                        <div class="border border-gray-200  rounded py-3 px-4 bg-white flex-grow-1">
                                            <div class="fs-6 fw-bold text-gray-900">{{ $booking->careRequest->careType->name ?? 'N/A' }}</div>
                                            <div class="fw-semibold fs-8 text-gray-600 mt-1">Care Type</div>
                                        </div>
                                        <div class="border border-gray-200  rounded py-3 px-4 bg-white flex-grow-1">
                                            <div class="fs-6 fw-bold text-gray-900">{{ $booking->completed_sessions }} / {{ $booking->total_sessions }}</div>
                                            <div class="fw-semibold fs-8 text-gray-600 mt-1">Sessions Completed</div>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="bg-white rounded p-4 border border-primary border-dashed ">
                                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9 mb-1">Start Date & Time</span>
                                                <span class="fw-bold fs-6 text-gray-900">{{ $booking->start_date ? $booking->start_date->format('d M Y') : 'N/A' }}</span>
                                                <span class="text-gray-500 text-uppercase fw-bold fs-9 ms-2">{{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="bg-white rounded p-4 border border-primary border-dashed ">
                                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9 mb-1">End Date & Time</span>
                                                <span class="fw-bold fs-6 text-gray-900">{{ $booking->end_date ? $booking->end_date->format('d M Y') : 'N/A' }}</span>
                                                <span class="text-gray-500 text-uppercase fw-bold fs-9 ms-2">{{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payments Details & Gateway Info --}}
                        <div class="col-xl-6">
                            <div class="card shadow-sm mb-5 border border-primary h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Payment Details</h3>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    @if($booking->payment_method)
                                        <div class="d-flex flex-stack mb-3">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Payment Method</span>
                                            <span class="badge badge-light-primary border border-primary text-gray-800 px-3 py-1">{{ $booking->payment_method_text }}</span>
                                        </div>
                                    @endif
                                    @if($booking->wallet_amount_used > 0)
                                        <div class="d-flex flex-stack mb-3">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Wallet Used</span>
                                            <span class="fw-bold fs-7 text-gray-900">₹{{ number_format($booking->wallet_amount_used, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($booking->gateway_amount > 0)
                                        <div class="d-flex flex-stack mb-3">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Gateway Paid</span>
                                            <span class="fw-bold fs-7 text-gray-900">₹{{ number_format($booking->gateway_amount, 2) }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($booking->gateway_order_id || $booking->gateway_payment_id)
                                        <div class="separator separator-dashed border-gray-200 my-4"></div>
                                        <table class="table table-row-bordered align-middle fs-7 gy-3 mb-0">
                                            <tbody>
                                                @if($booking->gateway_order_id)
                                                    <tr>
                                                        <td class="text-gray-600 fw-semibold border-0 py-2">Order ID</td>
                                                        <td class="border-0 py-2 text-end"><code class="fs-8 text-gray-900 bg-white p-1 rounded">{{ $booking->gateway_order_id }}</code></td>
                                                    </tr>
                                                @endif
                                                @if($booking->gateway_payment_id)
                                                    <tr>
                                                        <td class="text-gray-600 fw-semibold border-0 py-2">Payment ID</td>
                                                        <td class="border-0 py-2 text-end"><code class="fs-8 text-gray-900 bg-white p-1 rounded">{{ $booking->gateway_payment_id }}</code></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Selected Bid & Metadata --}}
                        <div class="col-xl-6">
                            <div class="card shadow-sm mb-5 border border-primary h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Selected Bid & Metadata</h3>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    @if($booking->bid)
                                        @php $bid = $booking->bid; @endphp
                                        <div class="d-flex flex-wrap gap-3 mb-4">
                                            <div class="border border-gray-200  rounded py-2 px-3 bg-white flex-grow-1">
                                                <div class="fs-6 fw-bold text-gray-900">₹{{ number_format($bid->nurse_amount, 2) }}</div>
                                                <div class="fw-semibold fs-8 text-gray-600">Nurse Amount</div>
                                            </div>
                                            <div class="border border-gray-200  rounded py-2 px-3 bg-white flex-grow-1">
                                                <div class="fs-6 fw-bold text-gray-900">₹{{ number_format($bid->total_amount, 2) }}</div>
                                                <div class="fw-semibold fs-8 text-gray-600">Total Bid Amount</div>
                                            </div>
                                        </div>
                                        @if($bid->notes)
                                            <div class="bg-white rounded p-4 border border-primary border-dashed  mb-4">
                                                <span class="fw-bold text-gray-900 fs-8 d-block mb-1">Nurse Notes</span>
                                                <p class="text-gray-800 fw-medium fs-8 mb-0">{{ $bid->notes }}</p>
                                            </div>
                                        @endif
                                    @endif

                                    <table class="table table-row-bordered align-middle fs-8 gy-2 mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="text-gray-600 fw-semibold border-0 py-1">Booking ID</td>
                                                <td class="text-gray-900 fw-bold border-0 py-1 text-end">{{ $booking->id }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-600 fw-semibold border-0 py-1">Bid ID</td>
                                                <td class="text-gray-900 fw-bold border-0 py-1 text-end">{{ $booking->bid_id ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-600 fw-semibold border-0 py-1">Created At</td>
                                                <td class="text-gray-900 fw-medium border-0 py-1 text-end">{{ $booking->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @include('admin.booking._cancellationinfo')

                </div>

                {{-- ── Sessions Tab ──────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-sessions">
                    <div class="card shadow-sm border border-gray-200">
                        <div class="card-body">
                            @include('admin.booking._session')
                        </div>
                    </div>
                </div>

                {{-- ── Bids Tab ──────────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-bids">
                    <div class="card shadow-sm border border-gray-200">
                        <div class="card-body">
                            @include('admin.booking._allbids')
                        </div>
                    </div>
                </div>

                {{-- ── Reviews Tab ───────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-reviews">
                    <div class="card shadow-sm border border-gray-200">
                        <div class="card-body">
                            @include('admin.booking._rating')
                        </div>
                    </div>
                </div>

                {{-- ── Payment Logs Tab ──────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-payments">
                    <div class="card shadow-sm border border-gray-200">
                        <div class="card-body">
                            @include('admin.booking._paymentlogs')
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── COMMENTS SECTION ───────────────────────────────────────────────── --}}
        <div class="mt-8">
            <div class="d-flex align-items-center mb-5">
                <i class="ki-outline ki-message-text-2 fs-2 text-gray-900 me-2"></i>
                <h3 class="fw-bold text-gray-900 fs-4 mb-0">Discussion / Comments</h3>
            </div>
            <div class="card shadow-sm border border-gray-200">
                <div class="card-body">
                    <x-comments type="{{ \App\Models\Booking::class }}" :model-id="$booking->id" />
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

            // Common DataTable options
            const getDtOpts = (skeletonId, wrapperId) => {
                return {
                    processing: false,
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

            // Track which tabs have been initialized
            var initialized = {};

            function initSessions() {
                if (initialized.sessions) return;
                initialized.sessions = true;
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
            }

            function initBids() {
                if (initialized.bids) return;
                initialized.bids = true;
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
            }

            function initReviews() {
                if (initialized.reviews) return;
                initialized.reviews = true;
                $('#ratings-table').DataTable(Object.assign({}, getDtOpts('ratings-skeleton', 'ratings-table-wrapper'), {
                    ajax: '{{ route('admin.bookings.reviews-data', $booking->id) }}',
                    columns: [
                        { data: 'user', className: 'ps-3' },
                        { data: 'rating' },
                        { data: 'review', className: 'text-gray-700 fs-7 text-wrap' },
                        { data: 'created_at' }
                    ]
                }));
            }

            function initPayments() {
                if (initialized.payments) return;
                initialized.payments = true;
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
            }

            // Initialize DataTables when their tab is shown (lazy loading)
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href');
                if (target === '#tab-sessions') initSessions();
                else if (target === '#tab-bids') initBids();
                else if (target === '#tab-reviews') initReviews();
                else if (target === '#tab-payments') initPayments();
            });

        });
    </script>
@endpush
