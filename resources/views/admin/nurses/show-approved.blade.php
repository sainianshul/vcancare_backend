@extends('admin.layouts.app')

@section('title', 'Nurse Profile - ' . $user->name)

@section('content')
    <!-- Profile Header -->
    <div class="card shadow-sm border border-gray-200 mb-5 mb-xl-8">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap">
                
                <!-- Avatar -->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-circle position-relative shadow-sm" style="border: 4px solid #fff;">
                        @if($user->profile_photo)
                            <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}" class="object-fit-cover" />
                        @else
                            <span class="symbol-label bg-light-primary text-primary fw-bold fs-2x">
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                        @if($profile->status === \App\Models\NurseProfile::STATUS_SUSPENDED)
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-warning rounded-circle border border-4 border-white h-20px w-20px" title="Suspended"></div>
                        @else
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-white h-20px w-20px" title="Active"></div>
                        @endif
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-900 fs-2 fw-bold me-2">{{ $user->name }}</span>
                                @if($profile->status === \App\Models\NurseProfile::STATUS_SUSPENDED)
                                    <span class="badge badge-light-warning fw-bold px-2 py-1 fs-8 text-uppercase me-2 border border-warning border-dashed">Suspended</span>
                                @else
                                    <i class="ki-outline ki-verify fs-1 text-primary me-2" title="Verified Professional"></i>
                                @endif
                                @if($user->created_by_admin)
                                    <span class="badge badge-light-primary fw-bold px-2 py-1 fs-9"><i class="ki-outline ki-shield-tick fs-8 text-primary me-1"></i>Added by Admin</span>
                                @endif
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
                                <span class="badge badge-light-primary text-primary me-3 mb-2 px-3 py-2 fw-bold">
                                    <i class="ki-outline ki-calendar-add fs-7 me-1 text-primary"></i> Joined: {{ $profile->created_at->diffForHumans() }}
                                </span>
                                <span class="badge badge-light-success text-success me-3 mb-2 px-3 py-2 fw-bold">
                                    <i class="ki-outline ki-check-square fs-7 me-1 text-success"></i> Verified: {{ $profile->approved_at ? \Carbon\Carbon::parse($profile->approved_at)->format('d M Y') : 'N/A' }}
                                </span>
                                <span class="badge badge-light-info text-info mb-2 px-3 py-2 fw-bold">
                                    <i class="ki-outline ki-time fs-7 me-1 text-info"></i> Last Login: {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never' }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions & Badges -->
                        <div class="d-flex flex-column align-items-end my-4">
                            <div class="d-flex mb-4 gap-2">

                                <a href="{{ route('admin.nurses.show-application', $user->id) }}" class="btn btn-sm btn-light-primary border border-primary border-dashed fw-bold px-4 py-2 hover-scale" data-bs-toggle="tooltip" title="View verification documents and onboarding details">
                                    <i class="ki-outline ki-folder-open fs-5 me-1 text-primary"></i> Verification History
                                </a>
                                <a href="{{ route('admin.nurses.edit', $user->id) }}" class="btn btn-sm btn-light-warning border border-warning border-dashed fw-bold px-4 py-2 hover-scale">
                                    <i class="ki-outline ki-pencil fs-5 me-1 text-warning"></i> Edit
                                </a>
                                
                                <!-- Status Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light-dark border border-dark border-dashed fw-bold text-gray-800 px-4 py-2 hover-scale dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ki-outline ki-setting-2 fs-5 me-1 text-gray-800"></i> Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border border-gray-200">
                                        <li><h6 class="dropdown-header text-gray-800 fw-bold">Change Status</h6></li>
                                        
                                        @if($profile->status != \App\Models\NurseProfile::STATUS_APPROVED)
                                        <li>
                                            <form action="{{ route('admin.nurses.status.update', $user->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="{{ \App\Models\NurseProfile::STATUS_APPROVED }}">
                                                <button type="submit" class="dropdown-item py-2 text-success" onclick="return confirm('Are you sure you want to approve/reactivate this nurse?')"><i class="ki-outline ki-check-circle fs-6 text-success me-2"></i> Approve Account</button>
                                            </form>
                                        </li>
                                        @endif

                                        @if($profile->status != \App\Models\NurseProfile::STATUS_SUSPENDED)
                                        <li>
                                            <form action="{{ route('admin.nurses.status.update', $user->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="{{ \App\Models\NurseProfile::STATUS_SUSPENDED }}">
                                                <input type="hidden" name="reason" value="Suspended by Admin">
                                                <button type="submit" class="dropdown-item py-2 text-warning" onclick="return confirm('Are you sure you want to suspend this nurse?')"><i class="ki-outline ki-minus-circle fs-6 text-warning me-2"></i> Suspend Account</button>
                                            </form>
                                        </li>
                                        @endif

                                        @if($profile->status != \App\Models\NurseProfile::STATUS_REJECTED)
                                        <li>
                                            <form action="{{ route('admin.nurses.status.update', $user->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="{{ \App\Models\NurseProfile::STATUS_REJECTED }}">
                                                <input type="hidden" name="reason" value="Rejected by Admin">
                                                <button type="submit" class="dropdown-item py-2 text-danger" onclick="return confirm('Are you sure you want to mark this nurse as rejected?')"><i class="ki-outline ki-cross-circle fs-6 text-danger me-2"></i> Mark Rejected</button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Services Badges -->
                            <div class="d-flex flex-wrap justify-content-end gap-2 mt-2" style="max-width: 300px;">
                                @forelse($profile->careTypes as $careType)
                                    <span class="badge bg-light text-gray-700 fw-medium px-3 py-1 fs-8">
                                        {{ $careType->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-500 fs-8 fw-medium">No services listed</span>
                                @endforelse
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
                <li class="nav-item">
                    <a class="nav-link text-active-primary text-gray-600 px-4 py-4 cursor-pointer" data-tab="contact">Contact</a>
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
                <div class="card shadow-sm border border-gray-200 mb-7 h-md-100">
                    <div class="card-header border-bottom border-gray-200 pt-5 pb-4 min-h-50px">
                        <h3 class="card-title fw-bold text-gray-900 fs-5 mb-0">Professional Details</h3>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="d-flex flex-wrap gap-4 mb-5">
                            <div class="bg-light-primary border border-primary border-dashed rounded py-4 px-4 flex-grow-1 text-center hover-scale">
                                <div class="fs-4 fw-bold text-primary">{{ $profile->years_of_experience ?? 0 }} Years</div>
                                <div class="text-gray-600 fw-bold fs-8 mt-1 text-uppercase">Experience</div>
                            </div>
                            
                            <div class="bg-light-info border border-info border-dashed rounded py-4 px-4 flex-grow-1 text-center hover-scale">
                                <div class="fs-4 fw-bold text-info">{{ $profile->license_number ?? 'N/A' }}</div>
                                <div class="text-gray-600 fw-bold fs-8 mt-1 text-uppercase">License Number</div>
                            </div>

                            <div class="bg-light-success border border-success border-dashed rounded py-4 px-4 flex-grow-1 text-center hover-scale">
                                <div class="fs-4 fw-bold text-success">
                                    @if($profile->gender == \App\Models\NurseProfile::GENDER_MALE) Male
                                    @elseif($profile->gender == \App\Models\NurseProfile::GENDER_OTHER) Other
                                    @else Female @endif
                                </div>
                                <div class="text-gray-600 fw-bold fs-8 mt-1 text-uppercase">Gender</div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-4 mb-5">
                            <div class="bg-light-warning border border-warning border-dashed rounded py-4 px-4 flex-grow-1 text-center hover-scale">
                                <div class="fs-4 fw-bold text-warning d-flex justify-content-center align-items-center">
                                    <i class="ki-outline ki-star text-warning fs-3 me-1"></i>
                                    {{ $profile->avg_rating ?? '0.0' }} <span class="text-gray-500 fs-8 fw-normal ms-1">({{ $profile->total_reviews ?? 0 }})</span>
                                </div>
                                <div class="text-gray-600 fw-bold fs-8 mt-1 text-uppercase">Rating</div>
                            </div>
                            
                            <div class="bg-light-primary border border-primary border-dashed rounded py-4 px-4 flex-grow-1 text-center hover-scale">
                                <div class="fs-4 fw-bold text-primary">{{ $profile->total_bookings ?? 0 }}</div>
                                <div class="text-gray-600 fw-bold fs-8 mt-1 text-uppercase">Total Bookings</div>
                            </div>

                            <div class="bg-light-success border border-success border-dashed rounded py-4 px-4 flex-grow-1 text-center hover-scale">
                                <div class="fs-4 fw-bold text-success">₹{{ number_format($profile->total_earnings ?? 0, 2) }}</div>
                                <div class="text-gray-600 fw-bold fs-8 mt-1 text-uppercase">Total Earnings</div>
                            </div>
                        </div>

                        <!-- Bio Section -->
                        @if($profile->bio)
                        <div class="mb-5">
                            <span class="text-gray-500 text-uppercase fw-bold d-block fs-8 mb-3">About Me</span>
                            <div class="bg-light rounded p-4 text-gray-700 fs-7 text-justify">
                                {{ $profile->bio }}
                            </div>
                        </div>
                        @endif

                        <div class="mb-5 border border-dashed border-gray-300 rounded p-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="text-gray-900 fw-bold d-block fs-5">Availability</span>
                                @if($profile->is_available)
                                    <span class="badge bg-light-success text-success fs-8 px-3 py-2"><i class="ki-outline ki-check-circle fs-7 text-success me-1"></i> Currently Available</span>
                                @else
                                    <span class="badge bg-light-danger text-danger fs-8 px-3 py-2"><i class="ki-outline ki-cross-circle fs-7 text-danger me-1"></i> Currently Unavailable</span>
                                @endif
                            </div>
                            
                            <div class="row g-6">
                                <!-- Days -->
                                <div class="col-12 col-xl-7">
                                    <span class="text-gray-500 text-uppercase fw-bold d-block fs-8 mb-2">Available Days</span>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        @php 
                                            $daysList = \App\Models\NurseProfile::getDaysList();
                                            $selectedDays = is_array($profile->available_days) ? $profile->available_days : (is_string($profile->available_days) ? json_decode($profile->available_days, true) : []);
                                        @endphp
                                        @foreach($daysList as $key => $label)
                                            @if(is_array($selectedDays) && in_array($key, $selectedDays))
                                                <span class="badge bg-primary text-white fs-7 px-3 py-2 shadow-sm rounded-pill">{{ substr($label, 0, 3) }}</span>
                                            @else
                                                <span class="badge bg-light border border-gray-300 text-gray-500 fs-7 px-3 py-2 opacity-50 rounded-pill">{{ substr($label, 0, 3) }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Time -->
                                <div class="col-12 col-xl-5">
                                    <span class="text-gray-500 text-uppercase fw-bold d-block fs-8 mb-2">Available Time</span>
                                    <div class="d-flex align-items-center bg-light-primary rounded px-4 py-3 border border-primary border-dashed">
                                        <i class="ki-outline ki-time fs-2x text-primary me-4"></i>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-900 fw-bold fs-6">
                                                @if($profile->available_from && $profile->available_to)
                                                    {{ \Carbon\Carbon::parse($profile->available_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($profile->available_to)->format('h:i A') }}
                                                @else
                                                    Not Specified
                                                @endif
                                            </span>
                                            @if($profile->timezone)
                                                <span class="text-gray-500 fs-8 fw-semibold">{{ $profile->timezone }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Details -->
                        <div class="row g-4 mb-5">
                            <div class="col-sm-6">
                                <div class="bg-light-primary rounded p-5 h-100 border border-primary border-dashed border-opacity-50 hover-scale">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="symbol symbol-40px me-3">
                                            <div class="symbol-label bg-white border border-primary border-dashed">
                                                <i class="ki-outline ki-phone fs-2 text-primary"></i>
                                            </div>
                                        </div>
                                        <span class="text-primary text-uppercase fw-bold fs-8">Phone Number</span>
                                    </div>
                                    <span class="fw-bold fs-5 text-gray-900 ms-14">{{ $user->phone ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bg-light-info rounded p-5 h-100 border border-info border-dashed border-opacity-50 hover-scale">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="symbol symbol-40px me-3">
                                            <div class="symbol-label bg-white border border-info border-dashed">
                                                <i class="ki-outline ki-sms fs-2 text-info"></i>
                                            </div>
                                        </div>
                                        <span class="text-info text-uppercase fw-bold fs-8">Email Address</span>
                                    </div>
                                    <span class="fw-bold fs-5 text-gray-900 ms-14 text-truncate d-block">{{ $user->email ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact Details -->
                        @if($profile->emergency_contact_name || $profile->emergency_contact_phone)
                        <div class="bg-light-danger rounded p-5 border border-danger border-dashed">
                            <h4 class="text-danger fw-bold fs-5 mb-4 d-flex align-items-center">
                                <i class="ki-outline ki-heart-circle fs-2x text-danger me-2"></i>
                                Emergency Contact
                            </h4>
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 text-uppercase fw-bold fs-8 mb-1">Contact Name</span>
                                        <span class="fw-bold fs-6 text-gray-900">{{ $profile->emergency_contact_name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 text-uppercase fw-bold fs-8 mb-1">Phone Number</span>
                                        <span class="fw-bold fs-6 text-gray-900">{{ $profile->emergency_contact_phone ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>


            </div>

            <!-- Right Column: Location & Schedule -->
            <div class="col-lg-4">
                
                <!-- Location Card (Matching Request UI) -->
                <div class="card shadow-sm border border-gray-300 mb-7">
                    <div class="card-header border-bottom border-gray-200 pt-5 pb-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Location</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex align-items-start mb-4">
                            <span class="bullet bullet-vertical h-30px bg-success me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-8">Full Address</span>
                                <span class="fw-bold fs-6 text-gray-900">{{ $profile->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-4">
                            <span class="bullet bullet-vertical h-30px bg-dark me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-8">Coordinates (Lat / Lng)</span>
                                <span class="fw-bold fs-6 text-gray-900">{{ $profile->latitude ?? 'N/A' }} / {{ $profile->longitude ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-4">
                            <span class="bullet bullet-vertical h-30px bg-primary me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-8">City & State</span>
                                <span class="fw-bold fs-6 text-gray-900">{{ $profile->city ?? 'N/A' }}, {{ $profile->state ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="bullet bullet-vertical h-30px bg-warning me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-8">Country & Pincode</span>
                                <span class="fw-bold fs-6 text-gray-900">{{ $profile->country ?? 'N/A' }} - {{ $profile->pincode ?? 'N/A' }}</span>
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
<script>

    // Tab AJAX Loading Logic
    document.addEventListener('DOMContentLoaded', function() {

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
                } else if (targetTab === 'contact') {
                    $.ajax({
                        url: '{{ route('admin.nurses.contact-form', $user->id) }}',
                        type: 'GET',
                        success: function (response) {
                            container.innerHTML = response;
                            const scripts = container.getElementsByTagName('script');
                            for (let i = 0; i < scripts.length; i++) {
                                eval(scripts[i].innerText);
                            }
                        },
                        error: function () {
                            container.innerHTML = '<div class="alert alert-danger m-5">Failed to load contact form. Please try again.</div>';
                        }
                    });
                } else {
                    // Fake AJAX request for other pending tabs
                    setTimeout(() => {
                        container.innerHTML = `
                            <div class="card shadow-none border border-gray-300 bg-body">
                                <div class="card-header border-bottom border-gray-200 pt-5 pb-4">
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



