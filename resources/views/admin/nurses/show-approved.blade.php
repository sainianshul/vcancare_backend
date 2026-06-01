@extends('admin.layouts.app')

@section('title', 'Nurse Profile - ' . $user->name)

@section('content')
    <!-- Profile Header -->
    <div class="card shadow-sm border-0 mb-5 mb-xl-8">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap">
                
                <!-- Avatar -->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-circle position-relative shadow-sm" style="border: 4px solid #fff;">
                        @if($user->profile_photo)
                            <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}" class="object-fit-cover" />
                        @else
                            <span class="symbol-label bg-light-primary text-primary fw-bold fs-2x border border-primary border-dashed">
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                        <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-white h-20px w-20px" title="Online/Active"></div>
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-900 fs-2 fw-bold me-2">{{ $user->name }}</span>
                                <i class="ki-outline ki-verify fs-1 text-primary me-2" title="Verified Professional"></i>

                            </div>
                            <div class="d-flex flex-wrap fw-medium fs-7 mb-4 pe-2">
                                <span class="d-flex align-items-center text-gray-800 me-5 mb-2">
                                    <i class="ki-outline ki-profile-circle fs-5 me-1 text-gray-600"></i> ID: #{{ $profile->id }}
                                </span>
                                <span class="d-flex align-items-center text-gray-800 me-5 mb-2">
                                    <i class="ki-outline ki-sms fs-5 me-1 text-gray-600"></i> {{ $user->email }}
                                </span>
                                <span class="d-flex align-items-center text-gray-800 me-5 mb-2">
                                    <i class="ki-outline ki-phone fs-5 me-1 text-gray-600"></i> {{ $user->phone ?? 'N/A' }}
                                </span>
                                <span class="d-flex align-items-center text-gray-800 mb-2">
                                    <i class="ki-outline ki-geolocation fs-5 me-1 text-gray-600"></i> {{ $profile->city ?? 'N/A' }}, {{ $profile->country ?? 'N/A' }}
                                </span>
                            </div>
                            
                            <!-- Key Dates -->
                            <div class="d-flex flex-wrap fw-medium fs-8 mb-4">
                                <span class="badge badge-light border border-gray-300 text-gray-700 me-3 mb-2 px-3 py-2">
                                    <i class="ki-outline ki-calendar-add fs-7 me-1"></i> Joined: {{ $profile->created_at->diffForHumans() }}
                                </span>
                                <span class="badge badge-light border border-gray-300 text-gray-700 me-3 mb-2 px-3 py-2">
                                    <i class="ki-outline ki-check-square fs-7 me-1"></i> Verified: {{ $profile->approved_at ? \Carbon\Carbon::parse($profile->approved_at)->format('d M Y') : 'N/A' }}
                                </span>
                                <span class="badge badge-light border border-gray-300 text-gray-700 mb-2 px-3 py-2">
                                    <i class="ki-outline ki-time fs-7 me-1"></i> Last Login: {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never' }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions & Badges -->
                        <div class="d-flex flex-column align-items-end my-4">
                            <div class="d-flex mb-4 gap-2">
                                <button class="btn btn-sm btn-light-primary border border-primary fw-bold px-4 py-2 hover-scale">
                                    <i class="ki-outline ki-sms fs-5 me-1"></i> SMS
                                </button>
                                <button class="btn btn-sm btn-light-info border border-info fw-bold px-4 py-2 hover-scale">
                                    <i class="ki-outline ki-message-text-2 fs-5 me-1"></i> Email
                                </button>
                                <a href="{{ route('admin.nurses.edit', $user->id) }}" class="btn btn-sm btn-light-warning border border-warning fw-bold px-4 py-2 hover-scale">
                                    <i class="ki-outline ki-pencil fs-5 me-1"></i> Edit
                                </a>
                            </div>
                            
                            <!-- Services Badges -->
                            <div class="d-flex flex-wrap justify-content-end gap-2 mt-2" style="max-width: 300px;">
                                @forelse($profile->careTypes as $careType)
                                    <span class="badge badge-light-primary border border-primary fw-medium px-3 py-1 fs-8">
                                        {{ $careType->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-500 fs-8 fw-medium">No services listed</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="d-flex flex-wrap flex-stack border-top border-gray-200 border-dashed pt-5 pb-2 mt-2">
                        <div class="row w-100 g-4">
                            <div class="col-6 col-md-3">
                                <div class="border border-gray-300 rounded px-4 py-3 bg-body d-flex align-items-center transition-all hover-scale h-100 shadow-sm">
                                    <div class="w-40px h-40px bg-light-warning rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                        <i class="ki-outline ki-star fs-3 text-warning"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <div class="fw-medium fs-8 text-gray-500 mb-1 text-uppercase tracking-wider">Avg Rating</div>
                                        <div class="fs-4 fw-bold text-gray-900 lh-1" id="stat-avg-rating">
                                            <span class="spinner-border spinner-border-sm text-warning align-middle" role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border border-gray-300 rounded px-4 py-3 bg-body d-flex align-items-center transition-all hover-scale h-100 shadow-sm">
                                    <div class="w-40px h-40px bg-light-success rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                        <i class="ki-outline ki-message-text-2 fs-3 text-success"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <div class="fw-medium fs-8 text-gray-500 mb-1 text-uppercase tracking-wider">Total Reviews</div>
                                        <div class="fs-4 fw-bold text-gray-900 lh-1" id="stat-total-reviews">
                                            <span class="spinner-border spinner-border-sm text-success align-middle" role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border border-gray-300 rounded px-4 py-3 bg-body d-flex align-items-center transition-all hover-scale h-100 shadow-sm">
                                    <div class="w-40px h-40px bg-light-primary rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                        <i class="ki-outline ki-shield-tick fs-3 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <div class="fw-medium fs-8 text-gray-500 mb-1 text-uppercase tracking-wider">Trust Score</div>
                                        <div class="fs-4 fw-bold text-gray-900 lh-1" id="stat-trust-score">
                                            <span class="spinner-border spinner-border-sm text-primary align-middle" role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border border-gray-300 rounded px-4 py-3 bg-body d-flex align-items-center transition-all hover-scale h-100 shadow-sm">
                                    <div class="w-40px h-40px bg-light-info rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                        <i class="ki-outline ki-briefcase fs-3 text-info"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <div class="fw-medium fs-8 text-gray-500 mb-1 text-uppercase tracking-wider">Jobs Done</div>
                                        <div class="fs-4 fw-bold text-gray-900 lh-1" id="stat-jobs-done">
                                            <span class="spinner-border spinner-border-sm text-info align-middle" role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation (AJAX) -->
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-semibold mt-4" id="nurse-profile-tabs">
                <li class="nav-item">
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 active cursor-pointer" data-tab="overview">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 cursor-pointer" data-tab="bookings">Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 cursor-pointer" data-tab="bids">Bids</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 cursor-pointer" data-tab="care-requests">Care Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 cursor-pointer" data-tab="reviews">Reviews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 cursor-pointer" data-tab="login-history">Login History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 cursor-pointer" data-tab="activity">Activity Log</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- AJAX Content Container -->
    <div id="tab-content-container">
        <!-- Default Content: Overview -->
        <div class="row g-7">
            <!-- Left Column: Main Details & Graph -->
            <div class="col-lg-8">
                
                <!-- About Details -->
                <div class="card shadow-sm border-0 border-gray-300 mb-7">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">Professional Details</h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex flex-wrap gap-4 mb-5">
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-3 mb-3 flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ki-outline ki-medal-star fs-3 text-warning me-2"></i>
                                    <div class="fs-6 fw-bold text-gray-900">{{ $profile->years_of_experience ?? 0 }} Years</div>
                                </div>
                                <div class="fw-semibold fs-8 text-gray-600">Experience</div>
                            </div>
                            
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-3 mb-3 flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ki-outline ki-document fs-3 text-primary me-2"></i>
                                    <div class="fs-6 fw-bold text-gray-900">{{ $profile->license_number ?? 'N/A' }}</div>
                                </div>
                                <div class="fw-semibold fs-8 text-gray-600">License Number</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-3 flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ki-outline ki-profile-user fs-3 text-success me-2"></i>
                                    <div class="fs-6 fw-bold text-gray-900">
                                        @if($profile->gender == \App\Models\NurseProfile::GENDER_MALE) Male
                                        @elseif($profile->gender == \App\Models\NurseProfile::GENDER_FEMALE) Female
                                        @else Other @endif
                                    </div>
                                </div>
                                <div class="fw-semibold fs-8 text-gray-600">Gender</div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="bg-light-info rounded p-3 border border-info border-dashed">
                                    <span class="text-info fw-semibold d-block fs-8 mb-1">Phone Number</span>
                                    <span class="fw-bold fs-7 text-gray-900">{{ $user->phone ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bg-light-primary rounded p-3 border border-primary border-dashed">
                                    <span class="text-primary fw-semibold d-block fs-8 mb-1">Email Address</span>
                                    <span class="fw-bold fs-7 text-gray-900 text-truncate d-block">{{ $user->email ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings / Bookings Graph -->
                <div class="card shadow-sm border border-gray-300 mb-7">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">Monthly Bookings & Activity</h3>
                        <div class="card-toolbar">
                            <button class="btn btn-sm btn-light border border-gray-300 text-gray-700 fw-medium px-3 py-1 fs-8">
                                <i class="ki-outline ki-filter fs-7"></i> Filter
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-6">
                        <div id="kt_charts_widget_activity" style="height: 300px"></div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Location & Schedule -->
            <div class="col-lg-4">
                
                <!-- Location Card (Matching Request UI) -->
                <div class="card shadow-sm border border-gray-300 mb-7">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Location</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex align-items-start mb-4">
                            <span class="bullet bullet-vertical h-30px bg-success me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-8">Full Address</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $profile->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-4">
                            <span class="bullet bullet-vertical h-30px bg-dark me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-8">Coordinates (Lat / Lng)</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $profile->latitude ?? 'N/A' }} / {{ $profile->longitude ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-4">
                            <span class="bullet bullet-vertical h-30px bg-primary me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-8">City & State</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $profile->city ?? 'N/A' }}, {{ $profile->state ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="bullet bullet-vertical h-30px bg-warning me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-8">Country & Pincode</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $profile->country ?? 'N/A' }} - {{ $profile->pincode ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <x-comments type="{{ \App\Models\Comment::TYPE_NURSE }}" :model-id="$user->id" />

@endsection

@push('datatables_css')
    @include('admin.layouts.partials._datatable-cdn-css')
@endpush

@push('styles')
<style>
    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-scale:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08) !important;
    }
    .symbol-circle {
        border-radius: 50% !important;
    }
    .symbol-circle img, .symbol-circle .symbol-label {
        border-radius: 50% !important;
    }
</style>
@endpush

@push('datatables_js')
    @include('admin.layouts.partials._datatable-cdn-js')
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Initialize ApexChart for Activity
    var initChart = function() {
        var element = document.getElementById("kt_charts_widget_activity");
        if (!element) return;

        var options = {
            series: [{
                name: 'Completed Bookings',
                data: [30, 40, 45, 50, 49, 60, 70, 91, 125, 130, 140, 150]
            }],
            chart: {
                fontFamily: 'inherit',
                type: 'area',
                height: 300,
                toolbar: { show: false }
            },
            colors: ['#7239ea'], // Primary color
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.0,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: {
                        colors: '#A1A5B7',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#A1A5B7',
                        fontSize: '12px'
                    }
                }
            },
            grid: {
                borderColor: '#EFF2F5',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    };

    // Tab AJAX Loading Logic
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch Nurse Stats
        fetch('{{ route('admin.nurses.stats', $user->id) }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('stat-avg-rating').innerHTML = data.avg_rating;
                document.getElementById('stat-total-reviews').innerHTML = data.total_reviews;
                document.getElementById('stat-trust-score').innerHTML = data.trust_score + '%';
                document.getElementById('stat-jobs-done').innerHTML = data.jobs_done;
            })
            .catch(error => {
                document.getElementById('stat-avg-rating').innerHTML = '-';
                document.getElementById('stat-total-reviews').innerHTML = '-';
                document.getElementById('stat-trust-score').innerHTML = '-';
                document.getElementById('stat-jobs-done').innerHTML = '-';
            });

        initChart();

        const tabs = document.querySelectorAll('#nurse-profile-tabs .nav-link');
        const container = document.getElementById('tab-content-container');
        const defaultContent = container.innerHTML; // Store overview content

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update active state
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const targetTab = this.getAttribute('data-tab');

                if (targetTab === 'overview') {
                    container.innerHTML = defaultContent;
                    initChart(); // Re-initialize chart
                    return;
                }

                // Show shimmer loader
                container.innerHTML = `
                    <div class="card shadow-none border border-gray-300 bg-body">
                        <div class="card-body pt-8 pb-8 placeholder-glow">
                            <span class="placeholder col-3 bg-secondary rounded mb-4" style="height:25px; display:block;"></span>
                            @include('admin.layouts.partials._table-skeleton', ['id' => 'tab-skeleton'])
                        </div>
                    </div>
                `;

                if (targetTab === 'reviews') {
                    $.ajax({
                        url: '{{ route('admin.nurses.reviews', $user->id) }}',
                        type: 'GET',
                        success: function (response) {
                            container.innerHTML = response.html || response;
                            // Re-execute scripts if any
                            const scripts = container.getElementsByTagName('script');
                            for (let i = 0; i < scripts.length; i++) {
                                eval(scripts[i].innerText);
                            }
                        },
                        error: function () {
                            container.innerHTML = '<div class="alert alert-danger m-5">Failed to load reviews. Please try again.</div>';
                        }
                    });
                } else if (targetTab === 'bookings') {
                    $.ajax({
                        url: '{{ route('admin.nurses.bookings', $user->id) }}',
                        type: 'GET',
                        success: function (response) {
                            container.innerHTML = response;
                            // Re-execute scripts since DataTables needs to initialize
                            const scripts = container.getElementsByTagName('script');
                            for (let i = 0; i < scripts.length; i++) {
                                eval(scripts[i].innerText);
                            }
                        },
                        error: function () {
                            container.innerHTML = '<div class="alert alert-danger m-5">Failed to load bookings. Please try again.</div>';
                        }
                    });
                } else if (targetTab === 'login-history') {
                    $.ajax({
                        url: '{{ route('admin.nurses.login-history', $user->id) }}',
                        type: 'GET',
                        success: function (response) {
                            container.innerHTML = response;
                            const scripts = container.getElementsByTagName('script');
                            for (let i = 0; i < scripts.length; i++) {
                                eval(scripts[i].innerText);
                            }
                        },
                        error: function () {
                            container.innerHTML = '<div class="alert alert-danger m-5">Failed to load login history. Please try again.</div>';
                        }
                    });
                } else if (targetTab === 'bids') {
                    $.ajax({
                        url: '{{ route('admin.nurses.bids', $user->id) }}',
                        type: 'GET',
                        success: function (response) {
                            container.innerHTML = response;
                            const scripts = container.getElementsByTagName('script');
                            for (let i = 0; i < scripts.length; i++) {
                                eval(scripts[i].innerText);
                            }
                        },
                        error: function () {
                            container.innerHTML = '<div class="alert alert-danger m-5">Failed to load bids. Please try again.</div>';
                        }
                    });
                } else if (targetTab === 'care-requests') {
                    $.ajax({
                        url: '{{ route('admin.nurses.care-requests', $user->id) }}',
                        type: 'GET',
                        success: function (response) {
                            container.innerHTML = response;
                            const scripts = container.getElementsByTagName('script');
                            for (let i = 0; i < scripts.length; i++) {
                                eval(scripts[i].innerText);
                            }
                        },
                        error: function () {
                            container.innerHTML = '<div class="alert alert-danger m-5">Failed to load care requests. Please try again.</div>';
                        }
                    });
                } else {
                    // Fake AJAX request for other pending tabs
                    setTimeout(() => {
                        container.innerHTML = `
                            <div class="card shadow-none border border-gray-300 bg-body">
                                <div class="card-header border-0 pt-6">
                                    <h3 class="card-title fw-bold text-gray-900 fs-5 text-capitalize">${targetTab.replace('-', ' ')}</h3>
                                </div>
                                <div class="card-body pt-4">
                                    <div class="alert bg-light border border-gray-300 border-dashed rounded p-5 d-flex align-items-center">
                                        <i class="ki-outline ki-information-5 fs-2x text-gray-600 me-4"></i>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-gray-900">Module Pending Integration</h4>
                                            <span class="text-gray-700 fw-medium">The ${targetTab.replace('-', ' ')} data will be loaded here via AJAX.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }, 800);
                }
            });
        });


    });
</script>
@endpush
