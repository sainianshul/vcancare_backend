@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Dashboard Overview
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">Welcome back, {{ auth()->user()->name }}</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center bg-light border border-gray-300 rounded px-4 py-2">
                    <i class="ki-outline ki-calendar fs-5 text-gray-600 me-2"></i>
                    <span class="text-gray-700 fw-medium fs-7">{{ now()->format('l, d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!--begin::Alerts-->
            <div id="pending-errors-container"></div>
            <div id="pending-nurses-container"></div>
            <div id="pending-tickets-container"></div>
            <!--end::Alerts-->

            <!--begin::Row 1 (Stats)-->
            <div class="row g-5 g-xl-8 mb-xl-8">
                <!-- Patients -->
                <div class="col-xl-3">
                    <div class="card hover-elevate-up shadow-sm border-0">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-people fs-1 text-primary"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('admin.patients.index') }}" class="text-gray-800 text-hover-primary fw-bold fs-5">Patients</a>
                                    <span class="text-gray-500 fw-semibold fs-7">Registered Users</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="fs-2hx fw-bold text-gray-900" id="stat-total-patients">
                                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nurses -->
                <div class="col-xl-3">
                    <div class="card hover-elevate-up shadow-sm border-0">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-success">
                                        <i class="ki-outline ki-shield-tick fs-1 text-success"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('admin.nurses.index') }}" class="text-gray-800 text-hover-success fw-bold fs-5">Nurses</a>
                                    <span class="text-gray-500 fw-semibold fs-7">Approved Staff</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="fs-2hx fw-bold text-gray-900" id="stat-total-nurses">
                                    <span class="spinner-border spinner-border-sm text-success" role="status"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requests -->
                <div class="col-xl-3">
                    <div class="card hover-elevate-up shadow-sm border-0">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-warning">
                                        <i class="ki-outline ki-document fs-1 text-warning"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('admin.requests.index') }}" class="text-gray-800 text-hover-warning fw-bold fs-5">Requests</a>
                                    <span class="text-gray-500 fw-semibold fs-7">Total Enquiries</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="fs-2hx fw-bold text-gray-900" id="stat-total-requests">
                                    <span class="spinner-border spinner-border-sm text-warning" role="status"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings -->
                <div class="col-xl-3">
                    <div class="card hover-elevate-up shadow-sm border-0">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-info">
                                        <i class="ki-outline ki-calendar-tick fs-1 text-info"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('admin.bookings.index') }}" class="text-gray-800 text-hover-info fw-bold fs-5">Bookings</a>
                                    <span class="text-gray-500 fw-semibold fs-7">Confirmed & Active</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="fs-2hx fw-bold text-gray-900" id="stat-total-bookings">
                                    <span class="spinner-border spinner-border-sm text-info" role="status"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row 1-->

            <!--begin::Row 2 (Revenue & Charts)-->
            <div class="row g-5 g-xl-8 mb-xl-8">
                <!-- Main Chart -->
                <div class="col-xl-8">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900 fs-4">Revenue & Bookings</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Performance over the last 6 months</span>
                            </h3>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="ki-outline ki-category fs-2"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-0 pb-4">
                            <div id="chart-bookings" style="height: 350px">
                                <div class="d-flex justify-content-center align-items-center h-100 placeholder-glow">
                                    <span class="spinner-border text-primary" role="status"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Highlights -->
                <div class="col-xl-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900 fs-4">Financial Overview</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Earnings breakdown</span>
                            </h3>
                        </div>
                        <div class="card-body p-6 d-flex flex-column justify-content-center">
                            
                            <!-- Total Revenue -->
                            <div class="d-flex align-items-center bg-light-success rounded p-5 mb-5">
                                <span class="svg-icon svg-icon-success me-5">
                                    <i class="ki-outline ki-wallet fs-1 text-success"></i>
                                </span>
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="fw-bold text-gray-800 text-hover-success fs-5">Lifetime Revenue</a>
                                    <span class="text-gray-500 fw-semibold d-block fs-7">Total earnings till date</span>
                                </div>
                                <span class="fw-bold text-success py-1" id="stat-total-revenue">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>

                            <!-- Month Revenue -->
                            <div class="d-flex align-items-center bg-light-primary rounded p-5 mb-5">
                                <span class="svg-icon svg-icon-primary me-5">
                                    <i class="ki-outline ki-graph-up fs-1 text-primary"></i>
                                </span>
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="fw-bold text-gray-800 text-hover-primary fs-5">This Month</a>
                                    <span class="text-gray-500 fw-semibold d-block fs-7">{{ now()->format('F Y') }}</span>
                                </div>
                                <span class="fw-bold text-primary py-1" id="stat-month-revenue">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>

                            <!-- Today Revenue -->
                            <div class="d-flex align-items-center bg-light-warning rounded p-5">
                                <span class="svg-icon svg-icon-warning me-5">
                                    <i class="ki-outline ki-sun fs-1 text-warning"></i>
                                </span>
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="fw-bold text-gray-800 text-hover-warning fs-5">Today's Revenue</a>
                                    <span class="text-gray-500 fw-semibold d-block fs-7">Collection for the day</span>
                                </div>
                                <span class="fw-bold text-warning py-1" id="stat-today-revenue">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row 2-->

            <!--begin::Row 3 (Tables)-->
            <div class="row g-5 g-xl-8">
                <!-- Recent Bookings -->
                <div class="col-xl-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900 fs-4">Recent Bookings</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Latest 5 active bookings</span>
                            </h3>
                            <div class="card-toolbar">
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light-primary">View All</a>
                            </div>
                        </div>
                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-500 border-bottom border-gray-200">
                                            <th class="ps-0 min-w-100px">Booking Ref</th>
                                            <th class="min-w-125px">Patient</th>
                                            <th class="min-w-100px">Amount</th>
                                            <th class="min-w-100px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recent-bookings-tbody">
                                        <tr><td colspan="4" class="text-center py-5"><span class="spinner-border spinner-border-sm text-primary"></span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Requests -->
                <div class="col-xl-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900 fs-4">Recent Care Requests</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Latest 5 patient requests</span>
                            </h3>
                            <div class="card-toolbar">
                                <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-light-warning">View All</a>
                            </div>
                        </div>
                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-500 border-bottom border-gray-200">
                                            <th class="ps-0 min-w-100px">Request Ref</th>
                                            <th class="min-w-125px">Patient</th>
                                            <th class="min-w-100px">City</th>
                                            <th class="min-w-100px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recent-requests-tbody">
                                        <tr><td colspan="4" class="text-center py-5"><span class="spinner-border spinner-border-sm text-primary"></span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row 3-->

        </div>
    </div>
    <!--end::Content-->

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Load Alerts ───────────────────────────
    $.get('{{ route("admin.system.errors.pending-count") }}').done(function(res) {
        if (res.count > 0) {
            $('#pending-errors-container').html(`
                <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-5 rounded shadow-sm border border-danger border-dashed">
                    <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4 mb-5 mb-sm-0"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10 text-gray-800">
                        <h4 class="fw-bold text-danger mb-1">System Errors Detected</h4>
                        <span>${res.count} application error(s) logged. Please review them immediately to ensure system stability.</span>
                    </div>
                    <div class="mt-4 mt-sm-0 ms-sm-auto">
                        <a href="{{ route('admin.system.error-logs') }}" class="btn btn-sm btn-danger fw-bold">Review Logs</a>
                    </div>
                </div>
            `);
        }
    });

    $.get('{{ route("admin.nurses.pending-count") }}').done(function(res) {
        if (res.count > 0) {
            $('#pending-nurses-container').html(`
                <div class="alert alert-dismissible bg-light-info d-flex flex-column flex-sm-row p-5 mb-5 rounded shadow-sm border border-info border-dashed">
                    <i class="ki-outline ki-profile-user fs-2hx text-info me-4 mb-5 mb-sm-0"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10 text-gray-800">
                        <h4 class="fw-bold text-info mb-1">Nurse Approvals Pending</h4>
                        <span>${res.count} nurse profile(s) awaiting verification. Approve them to onboard new nurses.</span>
                    </div>
                    <div class="mt-4 mt-sm-0 ms-sm-auto">
                        <a href="{{ route('admin.nurses.pending_approval') }}" class="btn btn-sm btn-info fw-bold">Review Nurses</a>
                    </div>
                </div>
            `);
        }
    });

    $.get('{{ route("admin.support.pending-count") }}').done(function(res) {
        if (res.count > 0) {
            $('#pending-tickets-container').html(`
                <div class="alert alert-dismissible bg-light-primary d-flex flex-column flex-sm-row p-5 mb-5 rounded shadow-sm border border-primary border-dashed">
                    <i class="ki-outline ki-message-text-2 fs-2hx text-primary me-4 mb-5 mb-sm-0"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10 text-gray-800">
                        <h4 class="fw-bold text-primary mb-1">Unresolved Support Tickets</h4>
                        <span>${res.count} support ticket(s) are open and require a response.</span>
                    </div>
                    <div class="mt-4 mt-sm-0 ms-sm-auto">
                        <a href="{{ route('admin.support.index') }}?status=0" class="btn btn-sm btn-primary fw-bold">View Tickets</a>
                    </div>
                </div>
            `);
        }
    });

    // ── Fetch Dashboard Stats ───────────────────────────
    fetch('{{ route("admin.dashboard.stats") }}')
        .then(r => r.json())
        .then(d => {
            // Overview Numbers
            $('#stat-total-patients').text(d.total_patients);
            $('#stat-total-nurses').text(d.total_nurses);
            $('#stat-total-requests').text(d.total_requests);
            $('#stat-total-bookings').text(d.total_bookings);

            // Revenue Numbers
            $('#stat-total-revenue').html('₹' + d.total_revenue);
            $('#stat-month-revenue').html('₹' + d.month_revenue);
            $('#stat-today-revenue').html('₹' + d.today_revenue);

            // ── Recent Bookings Table ───────────────────
            var bookingsHtml = '';
            var bsColors = {0:'warning', 1:'primary', 2:'info', 3:'success', 4:'danger'};
            d.recent_bookings.forEach(function(b) {
                var sc = bsColors[b.status] || 'dark';
                bookingsHtml += `<tr>
                    <td>
                        <a href="/admin/bookings/${b.id}" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">#${b.reference_id}</a>
                        <span class="text-muted fw-semibold d-block fs-7">${b.created_at}</span>
                    </td>
                    <td>
                        <span class="text-gray-800 fw-bold d-block fs-6">${b.user_name}</span>
                        <span class="text-muted fw-semibold d-block fs-7">Nurse: ${b.nurse_name}</span>
                    </td>
                    <td>
                        <span class="text-success fw-bold d-block fs-6">₹${b.total_amount}</span>
                    </td>
                    <td>
                        <span class="badge badge-light-${sc} fs-7 fw-bold">${b.status_text}</span>
                    </td>
                </tr>`;
            });
            if (!d.recent_bookings.length) {
                bookingsHtml = '<tr><td colspan="4" class="text-center text-muted py-5">No bookings yet.</td></tr>';
            }
            $('#recent-bookings-tbody').html(bookingsHtml);

            // ── Recent Requests Table ───────────────────
            var requestsHtml = '';
            var rsColors = {0:'warning', 1:'success', 2:'danger', 3:'secondary', 4:'primary', 5:'info', 6:'danger', 7:'danger', 8:'danger'};
            d.recent_requests.forEach(function(r) {
                var sc = rsColors[r.status] || 'dark';
                requestsHtml += `<tr>
                    <td>
                        <a href="/admin/requests/${r.id}" class="text-gray-900 fw-bold text-hover-warning mb-1 fs-6">#${r.reference_id}</a>
                        <span class="text-muted fw-semibold d-block fs-7">${r.created_at}</span>
                    </td>
                    <td>
                        <span class="text-gray-800 fw-bold d-block fs-6">${r.user_name}</span>
                    </td>
                    <td>
                        <span class="text-gray-600 fw-semibold d-block fs-7">${r.city}</span>
                    </td>
                    <td>
                        <span class="badge badge-light-${sc} fs-7 fw-bold">${r.status_text}</span>
                    </td>
                </tr>`;
            });
            if (!d.recent_requests.length) {
                requestsHtml = '<tr><td colspan="4" class="text-center text-muted py-5">No care requests yet.</td></tr>';
            }
            $('#recent-requests-tbody').html(requestsHtml);

            // ── Main Chart: Bookings & Revenue ────────────────
            var bookingsChartEl = document.getElementById('chart-bookings');
            bookingsChartEl.innerHTML = ''; // clear skeleton
            
            var bookingsChart = new ApexCharts(bookingsChartEl, {
                series: [{
                    name: 'Bookings',
                    type: 'column',
                    data: d.monthly_bookings.map(m => m.count)
                }, {
                    name: 'Revenue (₹)',
                    type: 'line',
                    data: d.monthly_bookings.map(m => m.revenue)
                }],
                chart: {
                    fontFamily: 'inherit',
                    height: 350,
                    type: 'line',
                    toolbar: { show: false }
                },
                stroke: {
                    width: [0, 3],
                    curve: 'smooth'
                },
                colors: ['#E4E6EF', '#009EF7'], // Column: light gray, Line: primary
                fill: {
                    type: ['solid', 'solid']
                },
                plotOptions: {
                    bar: {
                        columnWidth: '40%',
                        borderRadius: 6
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: d.monthly_bookings.map(m => m.month),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: { colors: '#A1A5B7', fontSize: '12px' }
                    }
                },
                yaxis: [{
                    title: { text: 'Bookings', style: { color: '#A1A5B7', fontSize: '12px', fontWeight: 500 } },
                    labels: { style: { colors: '#A1A5B7', fontSize: '12px' } }
                }, {
                    opposite: true,
                    title: { text: 'Revenue (₹)', style: { color: '#A1A5B7', fontSize: '12px', fontWeight: 500 } },
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
                    fontSize: '13px',
                    fontWeight: 600,
                    labels: { colors: '#5E6278' },
                    markers: { radius: 12 }
                }
            });
            bookingsChart.render();
            
        }).catch(err => {
            console.error("Failed to load dashboard stats", err);
        });
});
</script>
@endpush