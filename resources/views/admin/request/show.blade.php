@extends('admin.layouts.app')

@section('title', 'Care Request Details')

@section('content')

    <x-breadcrumb :items="[
            ['label' => 'Care Requests', 'url' => route('admin.requests.index')],
            ['label' => 'View Request: ' . $careRequest->reference_id],
        ]" />

    <div class="d-flex flex-column gap-7 gap-lg-10">

        {{-- ── HEADER ───────────────────────────────────────────────────────── --}}
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.requests.index') }}" class="btn btn-icon btn-light btn-active-secondary btn-sm">
                    <i class="ki-outline ki-arrow-left fs-4"></i>
                </a>
                <h1 class="fw-bold text-gray-900 fs-4 mb-0">
                    Request <span class="text-primary">#{{ $careRequest->reference_id ?? 'N/A' }}</span>
                </h1>

                @php
                    $statusColors = [
                        \App\Models\CareRequest::STATUS_PENDING => 'warning',
                        \App\Models\CareRequest::STATUS_COMPLETED => 'success',
                        \App\Models\CareRequest::STATUS_CANCELLED => 'danger',
                        \App\Models\CareRequest::STATUS_EXPIRED => 'secondary',
                        \App\Models\CareRequest::STATUS_MATCHING => 'primary',
                        \App\Models\CareRequest::STATUS_ACCEPTED => 'info',
                        \App\Models\CareRequest::STATUS_FAILED_NO_NURSES => 'danger',
                        \App\Models\CareRequest::STATUS_FAILED_NO_BIDS => 'danger',
                        \App\Models\CareRequest::STATUS_FAILED_UNACCEPTED => 'danger',
                    ];
                    $color = $statusColors[$careRequest->status ?? 0] ?? 'dark';
                @endphp
                <span class="badge badge-light-{{ $color }} fs-8 px-3 py-1 border border-{{ $color }}">
                    {{ $careRequest->status_text ?? 'Unknown' }}
                </span>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-light-primary btn-sm fw-bold fs-8 px-4 py-2">
                    <i class="ki-outline ki-sms fs-4"></i> Contact User
                </button>
            </div>
        </div>

        <div class="row g-7">
            {{-- ── LEFT COLUMN (Main Details) ─────────────────────────────────── --}}
            <div class="col-lg-8">

                {{-- Service & Timing Card --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Service & Timing</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex flex-wrap gap-4 mb-5">
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-heart fs-3 text-danger me-2"></i>
                                    <div class="fs-6 fw-bold text-gray-900">{{ $careRequest->careType->name ?? 'Unknown' }}</div>
                                </div>
                                <div class="fw-semibold fs-8 text-gray-600 mt-1">Care Type</div>
                            </div>

                            @php
                                $days = 1;
                                if ($careRequest->start_date && $careRequest->end_date) {
                                    $days = $careRequest->start_date->diffInDays($careRequest->end_date) + 1;
                                }
                            @endphp
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-calendar fs-3 text-primary me-2"></i>
                                    <div class="fs-6 fw-bold text-gray-900">{{ $days }} Day{{ $days > 1 ? 's' : '' }}</div>
                                </div>
                                <div class="fw-semibold fs-8 text-gray-600 mt-1">Duration</div>
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
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-time fs-3 text-success me-2"></i>
                                    <div class="fs-6 fw-bold text-gray-900">{{ $hours }} Hour{{ $hours > 1 ? 's' : '' }}/Day</div>
                                </div>
                                <div class="fw-semibold fs-8 text-gray-600 mt-1">Daily Shift</div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="bg-light-primary rounded p-3 border border-primary border-dashed">
                                    <span class="text-primary fw-semibold d-block fs-8 mb-1">Start Date & Time</span>
                                    <span class="fw-bold fs-6 text-gray-900">{{ $careRequest->start_date ? $careRequest->start_date->format('d M Y') : 'N/A' }}</span>
                                    <span class="text-gray-700 fw-semibold fs-7 ms-2">{{ $careRequest->start_time ? \Carbon\Carbon::parse($careRequest->start_time)->format('h:i A') : 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bg-light-danger rounded p-3 border border-danger border-dashed">
                                    <span class="text-danger fw-semibold d-block fs-8 mb-1">End Date & Time</span>
                                    <span class="fw-bold fs-6 text-gray-900">{{ $careRequest->end_date ? $careRequest->end_date->format('d M Y') : 'N/A' }}</span>
                                    <span class="text-gray-700 fw-semibold fs-7 ms-2">{{ $careRequest->end_time ? \Carbon\Carbon::parse($careRequest->end_time)->format('h:i A') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bidding Engine Stats (Left side only now) --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Bidding Engine Stats</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex align-items-center bg-light-warning border border-warning border-dashed rounded p-3 mb-4">
                            <i class="ki-outline ki-time fs-2 text-warning me-3"></i>
                            <div class="flex-grow-1">
                                <span class="text-warning fw-semibold d-block fs-8">Bidding Ends At</span>
                                <span class="fw-bold fs-6 text-gray-900">{{ $careRequest->bidding_ends_at ? $careRequest->bidding_ends_at->format('d M Y, h:i A') : 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="border border-gray-300 border-dashed rounded p-3 text-center bg-light">
                                    <span class="fs-4 fw-bold text-gray-900 d-block">{{ $careRequest->matching_attempt_level }}</span>
                                    <span class="fs-8 fw-semibold text-gray-600">Radius Level</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border border-gray-300 border-dashed rounded p-3 text-center bg-light">
                                    <span class="fs-4 fw-bold text-gray-900 d-block">{{ $careRequest->total_bids_received }}</span>
                                    <span class="fs-8 fw-semibold text-gray-600">Total Bids</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bids Table --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-5 mb-0">Received Bids</span>
                        </h3>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative">
                                <i class="ki-outline ki-magnifier fs-6 text-gray-600 position-absolute ms-3 z-index-3"></i>
                                <input type="text" id="bids-search"
                                    class="form-control form-control-sm form-control-solid border border-gray-300 text-gray-900 w-200px ps-9 fw-semibold fs-8"
                                    placeholder="Search bids...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="table-responsive">
                            <table id="bids-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                        <th class="ps-3 min-w-150px rounded-start">Nurse</th>
                                        <th class="min-w-100px">Nurse Amt</th>
                                        <th class="min-w-100px">Comm.</th>
                                        <th class="min-w-100px">Total</th>
                                        <th class="min-w-100px">Status</th>
                                        <th class="text-end pe-3 rounded-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Notified Nurses Table --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-5 mb-0">Notified Nurses</span>
                        </h3>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative">
                                <i class="ki-outline ki-magnifier fs-6 text-gray-600 position-absolute ms-3 z-index-3"></i>
                                <input type="text" id="nurses-search"
                                    class="form-control form-control-sm form-control-solid border border-gray-300 text-gray-900 w-200px ps-9 fw-semibold fs-8"
                                    placeholder="Search nurses...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="table-responsive">
                            <table id="notified-nurses-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                        <th class="ps-3 min-w-150px rounded-start">Nurse</th>
                                        <th class="min-w-100px">Dist.</th>
                                        <th class="min-w-100px">Notified At</th>
                                        <th class="min-w-100px">Status</th>
                                        <th class="text-end pe-3 rounded-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Comments Component --}}
                <x-comments type="{{ \App\Models\CareRequest::class }}" :model-id="$careRequest->id" />

            </div>

            {{-- ── RIGHT COLUMN (Patient & Metadata) ────────────────────────── --}}
            <div class="col-lg-4">

                {{-- Account Owner Card --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Account Owner</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        @if(!empty($careRequest->user))
                            <div class="d-flex flex-center flex-column mb-4">
                                <div class="symbol symbol-60px symbol-circle mb-3">
                                    <span class="symbol-label bg-light-primary text-primary fs-3 fw-bold border border-primary">
                                        {{ mb_strtoupper(mb_substr($careRequest->user->name ?? 'U', 0, 2)) }}
                                    </span>
                                </div>
                                <a href="{{ route('admin.patients.show', $careRequest->user->id ?? 0) }}"
                                    class="fs-6 text-gray-900 text-hover-primary fw-bold mb-1">
                                    {{ $careRequest->user->name ?? 'Unknown' }}
                                </a>
                                <div class="fs-8 fw-semibold text-gray-600 mb-2">ID: {{ $careRequest->user->id ?? 'N/A' }}</div>
                                <div class="badge badge-light-success border border-success fw-bold px-3 py-1 mt-1 fs-8">Active Member</div>
                            </div>

                            <div class="separator separator-dashed border-gray-300 my-4"></div>

                            <div class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-sms fs-4 text-primary me-2"></i>
                                <div class="fs-7 text-gray-800 fw-semibold">
                                    {{ $careRequest->user->phone ?? $careRequest->user->email ?? 'N/A' }}
                                </div>
                            </div>

                            <a href="{{ route('admin.patients.show', $careRequest->user->id ?? 0) }}"
                                class="btn btn-light-primary border border-primary btn-sm w-100 fw-bold fs-8 px-3 py-2">
                                Go to Profile <i class="ki-outline ki-arrow-right fs-6 ms-1"></i>
                            </a>
                        @else
                            <div class="text-center text-gray-600 fs-7">Unknown User</div>
                        @endif
                    </div>
                </div>

                {{-- Patient Details Card --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Patient Details</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex flex-stack mb-4">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px me-3">
                                    <div class="symbol-label bg-light-info text-info border border-info">
                                        <i class="ki-outline ki-user fs-4"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">{{ $careRequest->patient_name ?? 'N/A' }}</span>
                                    <span class="mt-1">
                                        @if($careRequest->care_for === \App\Models\CareRequest::CARE_FOR_SELF)
                                            <span class="badge badge-light-primary border border-primary fs-9 px-2 py-1">Self Care</span>
                                        @else
                                            <span class="badge badge-light-info border border-info fs-9 px-2 py-1">Family Member</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-3 mt-4">
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Patient Age</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $careRequest->patient_age ?? 'N/A' }} years</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Contact Phone</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $careRequest->contact_phone ?? 'N/A' }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Secondary Phone</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $careRequest->secondary_phone ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Location Card --}}
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
                                <span class="fw-bold fs-7 text-gray-900">{{ $careRequest->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <span class="bullet bullet-vertical h-30px bg-primary me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-8">City & State</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $careRequest->city ?? 'N/A' }}, {{ $careRequest->state ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="bullet bullet-vertical h-30px bg-warning me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-8">Pincode</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $careRequest->pincode ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Financials --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Financials</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="bg-light-success border border-success border-dashed rounded p-3 mb-3">
                            <div class="d-flex flex-stack mb-1">
                                <span class="text-success fw-semibold fs-7">Platform Commission</span>
                                <span class="fs-6 fw-bold text-gray-900">
                                    @if($careRequest->commission_type === 1)
                                        {{ $careRequest->commission_value }}%
                                    @else
                                        ₹{{ number_format($careRequest->commission_value, 2) }}
                                    @endif
                                </span>
                            </div>
                            <span class="text-success fs-8 d-block">
                                Applied Rule: 
                                <strong>
                                    @if($careRequest->commission_type === 1)
                                        Percentage
                                    @elseif($careRequest->commission_type === 2)
                                        Flat Fixed
                                    @elseif($careRequest->commission_type === 3)
                                        Fixed Per Day
                                    @else
                                        Unknown
                                    @endif
                                </strong>
                            </span>
                        </div>

                        <div class="d-flex flex-stack p-3 bg-light border border-gray-200 rounded">
                            <span class="text-gray-700 fw-semibold fs-7">Pre-authorized Tip</span>
                            <span class="text-primary fw-bold fs-6">₹{{ number_format($careRequest->tip_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                @if($careRequest->notes)
                    <div class="card shadow-sm bg-light-info border border-info border-dashed">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ki-outline ki-information fs-3 text-info me-2"></i>
                                <span class="fw-bold text-gray-900 fs-6">Patient Notes</span>
                            </div>
                            <p class="text-gray-800 fw-medium fs-7 mb-0">
                                {{ $careRequest->notes }}
                            </p>
                        </div>
                    </div>
                @endif

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
            let table = $('#bids-table').DataTable({
                serverSide: true,
                processing: false,
                ajax: {
                    url: '{{ route('admin.requests.bids-data', $careRequest->id) }}'
                },
                columns: [
                    { data: 'nurse', name: 'nurse', orderable: false, searchable: false, className: 'ps-3' },
                    { data: 'nurse_amount', name: 'nurse_amount' },
                    { data: 'commission_amount', name: 'commission_amount' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'status', name: 'status' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-3' },
                ],
                order: [[3, 'asc']], // Order by total amount ascending initially
                pageLength: 5,
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                dom:
                    "<'row'<'col-12'tr>>" +
                    "<'row align-items-center mt-3 pt-3 flex-nowrap'" +
                    "<'col-sm-12 col-md-5 fs-8 text-gray-600 fw-semibold'i>" +
                    "<'col-sm-12 col-md-7 d-flex justify-content-md-end align-items-center gap-2'lp>>",
                language: {
                    emptyTable: '<span class="text-gray-500 fs-7">No bids received yet.</span>',
                    zeroRecords: '<span class="text-gray-500 fs-7">No matching bids found.</span>',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    lengthMenu: '_MENU_',
                    paginate: {
                        previous: '<i class="ki-outline ki-arrow-left fs-8"></i>',
                        next: '<i class="ki-outline ki-arrow-right fs-8"></i>',
                    },
                }
            });

            // ── Search ───────────────────────────────────────────────────────
            let searchTimer;
            $('#bids-search').on('input', function () {
                clearTimeout(searchTimer);
                let query = $(this).val();
                searchTimer = setTimeout(function () {
                    table.search(query).draw();
                }, 400);
            });

            // ── Notified Nurses DataTable ─────────────────────────────────────
            let nursesTable = $('#notified-nurses-table').DataTable({
                serverSide: true,
                processing: false,
                ajax: {
                    url: '{{ route('admin.requests.notified-nurses-data', $careRequest->id) }}'
                },
                columns: [
                    { data: 'nurse', name: 'nurse', orderable: false, searchable: false, className: 'ps-3' },
                    { data: 'distance', name: 'distance', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-3' },
                ],
                order: [[2, 'desc']], // Order by notified at descending initially
                pageLength: 5,
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                dom:
                    "<'row'<'col-12'tr>>" +
                    "<'row align-items-center mt-3 pt-3 flex-nowrap'" +
                    "<'col-sm-12 col-md-5 fs-8 text-gray-600 fw-semibold'i>" +
                    "<'col-sm-12 col-md-7 d-flex justify-content-md-end align-items-center gap-2'lp>>",
                language: {
                    emptyTable: '<span class="text-gray-500 fs-7">No nurses notified yet.</span>',
                    zeroRecords: '<span class="text-gray-500 fs-7">No matching nurses found.</span>',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    lengthMenu: '_MENU_',
                    paginate: {
                        previous: '<i class="ki-outline ki-arrow-left fs-8"></i>',
                        next: '<i class="ki-outline ki-arrow-right fs-8"></i>',
                    },
                }
            });

            // ── Search Notified Nurses ───────────────────────────────────────
            let nursesSearchTimer;
            $('#nurses-search').on('input', function () {
                clearTimeout(nursesSearchTimer);
                let query = $(this).val();
                nursesSearchTimer = setTimeout(function () {
                    nursesTable.search(query).draw();
                }, 400);
            });
        });
    </script>
@endpush