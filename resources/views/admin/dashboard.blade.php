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
                <div class="d-flex align-items-center bg-white border border-gray-300 shadow-sm rounded px-3 py-2">
                    <i class="ki-outline ki-calendar fs-6 text-gray-600 me-2"></i>
                    <span class="text-gray-700 fw-bold fs-7">{{ now()->format('l, d M Y') }}</span>
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
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4 shadow-sm border-0">
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-white-50 small text-uppercase fw-bold mb-1">Total Patients</div>
                                    <div class="fs-1 fw-bolder" id="stat-total-patients"><span class="spinner-border spinner-border-sm text-white" role="status"></span></div>
                                </div>
                                <i class="ki-outline ki-people fs-3x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between border-0 py-3">
                            <a class="small text-white stretched-link text-decoration-none fw-bold" href="{{ route('admin.patients.index') }}">View Details</a>
                            <div class="small text-white"><i class="ki-outline ki-arrow-right text-white"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4 shadow-sm border-0">
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-white-50 small text-uppercase fw-bold mb-1">Approved Nurses</div>
                                    <div class="fs-1 fw-bolder" id="stat-total-nurses"><span class="spinner-border spinner-border-sm text-white" role="status"></span></div>
                                </div>
                                <i class="ki-outline ki-shield-tick fs-3x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between border-0 py-3">
                            <a class="small text-white stretched-link text-decoration-none fw-bold" href="{{ route('admin.nurses.index') }}">View Details</a>
                            <div class="small text-white"><i class="ki-outline ki-arrow-right text-white"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4 shadow-sm border-0">
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-white-50 small text-uppercase fw-bold mb-1">Total Enquiries</div>
                                    <div class="fs-1 fw-bolder" id="stat-total-requests"><span class="spinner-border spinner-border-sm text-white" role="status"></span></div>
                                </div>
                                <i class="ki-outline ki-document fs-3x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between border-0 py-3">
                            <a class="small text-white stretched-link text-decoration-none fw-bold" href="{{ route('admin.requests.index') }}">View Details</a>
                            <div class="small text-white"><i class="ki-outline ki-arrow-right text-white"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-info text-white mb-4 shadow-sm border-0">
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-white-50 small text-uppercase fw-bold mb-1">Active Bookings</div>
                                    <div class="fs-1 fw-bolder" id="stat-total-bookings"><span class="spinner-border spinner-border-sm text-white" role="status"></span></div>
                                </div>
                                <i class="ki-outline ki-calendar-tick fs-3x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between border-0 py-3">
                            <a class="small text-white stretched-link text-decoration-none fw-bold" href="{{ route('admin.bookings.index') }}">View Details</a>
                            <div class="small text-white"><i class="ki-outline ki-arrow-right text-white"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row 1-->

            <!--begin::Row 2 (Revenue & Charts)-->
            <div class="row mb-4">
                <!-- Main Chart -->
                <div class="col-xl-8">
                    <div class="card shadow-sm border-gray-300 h-100">
                        <div class="card-header border-bottom border-gray-200 pt-5 pb-4">
                            <h3 class="card-title align-items-start flex-column m-0">
                                <span class="fw-bold text-gray-800 fs-5"><i class="ki-outline ki-chart-line-star text-dark me-2"></i>Revenue & Bookings Overview</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="chart-bookings" style="height: 320px">
                                <div class="d-flex justify-content-center align-items-center h-100 placeholder-glow">
                                    <span class="spinner-border text-primary" role="status"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Highlights -->
                <div class="col-xl-4">
                    <div class="card shadow-sm border-gray-300 h-100">
                        <div class="card-header border-bottom border-gray-200 pt-5 pb-4">
                            <h3 class="card-title align-items-start flex-column m-0">
                                <span class="fw-bold text-gray-800 fs-5"><i class="ki-outline ki-wallet text-dark me-2"></i>Financial Highlights</span>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center p-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-outline ki-bank fs-2 text-success me-3"></i>
                                        <div>
                                            <div class="fw-bold text-gray-800">Lifetime Revenue</div>
                                            <small class="text-muted text-uppercase fw-bold fs-9">Total earnings till date</small>
                                        </div>
                                    </div>
                                    <span class="fs-4 fw-bolder text-success" id="stat-total-revenue"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-outline ki-graph-up fs-2 text-primary me-3"></i>
                                        <div>
                                            <div class="fw-bold text-gray-800">This Month</div>
                                            <small class="text-muted text-uppercase fw-bold fs-9">{{ now()->format('F Y') }}</small>
                                        </div>
                                    </div>
                                    <span class="fs-4 fw-bolder text-primary" id="stat-month-revenue"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-4 border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-outline ki-sun fs-2 text-warning me-3"></i>
                                        <div>
                                            <div class="fw-bold text-gray-800">Today's Revenue</div>
                                            <small class="text-muted text-uppercase fw-bold fs-9">Collection for the day</small>
                                        </div>
                                    </div>
                                    <span class="fs-4 fw-bolder text-warning" id="stat-today-revenue"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row 2-->

            <!--begin::Row 3 (Tables)-->
            <div class="row">
                <!-- Recent Bookings -->
                <div class="col-xl-6">
                    <div class="card shadow-sm border border-gray-200 mb-4 h-100">
                        <div class="card-header border-bottom border-gray-200 pt-5 pb-4 d-flex justify-content-between align-items-center">
                            <h3 class="card-title m-0">
                                <span class="fw-bold text-gray-800 fs-5"><i class="ki-outline ki-calendar-tick text-dark me-2"></i>Recent Bookings</span>
                            </h3>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline btn-outline-dashed btn-outline-primary fw-bold">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-500 border-bottom border-gray-200">
                                            <th class="ps-0 min-w-100px text-uppercase fs-9">Booking Ref</th>
                                            <th class="min-w-125px text-uppercase fs-9">Patient</th>
                                            <th class="min-w-100px text-uppercase fs-9">Amount</th>
                                            <th class="min-w-100px text-uppercase fs-9">Status</th>
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
                    <div class="card shadow-sm border border-gray-200 mb-4 h-100">
                        <div class="card-header border-bottom border-gray-200 pt-5 pb-4 d-flex justify-content-between align-items-center">
                            <h3 class="card-title m-0">
                                <span class="fw-bold text-gray-800 fs-5"><i class="ki-outline ki-document text-dark me-2"></i>Recent Care Requests</span>
                            </h3>
                            <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-outline btn-outline-dashed btn-outline-warning fw-bold">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-500 border-bottom border-gray-200">
                                            <th class="ps-0 min-w-100px text-uppercase fs-9">Request Ref</th>
                                            <th class="min-w-125px text-uppercase fs-9">Patient</th>
                                            <th class="min-w-100px text-uppercase fs-9">City</th>
                                            <th class="min-w-100px text-uppercase fs-9">Status</th>
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
                <div class="alert alert-dismissible bg-white border border-danger d-flex align-items-center p-4 mb-5 rounded shadow-sm">
                    <i class="ki-outline ki-shield-cross fs-1 text-danger me-4"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10 text-gray-800">
                        <span class="fw-bold text-danger fs-6">System Errors Detected</span>
                        <span class="fs-8">${res.count} application error(s) logged. Please review them immediately.</span>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('admin.system.error-logs') }}" class="btn btn-sm btn-outline btn-outline-dashed btn-outline-danger fw-bold">Review Logs</a>
                    </div>
                </div>
            `);
        }
    });

    $.get('{{ route("admin.nurses.pending-count") }}').done(function(res) {
        if (res.count > 0) {
            $('#pending-nurses-container').html(`
                <div class="alert alert-dismissible bg-white border border-info d-flex align-items-center p-4 mb-5 rounded shadow-sm">
                    <i class="ki-outline ki-profile-user fs-1 text-info me-4"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10 text-gray-800">
                        <span class="fw-bold text-info fs-6">Nurse Approvals Pending</span>
                        <span class="fs-8">${res.count} nurse profile(s) awaiting verification.</span>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('admin.nurses.pending_approval') }}" class="btn btn-sm btn-outline btn-outline-dashed btn-outline-info fw-bold">Review Nurses</a>
                    </div>
                </div>
            `);
        }
    });

    $.get('{{ route("admin.support.pending-count") }}').done(function(res) {
        if (res.count > 0) {
            $('#pending-tickets-container').html(`
                <div class="alert alert-dismissible bg-white border border-primary d-flex align-items-center p-4 mb-5 rounded shadow-sm">
                    <i class="ki-outline ki-message-text-2 fs-1 text-primary me-4"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10 text-gray-800">
                        <span class="fw-bold text-primary fs-6">Unresolved Support Tickets</span>
                        <span class="fs-8">${res.count} support ticket(s) are open and require a response.</span>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('admin.support.index') }}?status=0" class="btn btn-sm btn-outline btn-outline-dashed btn-outline-primary fw-bold">View Tickets</a>
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
                        <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">${b.created_at}</span>
                    </td>
                    <td>
                        <span class="text-gray-800 fw-bold d-block fs-6">${b.user_name}</span>
                        <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">Nurse: ${b.nurse_name}</span>
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
                        <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">${r.created_at}</span>
                    </td>
                    <td>
                        <span class="text-gray-800 fw-bold d-block fs-6">${r.user_name}</span>
                    </td>
                    <td>
                        <span class="text-gray-600 fw-semibold d-block fs-7">${r.city}</span>
                    </td>
                    <td>
                        <span class="badge badge-light-${sc} border border-${sc} fs-7 fw-bold">${r.status_text}</span>
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