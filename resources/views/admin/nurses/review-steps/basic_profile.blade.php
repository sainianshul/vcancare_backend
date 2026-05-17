<div class="card shadow-none border border-gray-300 bg-white">
    <div class="card-header border-0 pt-8 pb-4">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-2 mb-2 text-gray-900">Personal info</span>
            <span class="text-gray-500 fw-semibold fs-7">Review identity and contact details</span>
        </h3>
        <div class="card-toolbar">
            @php
                $badgeClass = 'badge-light-warning border-warning text-warning';
                $badgeText = 'In review';
                if ($status == \App\Models\NurseProfileVerification::STATUS_APPROVED) {
                    $badgeClass = 'badge-light-success border-success text-success';
                    $badgeText = 'Verified';
                } elseif ($status == \App\Models\NurseProfileVerification::STATUS_REJECTED) {
                    $badgeClass = 'badge-light-danger border-danger text-danger';
                    $badgeText = 'Rejected';
                }
            @endphp
            <span class="badge border fw-bold px-4 py-2 fs-8 {{ $badgeClass }}">{{ $badgeText }}</span>
        </div>
    </div>
    
    <div class="card-body pt-0 pb-8">

        @if($status == \App\Models\NurseProfileVerification::STATUS_REJECTED && !empty($verification->review_message))
        <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex align-items-center w-100 p-4 mb-7">
            <i class="ki-outline ki-message-text-2 fs-1 text-danger me-4"></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h6 class="mb-1 text-danger fw-bold">Rejection Reason</h6>
                <span class="text-gray-800 fw-medium fs-7">{{ $verification->review_message }}</span>
            </div>
        </div>
        @endif

        
        <!-- Bio Section -->
        <div class="border border-gray-300 border-dashed rounded p-5 mb-5 bg-light">
            <span class="text-gray-600 fs-8 fw-semibold d-block mb-1 text-uppercase tracking-wider">Biography / About</span>
            <span class="text-gray-800 fs-7 fw-medium lh-lg">
                {{ $sectionData['bio'] ?? 'N/A' }}
            </span>
        </div>

        <!-- Professional Details -->
        <div class="border border-gray-300 rounded p-5 mb-5">
            <h4 class="text-gray-900 fw-bold fs-6 mb-4 d-flex align-items-center">
                <i class="ki-outline ki-briefcase fs-4 text-primary me-2"></i> Professional Details
            </h4>
            <div class="row g-6">
                <div class="col-md-4">
                    <span class="text-gray-500 fs-8 fw-semibold d-block mb-1">License Number</span>
                    <span class="badge badge-light-primary border border-primary fw-medium px-3 py-1 fs-7">
                        {{ $sectionData['license_number'] ?? 'N/A' }}
                    </span>
                </div>
                <div class="col-md-4">
                    <span class="text-gray-500 fs-8 fw-semibold d-block mb-1">License Expiry</span>
                    <span class="text-gray-800 fs-7 fw-medium">
                        {{ $sectionData['license_expiry_date'] ? \Carbon\Carbon::parse($sectionData['license_expiry_date'])->format('d M Y') : 'N/A' }}
                    </span>
                </div>
                <div class="col-md-4">
                    <span class="text-gray-500 fs-8 fw-semibold d-block mb-1">Total Experience</span>
                    <span class="badge badge-light-success border border-success fw-medium px-3 py-1 fs-7">
                        {{ $sectionData['years_of_experience'] ?? '0' }} Years
                    </span>
                </div>
            </div>
        </div>

        <!-- Location & Address -->
        <div class="border border-gray-300 rounded p-5 mb-5">
            <h4 class="text-gray-900 fw-bold fs-6 mb-4 d-flex align-items-center">
                <i class="ki-outline ki-geolocation fs-4 text-primary me-2"></i> Location & Address
            </h4>
            <div class="row g-6">
                <div class="col-12">
                    <span class="text-gray-500 fs-8 fw-semibold d-block mb-1">Full Address Line</span>
                    <span class="text-gray-800 fs-7 fw-medium">{{ $sectionData['address'] ?? 'N/A' }}</span>
                </div>
                <div class="col-md-3 col-sm-6">
                    <span class="text-gray-500 fs-8 fw-semibold d-block mb-1">City</span>
                    <span class="text-gray-800 fs-7 fw-medium">{{ $sectionData['city'] ?? 'N/A' }}</span>
                </div>
                <div class="col-md-3 col-sm-6">
                    <span class="text-gray-500 fs-8 fw-semibold d-block mb-1">State</span>
                    <span class="text-gray-800 fs-7 fw-medium">{{ $sectionData['state'] ?? 'N/A' }}</span>
                </div>
                <div class="col-md-3 col-sm-6">
                    <span class="text-gray-500 fs-8 fw-semibold d-block mb-1">Country</span>
                    <span class="text-gray-800 fs-7 fw-medium">{{ $sectionData['country'] ?? 'N/A' }}</span>
                </div>
                <div class="col-md-3 col-sm-6">
                    <span class="text-gray-500 fs-8 fw-semibold d-block mb-1">Pincode / ZIP</span>
                    <span class="text-gray-800 fs-7 fw-medium">{{ $sectionData['pincode'] ?? 'N/A' }}</span>
                </div>
            </div>
            
            @if(isset($sectionData['latitude']) && isset($sectionData['longitude']))
                <div class="mt-4 pt-4 border-top border-gray-200 border-dashed d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center">
                        <span class="text-gray-500 fs-8 fw-semibold me-2">Latitude:</span>
                        <span class="badge badge-light fw-medium border border-gray-300 text-gray-700 fs-8">{{ $sectionData['latitude'] }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-gray-500 fs-8 fw-semibold me-2">Longitude:</span>
                        <span class="badge badge-light fw-medium border border-gray-300 text-gray-700 fs-8">{{ $sectionData['longitude'] }}</span>
                    </div>
                </div>
            @endif
        </div>

        @if(!$isReadOnly)
        <div class="d-flex align-items-center flex-wrap gap-3 mt-8 pt-6 border-top border-gray-200 border-dashed justify-content-end">
            <button type="button" class="btn btn-sm btn-light border border-gray-300 text-gray-700 hover-bg-light fw-bold px-5 py-2" onclick="processStepReview({{ $stepId }}, {{ \App\Models\NurseProfileVerification::STATUS_REJECTED }})">
                <i class="ki-outline ki-cross fs-5 me-2 text-gray-600"></i> Reject
            </button>
            <button type="button" class="btn btn-sm btn-dark text-white fw-bold px-5 py-2" onclick="processStepReview({{ $stepId }}, {{ \App\Models\NurseProfileVerification::STATUS_APPROVED }})">
                <i class="ki-outline ki-check fs-5 me-2 text-white"></i> Approve this section
            </button>
        </div>
        @endif
    </div>
</div>



