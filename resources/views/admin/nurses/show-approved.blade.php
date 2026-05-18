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
                                <i class="ki-outline ki-verify fs-1 text-primary" title="Verified Professional"></i>
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
                                        <div class="fs-4 fw-bold text-gray-900 lh-1">{{ $profile->avg_rating ?? '0.0' }}</div>
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
                                        <div class="fs-4 fw-bold text-gray-900 lh-1">{{ $profile->total_reviews ?? 0 }}</div>
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
                                        <div class="fs-4 fw-bold text-gray-900 lh-1">{{ $profile->trust_score ?? 100 }}%</div>
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
                                        <div class="fs-4 fw-bold text-gray-900 lh-1">{{ $profile->total_bookings_completed ?? 0 }}</div>
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
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 cursor-pointer" data-tab="requests">Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 cursor-pointer" data-tab="bids">Bids</a>
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
        <div class="row g-5 g-xl-8">
            <!-- Left Column: Details -->
            <div class="col-xl-4">
            <!-- Basic Details -->
            <div class="card shadow-sm border-0 mb-5 mb-xl-8">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bolder text-dark fs-5">About</h3>
                </div>
                <div class="card-body pt-4">
                    <div class="d-flex flex-column gap-3 fs-7 fw-semibold">
                        <div class="d-flex justify-content-between">
                            <span class="w-100px text-gray-500">License</span>
                            <span class="text-gray-900">{{ $profile->license_number ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="w-100px text-gray-500">Experience</span>
                            <span class="text-gray-900">{{ $profile->years_of_experience ?? 0 }} Years</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="w-100px text-gray-500">Gender</span>
                            <span class="text-gray-900">
                                @if($profile->gender == \App\Models\NurseProfile::GENDER_MALE) Male
                                @elseif($profile->gender == \App\Models\NurseProfile::GENDER_FEMALE) Female
                                @else Other @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="w-100px text-gray-500">City</span>
                            <span class="text-gray-900">{{ $profile->city ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="w-100px text-gray-500">State</span>
                            <span class="text-gray-900">{{ $profile->state ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="w-100px text-gray-500">Country</span>
                            <span class="text-gray-900">{{ $profile->country ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="w-100px text-gray-500">Postcode</span>
                            <span class="text-gray-900">{{ $profile->pincode ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2 pt-2 border-top border-gray-200 border-dashed">
                            <span class="w-100px text-gray-500">Phone</span>
                            <span class="text-gray-900">{{ $user->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="w-100px text-gray-500">Email</span>
                            <span class="text-gray-900 text-end text-break w-150px">{{ $user->email ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Schedule / Availability -->
                <div class="card shadow-sm border-0 mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-6">
                        <h3 class="card-title fw-bolder text-dark fs-5">Schedule</h3>
                    </div>
                    <div class="card-body pt-4">
                        <div class="mb-5 d-flex justify-content-between align-items-center bg-light rounded p-4 border border-gray-200">
                            <span class="text-gray-800 fw-medium fs-7">Current Status</span>
                            @if($profile->is_available)
                                <span class="badge badge-light-success border border-success fw-bold px-3 py-1 fs-8"><i class="ki-outline ki-check-circle fs-8 me-1 text-success"></i> Available</span>
                            @else
                                <span class="badge badge-light-danger border border-danger fw-bold px-3 py-1 fs-8"><i class="ki-outline ki-minus-circle fs-8 me-1 text-danger"></i> Offline</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom border-gray-200 border-dashed pb-3 mb-3">
                            <span class="text-gray-600 fw-semibold fs-7">Available Hours</span>
                            <span class="text-gray-900 fw-medium fs-7">
                                {{ $profile->available_from ? \Carbon\Carbon::parse($profile->available_from)->format('h:i A') : 'N/A' }} 
                                - 
                                {{ $profile->available_to ? \Carbon\Carbon::parse($profile->available_to)->format('h:i A') : 'N/A' }}
                            </span>
                        </div>
                        <div class="d-flex flex-column mt-4">
                            <span class="text-gray-600 fw-semibold fs-7 mb-2">Available Days</span>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                    $allDaysMap = \App\Models\NurseProfile::getDaysList();
                                    $days = [];
                                    if(!empty($profile->available_days)) {
                                        $days = is_string($profile->available_days) ? json_decode($profile->available_days, true) : $profile->available_days;
                                        if(!is_array($days)) $days = explode(',', $profile->available_days);
                                    }
                                    $days = array_map('intval', $days);
                                @endphp
                                @forelse($days as $dayValue)
                                    @if(isset($allDaysMap[$dayValue]))
                                        <span class="badge badge-light border border-gray-300 text-gray-800 fw-medium fs-8">{{ $allDaysMap[$dayValue] }}</span>
                                    @endif
                                @empty
                                    <span class="text-gray-500 fs-8">No specific days</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Graph & Activity -->
            <div class="col-xl-8">
                <!-- Earnings / Bookings Graph -->
                <div class="card shadow-sm border-0 mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-6">
                        <h3 class="card-title fw-bolder text-dark fs-5">Monthly Bookings & Activity</h3>
                        <div class="card-toolbar">
                            <button class="btn btn-sm btn-light border border-gray-300 text-gray-700 fw-medium">
                                <i class="ki-outline ki-filter fs-6"></i> Filter
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-6">
                        <div id="kt_charts_widget_activity" style="height: 300px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-comments type="{{ \App\Models\Comment::TYPE_NURSE }}" :model-id="$user->id" />

@endsection

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

                // Fake AJAX request (Replace with actual route later)
                // $.ajax({ url: '/admin/nurses/{{$user->id}}/tab/' + targetTab, type: 'GET' })
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
            });
        });
    });
</script>
@endpush
