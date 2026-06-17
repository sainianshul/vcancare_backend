@extends('admin.layouts.app')

@section('title', 'Care Request Details')

@section('content')

    <x-breadcrumb :items="[
            ['label' => 'Care Requests', 'url' => route('admin.requests.index')],
            ['label' => 'View Request: ' . $careRequest->reference_id],
        ]" />

    <div class="d-flex flex-column gap-7 gap-lg-10">

        <x-alert-success />
        <x-form-errors />

        {{-- ── HEADER ───────────────────────────────────────────────────────── --}}
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.requests.index') }}" class="btn btn-icon btn-light btn-active-secondary btn-sm">
                    <i class="ki-outline ki-arrow-left fs-4"></i>
                </a>
                <h1 class="fw-bold text-gray-900 fs-4 mb-0">
                    Request <span class="text-primary">#{{ $careRequest->reference_id ?? 'N/A' }}</span>
                </h1>

                <span class="badge badge-light-{{ $careRequest->status_color }} fs-8 px-3 py-1 border border-{{ $careRequest->status_color }}">
                    {{ $careRequest->status_text ?? 'Unknown' }}
                </span>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-light-primary btn-sm fw-bold fs-8 px-4 py-2 border border-primary">
                    <i class="ki-outline ki-sms fs-4"></i> Contact User
                </button>
            </div>
        </div>

        {{-- ── INFO CARDS (ABOVE TABS) ──────────────────────────────────────── --}}
        <div class="row g-5 g-xl-8">
            
            {{-- Account Owner Card --}}
            <div class="col-xl-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                        <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Account Owner</h3>
                    </div>
                    <div class="card-body pt-4 pb-4">
                        @if(!empty($careRequest->user))
                            <div class="d-flex align-items-center mb-4">
                                <div class="symbol symbol-40px symbol-circle me-3">
                                    <span class="symbol-label bg-light-primary text-primary fs-6 fw-bold">
                                        {{ mb_strtoupper(mb_substr($careRequest->user->name ?? 'U', 0, 2)) }}
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('admin.patients.show', $careRequest->user->id ?? 0) }}" class="fs-6 text-gray-900 text-hover-primary fw-bold">{{ $careRequest->user->name ?? 'Unknown' }}</a>
                                    <span class="text-gray-500 fs-8">{{ $careRequest->user->phone ?? $careRequest->user->email ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge badge-light-success fw-bold px-2 py-1 fs-8">Active Member</span>
                                <span class="badge badge-light fw-bold px-2 py-1 fs-8 text-gray-700">ID: {{ $careRequest->user->id ?? 'N/A' }}</span>
                            </div>
                            <a href="{{ route('admin.patients.show', $careRequest->user->id ?? 0) }}" class="text-primary fw-bold fs-8 d-flex align-items-center">
                                View Profile <i class="ki-outline ki-arrow-right fs-7 ms-1 text-primary"></i>
                            </a>
                        @else
                            <div class="text-center text-gray-600 fs-7 mt-5">Unknown User</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Patient Details Card --}}
            <div class="col-xl-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                        <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Patient Details</h3>
                    </div>
                    <div class="card-body pt-4 pb-4">
                        <div class="d-flex flex-stack mb-4">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-info text-info">
                                        <i class="ki-outline ki-user fs-3 text-info"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">{{ $careRequest->patient_name ?? 'N/A' }}</span>
                                    <span class="mt-1">
                                        @if($careRequest->care_for === \App\Models\CareRequest::CARE_FOR_SELF)
                                            <span class="badge badge-light-info fs-9 px-2 py-1">Self Care</span>
                                        @else
                                            <span class="badge badge-light-warning fs-9 px-2 py-1">Family Member</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex flex-stack border-bottom border-gray-100 pb-2">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Patient Age</span>
                                <span class="text-gray-900 fw-bold fs-8">{{ $careRequest->patient_age ?? 'N/A' }} years</span>
                            </div>
                            <div class="d-flex flex-stack border-bottom border-gray-100 pb-2 pt-2">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Contact Phone</span>
                                <span class="text-gray-900 fw-bold fs-8">{{ $careRequest->contact_phone ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex flex-stack pt-2">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Secondary Phone</span>
                                <span class="text-gray-900 fw-bold fs-8">{{ $careRequest->secondary_phone ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Location Card --}}
            <div class="col-xl-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                        <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Location</h3>
                    </div>
                    <div class="card-body pt-4 pb-4">
                        <div class="d-flex align-items-start mb-3">
                            <span class="bullet bullet-vertical h-30px bg-primary me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">Full Address</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $careRequest->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <span class="bullet bullet-vertical h-30px bg-info me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">City & State</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $careRequest->city ?? 'N/A' }}, {{ $careRequest->state ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="bullet bullet-vertical h-30px bg-success me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">Pincode</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $careRequest->pincode ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── FULL WIDTH TABS ────────────────────────────────────────────────── --}}
        <div>
            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold mb-5" id="request-tabs">
                <li class="nav-item">
                    <a class="nav-link active text-gray-600 text-active-primary px-4 py-3" data-bs-toggle="tab" href="#tab-overview">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-gray-600 text-active-primary px-4 py-3" data-bs-toggle="tab" href="#tab-bids">Bids List</a>
                </li>
                @if(in_array($careRequest->status, [\App\Models\CareRequest::STATUS_PENDING, \App\Models\CareRequest::STATUS_MATCHING, \App\Models\CareRequest::STATUS_FAILED_NO_BIDS]))
                <li class="nav-item">
                    <a class="nav-link text-gray-600 text-active-primary px-4 py-3" data-bs-toggle="tab" href="#tab-notified">Notified Nurses</a>
                </li>
                @endif
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content" id="request-tabs-content">

                {{-- ── Overview Tab ──────────────────────────────────────── --}}
                <div class="tab-pane fade show active" id="tab-overview">
                    <div class="row g-5 g-xl-8">

                        {{-- Service & Timing --}}
                        <div class="col-xl-6">
                            <div class="card shadow-sm mb-5 h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Service & Timing</h3>
                                </div>
                                <div class="card-body pt-4 pb-4">
                                    <div class="d-flex flex-wrap gap-4 mb-5">
                                        <div class="bg-light rounded py-3 px-4 flex-grow-1 text-center">
                                            <div class="fs-6 fw-bold text-gray-900">{{ $careRequest->careType->name ?? 'Unknown' }}</div>
                                            <div class="fw-semibold fs-8 text-gray-500 mt-1">Care Type</div>
                                        </div>

                                        @php
                                            $days = 1;
                                            if ($careRequest->start_date && $careRequest->end_date) {
                                                $days = $careRequest->start_date->diffInDays($careRequest->end_date) + 1;
                                            }
                                        @endphp
                                        <div class="bg-light rounded py-3 px-4 flex-grow-1 text-center">
                                            <div class="fs-6 fw-bold text-gray-900">{{ $days }} Day{{ $days > 1 ? 's' : '' }}</div>
                                            <div class="fw-semibold fs-8 text-gray-500 mt-1">Duration</div>
                                        </div>

                                        @php
                                            $hours = 0;
                                            if ($careRequest->start_time && $careRequest->end_time) {
                                                $start = \Carbon\Carbon::parse($careRequest->start_time);
                                                $end = \Carbon\Carbon::parse($careRequest->end_time);
                                                if ($end->lessThan($start)) {
                                                    $end->addDay();
                                                }
                                                $hours = $start->diffInHours($end);
                                            }
                                        @endphp
                                        <div class="bg-light rounded py-3 px-4 flex-grow-1 text-center">
                                            <div class="fs-6 fw-bold text-gray-900">{{ $hours }} Hour{{ $hours > 1 ? 's' : '' }}/Day</div>
                                            <div class="fw-semibold fs-8 text-gray-500 mt-1">Daily Shift</div>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="bg-light-info rounded p-4">
                                                <span class="text-info text-uppercase fw-bold d-block fs-9 mb-1">Start Date & Time</span>
                                                <span class="fw-bold fs-5 text-gray-900 d-block">{{ $careRequest->start_date ? $careRequest->start_date->format('d M Y') : 'N/A' }}</span>
                                                <span class="text-gray-600 fw-bold fs-8 mt-1 d-block">{{ $careRequest->start_time ? \Carbon\Carbon::parse($careRequest->start_time)->format('h:i A') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="bg-light-warning rounded p-4">
                                                <span class="text-warning text-uppercase fw-bold d-block fs-9 mb-1">End Date & Time</span>
                                                <span class="fw-bold fs-5 text-gray-900 d-block">{{ $careRequest->end_date ? $careRequest->end_date->format('d M Y') : 'N/A' }}</span>
                                                <span class="text-gray-600 fw-bold fs-8 mt-1 d-block">{{ $careRequest->end_time ? \Carbon\Carbon::parse($careRequest->end_time)->format('h:i A') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Financials & Stats --}}
                        <div class="col-xl-6">
                            <div class="card shadow-sm mb-5 h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Financials & Bidding Stats</h3>
                                </div>
                                <div class="card-body pt-4 pb-4">
                                    <div class="d-flex flex-stack p-4 bg-light-success rounded mb-4">
                                        <div class="d-flex flex-column">
                                            <span class="text-success text-uppercase fw-bold fs-9">Platform Commission</span>
                                            <span class="text-gray-700 fs-8 mt-1">
                                                Type: 
                                                <strong>
                                                    @if($careRequest->commission_type === 1) Percentage
                                                    @elseif($careRequest->commission_type === 2) Flat Fixed
                                                    @elseif($careRequest->commission_type === 3) Fixed Per Day
                                                    @else Unknown @endif
                                                </strong>
                                            </span>
                                        </div>
                                        <span class="fs-3 fw-bold text-gray-900">
                                            @if($careRequest->commission_type === 1) {{ $careRequest->commission_value }}%
                                            @else ₹{{ number_format($careRequest->commission_value, 2) }} @endif
                                        </span>
                                    </div>

                                    <div class="d-flex flex-stack p-4 bg-light-primary rounded mb-5">
                                        <span class="text-primary text-uppercase fw-bold fs-9">Pre-authorized Tip</span>
                                        <span class="text-gray-900 fw-bold fs-3">₹{{ number_format($careRequest->tip_amount, 2) }}</span>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center bg-light rounded p-4">
                                                <i class="ki-outline ki-time fs-2 text-primary me-3"></i>
                                                <div class="flex-grow-1">
                                                    <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">Bidding Ends At</span>
                                                    <span class="fw-bold fs-6 text-gray-900">{{ $careRequest->bidding_ends_at ? $careRequest->bidding_ends_at->format('d M Y, h:i A') : 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-light rounded p-4 text-center">
                                                <span class="fs-4 fw-bold text-gray-900 d-block">{{ $careRequest->matching_attempt_level }}</span>
                                                <span class="fs-8 fw-semibold text-gray-500">Radius Level</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-light rounded p-4 text-center">
                                                <span class="fs-4 fw-bold text-gray-900 d-block">{{ $careRequest->total_bids_received }}</span>
                                                <span class="fs-8 fw-semibold text-gray-500">Total Bids</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($careRequest->notes)
                        <div class="card shadow-sm border border-gray-200 bg-white mb-5">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ki-outline ki-information fs-3 text-primary me-2"></i>
                                    <span class="fw-bold text-gray-900 fs-6">Patient Notes</span>
                                </div>
                                <p class="text-gray-800 fw-medium fs-7 mb-0">
                                    {{ $careRequest->notes }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Cancellation Info --}}
                    @if($careRequest->status === \App\Models\CareRequest::STATUS_CANCELLED)
                        <div class="card shadow-sm mb-5 bg-white border border-gray-200">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ki-outline ki-cross-circle fs-2 text-danger me-2"></i>
                                    <span class="fw-bold text-gray-900 fs-5">Cancellation Details</span>
                                </div>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="d-flex flex-stack border border-gray-200 rounded p-3 bg-white">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Cancelled By</span>
                                            <span class="fw-bold fs-7 text-gray-900">
                                                @switch($careRequest->cancelled_by)
                                                    @case(1) <span class="badge badge-light-primary border border-primary text-gray-700">User</span> @break
                                                    @case(2) <span class="badge badge-light-primary border border-primary text-gray-700">Nurse</span> @break
                                                    @case(3) <span class="badge badge-light-primary border border-primary text-gray-700">Admin</span> @break
                                                    @case(4) <span class="badge badge-light-primary border border-primary text-gray-700">System</span> @break
                                                    @default <span class="text-muted">Unknown</span>
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-stack border border-gray-200 rounded p-3 bg-white">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Cancelled At</span>
                                            <span class="fw-bold fs-7 text-gray-900">{{ $careRequest->updated_at ? $careRequest->updated_at->format('d M Y, h:i A') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($careRequest->cancel_reason)
                                    <div class="mt-4 border border-gray-200 rounded p-3 bg-white">
                                        <span class="text-gray-500 text-uppercase fw-bold fs-9 d-block mb-1">Reason</span>
                                        <span class="fw-semibold fs-7 text-gray-900">{{ $careRequest->cancel_reason }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>

                {{-- ── Bids Tab ──────────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-bids">
                    <div class="card shadow-sm border border-gray-200">
                        <div class="card-body">
                            @include('admin.request._bids_table')
                        </div>
                    </div>
                </div>

                {{-- ── Notified Nurses Tab ───────────────────────────────── --}}
                @if(in_array($careRequest->status, [\App\Models\CareRequest::STATUS_PENDING, \App\Models\CareRequest::STATUS_MATCHING, \App\Models\CareRequest::STATUS_FAILED_NO_BIDS]))
                <div class="tab-pane fade" id="tab-notified">
                    <div class="card shadow-sm border border-gray-200">
                        <div class="card-body">
                            @include('admin.request._notified_nurses_table')
                        </div>
                    </div>
                </div>
                @endif

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
                    <x-comments type="{{ \App\Models\Comment::TYPE_CARE_REQUEST }}" :model-id="$careRequest->id" />
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

            // Initialize DataTables when their tab is shown (lazy loading if necessary)
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href');
                if (target === '#tab-bids') {
                    // Check if bids-table needs to adjust columns
                    if($.fn.DataTable.isDataTable('#bids-table')){
                        $('#bids-table').DataTable().columns.adjust();
                    }
                } else if (target === '#tab-notified') {
                    // Check if notified_nurses_table needs to adjust columns
                    if($.fn.DataTable.isDataTable('#notified_nurses_table')){
                        $('#notified_nurses_table').DataTable().columns.adjust();
                    }
                }
            });

            // ── Search ───────────────────────────────────────────────────────
            let searchTimer;
            $('#bids-search').on('input', function () {
                clearTimeout(searchTimer);
                let query = $(this).val();
                searchTimer = setTimeout(function () {
                    // if table is defined globally in the included view
                    if(typeof table !== 'undefined') {
                        table.search(query).draw();
                    } else if($.fn.DataTable.isDataTable('#bids-table')) {
                        $('#bids-table').DataTable().search(query).draw();
                    }
                }, 400);
            });

        });
    </script>
@endpush
