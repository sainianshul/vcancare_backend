@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Dashboard" description="Welcome back, {{ auth()->user()->name }}" />
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge-light border border-gray-300 text-gray-700 fw-medium px-3 py-2 fs-8">
                    <i class="ki-outline ki-calendar fs-7 me-1"></i> {{ now()->format('l, d M Y') }}
                </span>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!--begin::Alert Containers (existing AJAX alerts)-->
            <div id="pending-errors-container"></div>
            <div id="pending-nurses-container"></div>
            <div id="pending-tickets-container"></div>
            <!--end::Alert Containers-->

            <!--begin::Row 1 — Overview Stat Cards-->
            <style>
                /* Modern SaaS Stats Styles */
                .saas-card {
                    border: 1px solid var(--bs-border-color);
                    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
                    transition: all 0.2s ease;
                    border-radius: 0.5rem;
                    background-color: var(--bs-card-bg);
                }
                .saas-card:hover {
                    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                    border-color: var(--bs-gray-400);
                }
                [data-bs-theme="dark"] .saas-card:hover {
                    border-color: var(--bs-gray-600);
                }
                .saas-card-primary:hover { border-color: var(--bs-primary); }
                .saas-card-success:hover { border-color: var(--bs-success); }
                .saas-card-warning:hover { border-color: var(--bs-warning); }
                .saas-card-info:hover { border-color: var(--bs-info); }
                
                
                .saas-today-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                    gap: 1rem;
                }
                .saas-today-item {
                    border: 1px solid var(--bs-border-color);
                    background-color: var(--bs-card-bg);
                    border-radius: 0.5rem;
                    padding: 1.25rem 1rem;
                    text-align: center;
                    transition: border-color 0.2s;
                }
                .saas-today-item:hover {
                    border-color: var(--bs-gray-400);
                    background-color: var(--bs-light);
                }
                [data-bs-theme="dark"] .saas-today-item:hover {
                    border-color: var(--bs-gray-600);
                }
                .saas-icon-box {
                    width: 32px;
                    height: 32px;
                    border-radius: 6px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    margin-bottom: 0.75rem;
                }
            </style>

            <div class="row g-5 mb-7" id="overview-stats">
                <!--begin::Patients-->
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('admin.patients.index') }}" class="card saas-card saas-card-primary h-100 text-decoration-none">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="fs-6 fw-medium text-gray-600">Total Patients</span>
                                <i class="ki-outline ki-people fs-3 text-primary"></i>
                            </div>
                            <div class="fs-2hx fw-bold text-gray-900" id="stat-total-patients">
                                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end::Patients-->

                <!--begin::Nurses-->
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('admin.nurses.index') }}" class="card saas-card saas-card-success h-100 text-decoration-none">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="fs-6 fw-medium text-gray-600">Total Nurses</span>
                                <i class="ki-outline ki-shield-tick fs-3 text-success"></i>
                            </div>
                            <div class="fs-2hx fw-bold text-gray-900" id="stat-total-nurses">
                                <span class="spinner-border spinner-border-sm text-success" role="status"></span>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end::Nurses-->

                <!--begin::Requests-->
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('admin.requests.index') }}" class="card saas-card saas-card-warning h-100 text-decoration-none">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="fs-6 fw-medium text-gray-600">Total Requests</span>
                                <i class="ki-outline ki-document fs-3 text-warning"></i>
                            </div>
                            <div class="fs-2hx fw-bold text-gray-900" id="stat-total-requests">
                                <span class="spinner-border spinner-border-sm text-warning" role="status"></span>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end::Requests-->

                <!--begin::Bookings-->
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('admin.bookings.index') }}" class="card saas-card saas-card-info h-100 text-decoration-none">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="fs-6 fw-medium text-gray-600">Total Bookings</span>
                                <i class="ki-outline ki-calendar-tick fs-3 text-info"></i>
                            </div>
                            <div class="fs-2hx fw-bold text-gray-900" id="stat-total-bookings">
                                <span class="spinner-border spinner-border-sm text-info" role="status"></span>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end::Bookings-->
            </div>
            <!--end::Row 1-->

            <!--begin::Row 2 — Revenue Cards-->
            <div class="row g-5 mb-7" id="revenue-stats">
                <!--begin::Total Revenue-->
                <div class="col-sm-6 col-lg-4 col-xl-4">
                    <div class="card saas-card h-100">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="fs-6 fw-medium text-gray-600">Total Revenue</span>
                                <i class="ki-outline ki-wallet fs-3 text-success"></i>
                            </div>
                            <div class="fs-2hx fw-bold text-gray-900 mb-2" id="stat-total-revenue">
                                <span class="spinner-border spinner-border-sm text-success" role="status"></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-light-success fw-bold px-2 py-1 fs-8">Lifetime Earnings</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Total Revenue-->

                <!--begin::This Month-->
                <div class="col-sm-6 col-lg-4 col-xl-4">
                    <div class="card saas-card h-100">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="fs-6 fw-medium text-gray-600">This Month</span>
                                <i class="ki-outline ki-chart-simple fs-3 text-primary"></i>
                            </div>
                            <div class="fs-2hx fw-bold text-gray-900 mb-2" id="stat-month-revenue">
                                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-light-primary fw-bold px-2 py-1 fs-8">{{ now()->format('F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::This Month-->

                <!--begin::Today-->
                <div class="col-sm-6 col-lg-4 col-xl-4">
                    <div class="card saas-card h-100">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="fs-6 fw-medium text-gray-600">Today's Revenue</span>
                                <i class="ki-outline ki-sun fs-3 text-warning"></i>
                            </div>
                            <div class="fs-2hx fw-bold text-gray-900 mb-2" id="stat-today-revenue">
                                <span class="spinner-border spinner-border-sm text-warning" role="status"></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-light-warning fw-bold px-2 py-1 fs-8">{{ now()->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Today-->
            </div>
            <!--end::Row 2-->

            <!--begin::Row 3 — Today's Activity-->
            <div class="card saas-card mb-7">
                <div class="card-header border-bottom border-gray-200 pt-5 min-h-50px">
                    <h3 class="card-title fw-medium text-gray-900 fs-5 mb-0">
                        Today's Activity
                    </h3>
                    <div class="card-toolbar">
                        <span class="text-gray-500 fs-7">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="card-body py-6">
                    <div class="saas-today-grid" id="today-stats">
                        <!--begin::Requests-->
                        <div class="saas-today-item">
                            <div class="saas-icon-box bg-light-primary">
                                <i class="ki-outline ki-document fs-4 text-primary"></i>
                            </div>
                            <div class="fs-2 fw-bold text-gray-900 mb-1" id="stat-today-requests">
                                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                            </div>
                            <div class="fs-8 fw-medium text-gray-500">Requests</div>
                        </div>
                        <!--end::Requests-->
                        
                        <!--begin::Bookings-->
                        <div class="saas-today-item">
                            <div class="saas-icon-box bg-light-info">
                                <i class="ki-outline ki-calendar-tick fs-4 text-info"></i>
                            </div>
                            <div class="fs-2 fw-bold text-gray-900 mb-1" id="stat-today-bookings">
                                <span class="spinner-border spinner-border-sm text-info" role="status"></span>
                            </div>
                            <div class="fs-8 fw-medium text-gray-500">Bookings</div>
                        </div>
                        <!--end::Bookings-->
                        
                        <!--begin::Bids-->
                        <div class="saas-today-item">
                            <div class="saas-icon-box bg-light-success">
                                <i class="ki-outline ki-price-tag fs-4 text-success"></i>
                            </div>
                            <div class="fs-2 fw-bold text-gray-900 mb-1" id="stat-today-bids">
                                <span class="spinner-border spinner-border-sm text-success" role="status"></span>
                            </div>
                            <div class="fs-8 fw-medium text-gray-500">Bids</div>
                        </div>
                        <!--end::Bids-->
                        
                        <!--begin::Reviews-->
                        <div class="saas-today-item">
                            <div class="saas-icon-box bg-light-warning">
                                <i class="ki-outline ki-star fs-4 text-warning"></i>
                            </div>
                            <div class="fs-2 fw-bold text-gray-900 mb-1" id="stat-today-reviews">
                                <span class="spinner-border spinner-border-sm text-warning" role="status"></span>
                            </div>
                            <div class="fs-8 fw-medium text-gray-500">Reviews</div>
                        </div>
                        <!--end::Reviews-->
                        
                        <!--begin::Logins-->
                        <div class="saas-today-item">
                            <div class="saas-icon-box bg-light-danger">
                                <i class="ki-outline ki-entrance-left fs-4 text-danger"></i>
                            </div>
                            <div class="fs-2 fw-bold text-gray-900 mb-1" id="stat-today-logins">
                                <span class="spinner-border spinner-border-sm text-danger" role="status"></span>
                            </div>
                            <div class="fs-8 fw-medium text-gray-500">Logins</div>
                        </div>
                        <!--end::Logins-->
                    </div>
                </div>
            </div>
            <!--end::Row 3-->

            <!--begin::Row 4 — Recent Tables-->
            <div class="row g-5 mb-7">
                <!--begin::Recent Bookings-->
                <div class="col-xl-6">
                    <div class="card saas-card h-100">
                        <div class="card-header border-bottom pt-5 min-h-50px">
                            <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">
                                <i class="ki-outline ki-calendar-tick fs-4 text-info me-2"></i>Recent Bookings
                            </h3>
                            <div class="card-toolbar">
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light-primary border border-primary fw-bold fs-8 px-3 py-1">View All</a>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="recent-bookings-skeleton">
                                @include('admin.layouts.partials._table-skeleton', ['id' => 'rb-skeleton'])
                            </div>
                            <div class="d-none" id="recent-bookings-content">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3">
                                        <thead>
                                            <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                                <th class="ps-3 rounded-start min-w-80px">Ref</th>
                                                <th class="min-w-100px">Patient</th>
                                                <th class="min-w-80px">Amount</th>
                                                <th class="min-w-80px">Status</th>
                                                <th class="rounded-end min-w-80px">When</th>
                                            </tr>
                                        </thead>
                                        <tbody id="recent-bookings-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Recent Bookings-->

                <!--begin::Recent Requests-->
                <div class="col-xl-6">
                    <div class="card saas-card h-100">
                        <div class="card-header border-bottom pt-5 min-h-50px">
                            <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">
                                <i class="ki-outline ki-document fs-4 text-warning me-2"></i>Recent Requests
                            </h3>
                            <div class="card-toolbar">
                                <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-light-warning border border-warning fw-bold fs-8 px-3 py-1">View All</a>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="recent-requests-skeleton">
                                @include('admin.layouts.partials._table-skeleton', ['id' => 'rr-skeleton'])
                            </div>
                            <div class="d-none" id="recent-requests-content">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3">
                                        <thead>
                                            <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                                <th class="ps-3 rounded-start min-w-80px">Ref</th>
                                                <th class="min-w-100px">Patient</th>
                                                <th class="min-w-80px">City</th>
                                                <th class="min-w-80px">Status</th>
                                                <th class="rounded-end min-w-80px">When</th>
                                            </tr>
                                        </thead>
                                        <tbody id="recent-requests-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Recent Requests-->
            </div>
            <!--end::Row 4-->

            <!--begin::Row 5 — Charts-->
            <div class="row g-5 mb-7">
                <!--begin::Bookings Chart-->
                <div class="col-xl-8">
                    <div class="card saas-card h-100">
                        <div class="card-header border-bottom border-gray-200 pt-5 min-h-50px">
                            <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">
                                <i class="ki-outline ki-chart-line fs-4 text-primary me-2"></i>Monthly Bookings & Revenue
                            </h3>
                            <div class="card-toolbar">
                                <span class="badge badge-light border border-gray-300 text-gray-600 fw-medium px-3 py-2 fs-8">Last 6 Months</span>
                            </div>
                        </div>
                        <div class="card-body pt-5 pb-4">
                            <div id="chart-bookings-skeleton" class="placeholder-glow">
                                <span class="placeholder bg-secondary rounded col-12 d-block" style="height:300px;"></span>
                            </div>
                            <div id="chart-bookings" style="height:300px;" class="d-none"></div>
                        </div>
                    </div>
                </div>
                <!--end::Bookings Chart-->

                <!--begin::Requests Chart-->
                <div class="col-xl-4">
                    <div class="card saas-card h-100">
                        <div class="card-header border-bottom pt-5 min-h-50px">
                            <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">
                                <i class="ki-outline ki-document fs-4 text-warning me-2"></i>Monthly Requests
                            </h3>
                        </div>
                        <div class="card-body pt-5 pb-4">
                            <div id="chart-requests-skeleton" class="placeholder-glow">
                                <span class="placeholder bg-secondary rounded col-12 d-block" style="height:300px;"></span>
                            </div>
                            <div id="chart-requests" style="height:300px;" class="d-none"></div>
                        </div>
                    </div>
                </div>
                <!--end::Requests Chart-->
            </div>
            <!--end::Row 5-->

            <!--begin::Row 6 — Breakdowns-->
            <div class="row g-5 mb-7">
                <!--begin::Booking Status-->
                <div class="col-xl-4">
                    <div class="card saas-card h-100">
                        <div class="card-header border-bottom pt-5 min-h-50px">
                            <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">
                                <i class="ki-outline ki-calendar-tick fs-4 text-info me-2"></i>Booking Status
                            </h3>
                        </div>
                        <div class="card-body py-4" id="booking-status-breakdown">
                            <div class="placeholder-glow" id="booking-status-skeleton">
                                @for ($i = 0; $i < 5; $i++)
                                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-200">
                                        <span class="placeholder bg-secondary rounded col-4"></span>
                                        <span class="placeholder bg-secondary rounded col-2"></span>
                                    </div>
                                @endfor
                            </div>
                            <div class="d-none" id="booking-status-content"></div>
                        </div>
                    </div>
                </div>
                <!--end::Booking Status-->

                <!--begin::Request Status-->
                <div class="col-xl-4">
                    <div class="card saas-card h-100">
                        <div class="card-header border-bottom pt-5 min-h-50px">
                            <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">
                                <i class="ki-outline ki-document fs-4 text-warning me-2"></i>Request Status
                            </h3>
                        </div>
                        <div class="card-body py-4" id="request-status-breakdown">
                            <div class="placeholder-glow" id="request-status-skeleton">
                                @for ($i = 0; $i < 5; $i++)
                                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                        <span class="placeholder bg-secondary rounded col-4"></span>
                                        <span class="placeholder bg-secondary rounded col-2"></span>
                                    </div>
                                @endfor
                            </div>
                            <div class="d-none" id="request-status-content"></div>
                        </div>
                    </div>
                </div>
                <!--end::Request Status-->

                <!--begin::Nurse Status-->
                <div class="col-xl-4">
                    <div class="card saas-card h-100">
                        <div class="card-header border-bottom pt-5 min-h-50px">
                            <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">
                                <i class="ki-outline ki-shield-tick fs-4 text-success me-2"></i>Nurse Status
                            </h3>
                        </div>
                        <div class="card-body py-4" id="nurse-status-breakdown">
                            <div class="placeholder-glow" id="nurse-status-skeleton">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                        <span class="placeholder bg-secondary rounded col-4"></span>
                                        <span class="placeholder bg-secondary rounded col-2"></span>
                                    </div>
                                @endfor
                            </div>
                            <div class="d-none" id="nurse-status-content"></div>
                        </div>
                    </div>
                </div>
                <!--end::Nurse Status-->
            </div>
            <!--end::Row 6-->

            <!--begin::Row 7 — Quick Links-->
            <div class="row g-5 mb-7">
                <div class="col-12">
                    <div class="card saas-card">
                        <div class="card-header border-bottom pt-5 min-h-50px">
                            <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">
                                <i class="ki-outline ki-abstract-26 fs-4 text-primary me-2"></i>Quick Actions
                            </h3>
                        </div>
                        <div class="card-body py-5">
                            <div class="d-flex flex-wrap gap-3">
                                <a href="{{ route('admin.patients.index') }}" class="btn btn-light-primary border border-primary fw-bold fs-7 px-5 py-3">
                                    <i class="ki-outline ki-people fs-4 me-2"></i>Patients
                                </a>
                                <a href="{{ route('admin.nurses.index') }}" class="btn btn-light-success border border-success fw-bold fs-7 px-5 py-3">
                                    <i class="ki-outline ki-shield-tick fs-4 me-2"></i>Nurses
                                </a>
                                <a href="{{ route('admin.nurses.pending_approval') }}" class="btn btn-light-info border border-info fw-bold fs-7 px-5 py-3">
                                    <i class="ki-outline ki-time fs-4 me-2"></i>Pending Approvals
                                </a>
                                <a href="{{ route('admin.requests.index') }}" class="btn btn-light-warning border border-warning fw-bold fs-7 px-5 py-3">
                                    <i class="ki-outline ki-document fs-4 me-2"></i>All Requests
                                </a>
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-light-info border border-info fw-bold fs-7 px-5 py-3">
                                    <i class="ki-outline ki-calendar-tick fs-4 me-2"></i>All Bookings
                                </a>
                                <a href="{{ route('admin.bids.index') }}" class="btn btn-light-dark border border-dark fw-bold fs-7 px-5 py-3">
                                    <i class="ki-outline ki-price-tag fs-4 me-2"></i>All Bids
                                </a>
                                <a href="{{ route('admin.support.index') }}" class="btn btn-light-danger border border-danger fw-bold fs-7 px-5 py-3">
                                    <i class="ki-outline ki-message-question fs-4 me-2"></i>Support Tickets
                                    <span class="badge badge-danger ms-2 fs-9" id="stat-open-tickets" style="display:none;"></span>
                                </a>
                                <a href="{{ route('admin.system.error-logs') }}" class="btn btn-light-danger border border-danger fw-bold fs-7 px-5 py-3">
                                    <i class="ki-outline ki-shield-cross fs-4 me-2"></i>Error Logs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row 7-->

        </div>
    </div>
    <!--end::Content-->

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Existing Alert Fetches ──────────────────────────
    $.get('{{ route("admin.system.errors.pending-count") }}').done(function(res) {
        if (res.count > 0) {
            $('#pending-errors-container').html(`
                <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex align-items-center flex-sm-row flex-column w-100 p-4 mb-7 shadow-sm">
                    <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4 mb-sm-0 mb-4"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10 text-center text-sm-start">
                        <h6 class="mb-1 text-danger fw-bold">Application Errors Detected</h6>
                        <span class="text-gray-800 fw-medium fs-7">Application has ${res.count} pending error${res.count > 1 ? 's' : ''} that require developer attention.</span>
                    </div>
                    <div class="ms-sm-auto mt-sm-0 mt-4">
                        <a href="{{ route('admin.system.error-logs') }}" class="btn btn-sm btn-danger fw-bold px-4 py-2">View Errors</a>
                    </div>
                </div>
            `);
        }
    });

    $.get('{{ route("admin.nurses.pending-count") }}').done(function(res) {
        if (res.count > 0) {
            $('#pending-nurses-container').html(`
                <div class="alert alert-dismissible bg-light-info border border-info border-dashed d-flex align-items-center flex-sm-row flex-column w-100 p-4 mb-7 shadow-sm">
                    <i class="ki-outline ki-profile-user fs-2hx text-info me-4 mb-sm-0 mb-4"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10 text-center text-sm-start">
                        <h6 class="mb-1 text-info fw-bold">Nurse Approvals Required</h6>
                        <span class="text-gray-800 fw-medium fs-7">${res.count} nurse profile${res.count > 1 ? 's' : ''} need${res.count === 1 ? 's' : ''} your approval to proceed.</span>
                    </div>
                    <div class="ms-sm-auto mt-sm-0 mt-4">
                        <a href="{{ route('admin.nurses.pending_approval') }}" class="btn btn-sm btn-info fw-bold px-4 py-2">View Nurses</a>
                    </div>
                </div>
            `);
        }
    });

    $.get('{{ route("admin.support.pending-count") }}').done(function(res) {
        if (res.count > 0) {
            $('#pending-tickets-container').html(`
                <div class="alert alert-dismissible bg-light-primary border border-primary border-dashed d-flex align-items-center flex-sm-row flex-column w-100 p-4 mb-7 shadow-sm">
                    <i class="ki-outline ki-message-text-2 fs-2hx text-primary me-4 mb-sm-0 mb-4"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10 text-center text-sm-start">
                        <h6 class="mb-1 text-primary fw-bold">Support Tickets Pending</h6>
                        <span class="text-gray-800 fw-medium fs-7">${res.count} support ticket${res.count > 1 ? 's are' : ' is'} in pending state and need${res.count === 1 ? 's' : ''} attention.</span>
                    </div>
                    <div class="ms-sm-auto mt-sm-0 mt-4">
                        <a href="{{ route('admin.support.index') }}?status=0" class="btn btn-sm btn-primary fw-bold px-4 py-2">View Tickets</a>
                    </div>
                </div>
            `);
        }
    });


    // ── Fetch Dashboard Stats ───────────────────────────
    fetch('{{ route("admin.dashboard.stats") }}')
        .then(r => r.json())
        .then(d => {
            // Overview
            $('#stat-total-patients').text(d.total_patients);
            $('#stat-total-nurses').text(d.total_nurses);
            $('#stat-total-requests').text(d.total_requests);
            $('#stat-total-bookings').text(d.total_bookings);

            // Revenue
            $('#stat-total-revenue').html('₹' + d.total_revenue);
            $('#stat-month-revenue').html('₹' + d.month_revenue);
            $('#stat-today-revenue').html('₹' + d.today_revenue);

            // Today
            $('#stat-today-requests').text(d.today_requests);
            $('#stat-today-bookings').text(d.today_bookings);
            $('#stat-today-bids').text(d.today_bids);
            $('#stat-today-reviews').text(d.today_reviews);
            $('#stat-today-logins').text(d.today_logins);

            // Support
            if (d.open_tickets > 0) {
                $('#stat-open-tickets').text(d.open_tickets).show();
            }

            // ── Booking Status Breakdown ────────────────
            var bColors = {pending:'warning', confirmed:'primary', active:'info', completed:'success', cancelled:'danger'};
            var bHtml = '';
            Object.keys(d.bookings_by_status).forEach(function(key) {
                var color = bColors[key] || 'dark';
                bHtml += `<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-vertical h-15px bg-${color} me-3"></span>
                        <span class="text-gray-700 fw-semibold fs-7 text-capitalize">${key}</span>
                    </div>
                    <span class="badge badge-light-${color} border border-${color} fw-bold px-3 py-1">${d.bookings_by_status[key]}</span>
                </div>`;
            });
            $('#booking-status-skeleton').addClass('d-none');
            $('#booking-status-content').html(bHtml).removeClass('d-none');

            // ── Request Status Breakdown ────────────────
            var rColors = {pending:'warning', matching:'primary', accepted:'info', completed:'success', cancelled:'danger', failed:'danger'};
            var rHtml = '';
            Object.keys(d.requests_by_status).forEach(function(key) {
                var color = rColors[key] || 'dark';
                rHtml += `<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-vertical h-15px bg-${color} me-3"></span>
                        <span class="text-gray-700 fw-semibold fs-7 text-capitalize">${key}</span>
                    </div>
                    <span class="badge badge-light-${color} border border-${color} fw-bold px-3 py-1">${d.requests_by_status[key]}</span>
                </div>`;
            });
            $('#request-status-skeleton').addClass('d-none');
            $('#request-status-content').html(rHtml).removeClass('d-none');

            // ── Nurse Status Breakdown ──────────────────
            var nColors = {pending:'warning', under_review:'info', approved:'success', rejected:'danger'};
            var nLabels = {pending:'Pending', under_review:'Under Review', approved:'Approved', rejected:'Rejected'};
            var nHtml = '';
            Object.keys(d.nurses_by_status).forEach(function(key) {
                var color = nColors[key] || 'dark';
                nHtml += `<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-vertical h-15px bg-${color} me-3"></span>
                        <span class="text-gray-700 fw-semibold fs-7">${nLabels[key] || key}</span>
                    </div>
                    <span class="badge badge-light-${color} border border-${color} fw-bold px-3 py-1">${d.nurses_by_status[key]}</span>
                </div>`;
            });
            $('#nurse-status-skeleton').addClass('d-none');
            $('#nurse-status-content').html(nHtml).removeClass('d-none');

            // ── Recent Bookings Table ───────────────────
            var bookingsHtml = '';
            var bsColors = {0:'warning', 1:'primary', 2:'info', 3:'success', 4:'danger'};
            d.recent_bookings.forEach(function(b) {
                var sc = bsColors[b.status] || 'dark';
                bookingsHtml += `<tr>
                    <td class="ps-3"><a href="/admin/bookings/${b.id}" class="text-primary fw-bold fs-7">#${b.reference_id}</a></td>
                    <td><span class="text-gray-800 fw-semibold fs-7">${b.user_name}</span></td>
                    <td><span class="fw-bold text-success fs-7">₹${b.total_amount}</span></td>
                    <td><span class="badge badge-light-${sc} border border-${sc} fw-bold px-2 py-1 fs-8">${b.status_text}</span></td>
                    <td><span class="text-gray-600 fs-8">${b.created_at}</span></td>
                </tr>`;
            });
            if (!d.recent_bookings.length) {
                bookingsHtml = '<tr><td colspan="5" class="text-center text-muted py-5 fs-7">No bookings found</td></tr>';
            }
            $('#recent-bookings-tbody').html(bookingsHtml);
            $('#recent-bookings-skeleton').addClass('d-none');
            $('#recent-bookings-content').removeClass('d-none');

            // ── Recent Requests Table ───────────────────
            var requestsHtml = '';
            var rsColors = {0:'warning', 1:'success', 2:'danger', 3:'secondary', 4:'primary', 5:'info', 6:'danger', 7:'danger', 8:'danger'};
            d.recent_requests.forEach(function(r) {
                var sc = rsColors[r.status] || 'dark';
                requestsHtml += `<tr>
                    <td class="ps-3"><a href="/admin/requests/${r.id}" class="text-primary fw-bold fs-7">#${r.reference_id}</a></td>
                    <td><span class="text-gray-800 fw-semibold fs-7">${r.user_name}</span></td>
                    <td><span class="text-gray-600 fs-7">${r.city}</span></td>
                    <td><span class="badge badge-light-${sc} border border-${sc} fw-bold px-2 py-1 fs-8">${r.status_text}</span></td>
                    <td><span class="text-gray-600 fs-8">${r.created_at}</span></td>
                </tr>`;
            });
            if (!d.recent_requests.length) {
                requestsHtml = '<tr><td colspan="5" class="text-center text-muted py-5 fs-7">No requests found</td></tr>';
            }
            $('#recent-requests-tbody').html(requestsHtml);
            $('#recent-requests-skeleton').addClass('d-none');
            $('#recent-requests-content').removeClass('d-none');

            // ── Bookings & Revenue Chart ────────────────
            var bookingsChartEl = document.getElementById('chart-bookings');
            var bookingsChart = new ApexCharts(bookingsChartEl, {
                series: [{
                    name: 'Bookings',
                    type: 'column',
                    data: d.monthly_bookings.map(m => m.count)
                }, {
                    name: 'Revenue (₹)',
                    type: 'area',
                    data: d.monthly_bookings.map(m => m.revenue)
                }],
                chart: {
                    fontFamily: 'inherit',
                    height: 300,
                    toolbar: { show: false }
                },
                colors: ['#3E97FF', '#7239EA'],
                fill: {
                    type: ['solid', 'gradient'],
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.0,
                        stops: [0, 90, 100]
                    }
                },
                plotOptions: {
                    bar: {
                        columnWidth: '40%',
                        borderRadius: 4
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: [0, 2]
                },
                xaxis: {
                    categories: d.monthly_bookings.map(m => m.month),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#A1A5B7', fontSize: '12px' } }
                },
                yaxis: [{
                    title: { text: 'Bookings', style: { color: '#A1A5B7', fontSize: '12px' } },
                    labels: { style: { colors: '#A1A5B7', fontSize: '12px' } }
                }, {
                    opposite: true,
                    title: { text: 'Revenue (₹)', style: { color: '#A1A5B7', fontSize: '12px' } },
                    labels: { style: { colors: '#A1A5B7', fontSize: '12px' } }
                }],
                grid: {
                    borderColor: '#EFF2F5',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(val, opts) {
                            if (opts.seriesIndex === 1) return '₹' + val.toLocaleString();
                            return val;
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontSize: '12px',
                    fontWeight: 600,
                    labels: { colors: '#5E6278' }
                }
            });
            $('#chart-bookings-skeleton').addClass('d-none');
            $(bookingsChartEl).removeClass('d-none');
            bookingsChart.render();

            // ── Requests Chart ──────────────────────────
            var requestsChartEl = document.getElementById('chart-requests');
            var requestsChart = new ApexCharts(requestsChartEl, {
                series: [{
                    name: 'Requests',
                    data: d.monthly_requests.map(m => m.count)
                }],
                chart: {
                    fontFamily: 'inherit',
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false }
                },
                colors: ['#F6C000'],
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                        borderRadius: 4
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: d.monthly_requests.map(m => m.month),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#A1A5B7', fontSize: '12px' } }
                },
                yaxis: {
                    labels: { style: { colors: '#A1A5B7', fontSize: '12px' } }
                },
                grid: {
                    borderColor: '#EFF2F5',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    y: {
                        formatter: function(val) { return val + ' requests'; }
                    }
                }
            });
            $('#chart-requests-skeleton').addClass('d-none');
            $(requestsChartEl).removeClass('d-none');
            requestsChart.render();
        })
        .catch(function(error) {
            console.error('Failed to load dashboard stats:', error);
            $('.stat-value').text('-');
        });
});
</script>
@endpush