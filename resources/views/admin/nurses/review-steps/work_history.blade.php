<div class="card shadow-none border border-gray-300 bg-white">
    <div class="card-header border-0 pt-8 pb-4">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-2 mb-2 text-gray-900">Work History</span>
            <span class="text-gray-500 fw-semibold fs-7">Review past employment records</span>
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
        <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-7">
            <i class="ki-outline ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0"></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h5 class="mb-1 text-danger">Rejection Reason</h5>
                <span class="text-gray-800 fw-medium">{{ $verification->review_message }}</span>
            </div>
        </div>
        @endif

        
        @forelse($sectionData['work_histories'] ?? [] as $work)
        <div class="border border-gray-300 rounded p-6 mb-5 d-flex">
            <!-- Icon -->
            <div class="w-50px h-50px bg-light-info rounded d-flex align-items-center justify-content-center me-5 border border-info border-dashed flex-shrink-0">
                <i class="ki-outline ki-briefcase fs-2x text-info"></i>
            </div>
            
            <!-- Details -->
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex flex-column">
                        <span class="text-gray-900 fw-bold fs-5">{{ $work['role_or_position'] }}</span>
                        <span class="text-gray-500 fw-semibold fs-7 mt-1">
                            <i class="ki-outline ki-shop fs-6 me-1 text-gray-500"></i> {{ $work['organization_name'] }}
                            @if(!empty($work['location']))
                            <span class="mx-2 text-gray-300">|</span>
                            <i class="ki-outline ki-geolocation fs-6 me-1 text-gray-500"></i> {{ $work['location'] }}
                            @endif
                        </span>
                    </div>
                    <span class="badge badge-light fw-medium border border-gray-300 text-gray-700 px-3 py-2 fs-7">
                        {{ $work['start_date'] ? \Carbon\Carbon::parse($work['start_date'])->format('M Y') : 'N/A' }} 
                        &nbsp;&nbsp;—&nbsp;&nbsp; 
                        {{ $work['is_currently_working'] ? 'Present' : ($work['end_date'] ? \Carbon\Carbon::parse($work['end_date'])->format('M Y') : 'N/A') }}
                    </span>
                </div>
                
                @if(!empty($work['description']))
                <div class="mt-4 pt-4 border-top border-gray-200 border-dashed">
                    <span class="text-gray-800 fw-medium fs-7 lh-lg d-block">
                        {{ $work['description'] }}
                    </span>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="border border-gray-300 border-dashed rounded p-5 mb-4 bg-light text-center">
            <span class="text-gray-500 fs-7 fw-medium">No work history records provided.</span>
        </div>
        @endforelse

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



