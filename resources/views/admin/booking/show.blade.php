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

        {{-- ── INFO HEADER WIDGET ──────────────────────────────────────── --}}
        <div class="card shadow-sm">
            <div class="card-body pt-5 pb-5">
                <div class="row g-5">
                    
                    {{-- Booked By (Creator) --}}
                    <div class="col-md-3 col-sm-6 pe-md-4">
                        <span class="text-gray-500 text-uppercase fw-bold fs-9 mb-2 d-block">Booked By (Creator)</span>
                        @if($booking->user)
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px symbol-circle me-3">
                                    @if($booking->user->profile_photo)
                                        <img src="{{ Storage::url($booking->user->profile_photo) }}" alt="img" class="object-fit-cover" />
                                    @else
                                        <span class="symbol-label bg-light-primary text-primary fw-bold">{{ mb_strtoupper(mb_substr($booking->user->name ?? 'U', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('admin.patients.show', $booking->user->id) }}" class="text-gray-900 text-hover-primary fw-bold fs-6">{{ $booking->user->name }}</a>
                                    <span class="text-gray-500 fs-8">{{ $booking->user->phone ?? $booking->user->email ?? 'N/A' }}</span>
                                    <a href="{{ route('admin.patients.show', $booking->user->id) }}" class="text-primary fs-9 fw-bold mt-1 d-flex align-items-center">View Profile <i class="ki-outline ki-arrow-right fs-9 text-primary ms-1"></i></a>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-600 fs-7">Unknown</span>
                        @endif
                    </div>

                    {{-- Patient Details --}}
                    <div class="col-md-3 col-sm-6 px-md-4 border-start border-gray-200">
                        <span class="text-gray-500 text-uppercase fw-bold fs-9 mb-2 d-block">Patient Name</span>
                        <div class="d-flex align-items-center">
                            <i class="ki-outline ki-user fs-2 text-info me-3"></i>
                            <div class="d-flex flex-column">
                                <span class="text-gray-900 fw-bold fs-6">{{ $booking->patient_name ?? ($booking->careRequest->patient_name ?? 'N/A') }} 
                                    @if($booking->patient_age || ($booking->careRequest && $booking->careRequest->patient_age))
                                        <span class="badge badge-light-info fs-9 px-2 py-0 ms-1">{{ $booking->patient_age ?? $booking->careRequest->patient_age }} yrs</span>
                                    @endif
                                </span>
                                <span class="text-gray-500 fs-8">{{ $booking->contact_phone ?? ($booking->careRequest->contact_phone ?? 'N/A') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Assigned Nurse --}}
                    <div class="col-md-3 col-sm-6 px-md-4 border-start border-gray-200">
                        <span class="text-gray-500 text-uppercase fw-bold fs-9 mb-2 d-block">Assigned Nurse</span>
                        @if($booking->nurse && $booking->nurse->user)
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px symbol-circle me-3">
                                    @if($booking->nurse->user->profile_photo)
                                        <img src="{{ Storage::url($booking->nurse->user->profile_photo) }}" alt="img" class="object-fit-cover" />
                                    @else
                                        <span class="symbol-label bg-light-success text-success fw-bold">{{ mb_strtoupper(mb_substr($booking->nurse->user->name ?? 'U', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('admin.nurses.show', $booking->nurse->user->id) }}" class="text-gray-900 text-hover-primary fw-bold fs-6">{{ $booking->nurse->user->name }}</a>
                                    <span class="text-gray-500 fs-8">ID: {{ $booking->nurse->id }}</span>
                                    <a href="{{ route('admin.nurses.show', $booking->nurse->user->id) }}" class="text-primary fs-9 fw-bold mt-1 d-flex align-items-center">View Profile <i class="ki-outline ki-arrow-right fs-9 text-primary ms-1"></i></a>
                                </div>
                            </div>
                            @if($booking->bid && $booking->bid->distance_km)
                                <div class="mt-3 text-gray-700 fs-8 d-flex align-items-center">
                                    <i class="ki-outline ki-route fs-7 text-gray-600 me-2"></i>
                                    Distance: <span class="fw-bold ms-1">{{ $booking->bid->distance_km }} km</span>
                                </div>
                            @endif
                        @else
                            <span class="text-gray-600 fs-7 d-flex align-items-center"><i class="ki-outline ki-minus-circle fs-3 text-muted me-2"></i> No Nurse Assigned</span>
                        @endif
                    </div>

                    {{-- Service Location --}}
                    <div class="col-md-3 col-sm-6 ps-md-4 border-start border-gray-200">
                        <span class="text-gray-500 text-uppercase fw-bold fs-9 mb-2 d-block">Service Location</span>
                        <div class="d-flex align-items-start">
                            <i class="ki-outline ki-geolocation fs-2 text-danger me-2 mt-1"></i>
                            <div class="d-flex flex-column">
                                <span class="text-gray-900 fw-semibold fs-7" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $booking->address ?? ($booking->careRequest->address ?? 'N/A') }}
                                </span>
                                <span class="text-gray-500 fs-8">{{ $booking->city ?? ($booking->careRequest->city ?? '') }} - {{ $booking->pincode ?? ($booking->careRequest->pincode ?? '') }}</span>
                            </div>
                        </div>
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
                            <div class="card shadow-sm mb-5 h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Financial & Rates</h3>
                                </div>
                                <div class="card-body pt-4 pb-4">
                                    <div class="row g-4 mb-5">
                                        <div class="col-sm-6">
                                            <div class="bg-light-primary rounded p-4">
                                                <span class="text-primary text-uppercase fw-bold d-block fs-9 mb-1">Total Amount</span>
                                                <span class="fw-bold fs-3 text-gray-900">₹{{ number_format($booking->total_amount, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="bg-light-success rounded p-4">
                                                <span class="text-success text-uppercase fw-bold d-block fs-9 mb-1">Earned Commission</span>
                                                <span class="fw-bold fs-3 text-gray-900">₹{{ number_format($booking->commission_amount, 2) }}</span>
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
                            <div class="card shadow-sm mb-5 h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Schedule & Service</h3>
                                </div>
                                <div class="card-body pt-4 pb-4">
                                    <div class="d-flex flex-wrap gap-4 mb-5">
                                        <div class="bg-light rounded py-3 px-4 flex-grow-1">
                                            <div class="fs-6 fw-bold text-gray-900">{{ $booking->careRequest->careType->name ?? 'N/A' }}</div>
                                            <div class="fw-semibold fs-8 text-gray-500 mt-1">Care Type</div>
                                        </div>
                                        <div class="bg-light rounded py-3 px-4 flex-grow-1">
                                            <div class="fs-6 fw-bold text-gray-900">{{ $booking->completed_sessions }} / {{ $booking->total_sessions }}</div>
                                            <div class="fw-semibold fs-8 text-gray-500 mt-1">Sessions Completed</div>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="bg-light-info rounded p-4">
                                                <span class="text-info text-uppercase fw-bold d-block fs-9 mb-1">Start Date & Time</span>
                                                <span class="fw-bold fs-5 text-gray-900 d-block">{{ $booking->start_date ? $booking->start_date->format('d M Y') : 'N/A' }}</span>
                                                <span class="text-gray-600 fw-bold fs-8 mt-1 d-block">{{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="bg-light-warning rounded p-4">
                                                <span class="text-warning text-uppercase fw-bold d-block fs-9 mb-1">End Date & Time</span>
                                                <span class="fw-bold fs-5 text-gray-900 d-block">{{ $booking->end_date ? $booking->end_date->format('d M Y') : 'N/A' }}</span>
                                                <span class="text-gray-600 fw-bold fs-8 mt-1 d-block">{{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payments Details & Gateway Info --}}
                        <div class="col-xl-6">
                            <div class="card shadow-sm mb-5 h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Payment Details</h3>
                                </div>
                                <div class="card-body pt-4 pb-4">
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
                            <div class="card shadow-sm mb-5 h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Selected Bid & Metadata</h3>
                                </div>
                                <div class="card-body pt-4 pb-4">
                                    @if($booking->bid)
                                        @php $bid = $booking->bid; @endphp
                                        <div class="d-flex flex-wrap gap-3 mb-4">
                                            <div class="bg-light-primary rounded py-3 px-4 flex-grow-1">
                                                <div class="fs-4 fw-bold text-gray-900">₹{{ number_format($bid->nurse_amount, 2) }}</div>
                                                <div class="fw-semibold fs-8 text-primary mt-1">Nurse Amount</div>
                                            </div>
                                            <div class="bg-light-success rounded py-3 px-4 flex-grow-1">
                                                <div class="fs-4 fw-bold text-gray-900">₹{{ number_format($bid->total_amount, 2) }}</div>
                                                <div class="fw-semibold fs-8 text-success mt-1">Total Bid Amount</div>
                                            </div>
                                        </div>
                                        @if($bid->notes)
                                            <div class="bg-light rounded p-4 mb-4">
                                                <span class="fw-bold text-gray-700 fs-8 d-block mb-1">Nurse Notes</span>
                                                <p class="text-gray-900 fw-medium fs-7 mb-0">{{ $bid->notes }}</p>
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

                                    @if($booking->careRequest || $booking->parentBooking || $booking->extensions->count() > 0)
                                        <div class="separator separator-dashed border-gray-200 my-4"></div>
                                        <h4 class="fs-6 fw-bold text-gray-800 mb-3">Linked References</h4>
                                        @if($booking->careRequest)
                                            <div class="d-flex flex-stack mb-2">
                                                <span class="text-gray-500 fw-bold fs-8">Linked Request</span>
                                                <a href="{{ route('admin.requests.show', $booking->care_request_id) }}" class="fw-bold fs-7 text-primary text-hover-primary">#{{ $booking->careRequest->reference_id ?? $booking->care_request_id }}</a>
                                            </div>
                                        @endif
                                        @if($booking->parentBooking)
                                            <div class="d-flex flex-stack mb-2">
                                                <span class="text-gray-500 fw-bold fs-8">Parent Booking</span>
                                                <a href="{{ route('admin.bookings.show', $booking->parent_booking_id) }}" class="fw-bold fs-7 text-warning text-hover-primary">#{{ $booking->parentBooking->reference_id }}</a>
                                            </div>
                                        @endif
                                        @if($booking->extensions->count() > 0)
                                            <div class="d-flex flex-stack mb-2">
                                                <span class="text-gray-500 fw-bold fs-8">Extensions</span>
                                                <div class="d-flex flex-wrap gap-1 justify-content-end">
                                                    @foreach($booking->extensions as $ext)
                                                        <a href="{{ route('admin.bookings.show', $ext->id) }}" class="badge badge-light-primary fs-8 text-hover-primary">#{{ $ext->reference_id }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif
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
