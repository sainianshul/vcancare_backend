<div class="card shadow-none border border-gray-300 bg-white">
    <div class="card-header border-0 pt-8 pb-4">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-2 mb-2 text-gray-900">Availability</span>
            <span class="text-gray-500 fw-semibold fs-7">Review shift preferences and schedules</span>
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

        
        <!-- General Availability Status -->
        <div class="mb-6">
            <div class="d-flex align-items-center bg-light-primary rounded border border-primary border-dashed p-4">
                <i class="ki-outline ki-check-circle fs-1 text-primary me-3"></i>
                <div class="d-flex flex-column">
                    <span class="text-gray-900 fw-bold fs-6">Currently Taking Shifts</span>
                    <span class="text-gray-600 fw-medium fs-7">This nurse is marked as actively available for work.</span>
                </div>
            </div>
        </div>

        <!-- Working Hours Grid -->
        <div class="mb-8">
            <h4 class="text-gray-900 fw-bold fs-6 mb-5 d-flex align-items-center">
                <i class="ki-outline ki-time fs-4 text-primary me-2"></i> Working Hours
            </h4>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center border border-gray-200 border-dashed rounded p-4">
                        <div class="w-40px h-40px bg-light rounded d-flex align-items-center justify-content-center me-4">
                            <i class="ki-outline ki-entrance-left fs-3 text-gray-600"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-500 fw-semibold fs-8">Available From</span>
                            <span class="text-gray-900 fw-bold fs-5">{{ $sectionData['available_from'] ? \Carbon\Carbon::parse($sectionData['available_from'])->format('h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center border border-gray-200 border-dashed rounded p-4">
                        <div class="w-40px h-40px bg-light rounded d-flex align-items-center justify-content-center me-4">
                            <i class="ki-outline ki-entrance-right fs-3 text-gray-600"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-500 fw-semibold fs-8">Available To</span>
                            <span class="text-gray-900 fw-bold fs-5">{{ $sectionData['available_to'] ? \Carbon\Carbon::parse($sectionData['available_to'])->format('h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Days Grid -->
        <div class="mb-5">
            <h4 class="text-gray-900 fw-bold fs-6 mb-4 d-flex align-items-center">
                <i class="ki-outline ki-calendar fs-4 text-primary me-2"></i> Preferred Working Days
            </h4>
            
            <div class="d-flex flex-wrap gap-3 mt-4">
                @php
                    // Map of standard days
                    $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    
                    // Decode the chosen days if stored as JSON/array
                    $chosenDays = [];
                    if (!empty($sectionData['available_days'])) {
                        if (is_string($sectionData['available_days'])) {
                            $decoded = json_decode($sectionData['available_days'], true);
                            $chosenDays = is_array($decoded) ? $decoded : explode(',', $sectionData['available_days']);
                        } elseif (is_array($sectionData['available_days'])) {
                            $chosenDays = $sectionData['available_days'];
                        }
                    }
                    
                    // Normalize chosen days to ensure proper checking
                    $chosenDays = array_map('trim', $chosenDays);
                    $chosenDays = array_map('ucfirst', array_map('strtolower', $chosenDays));
                @endphp
                
                @if(count($chosenDays) > 0)
                    @foreach($allDays as $day)
                        @if(in_array($day, $chosenDays))
                            <span class="badge badge-light-primary border border-primary fw-medium px-4 py-2 fs-7 d-flex align-items-center gap-2">
                                <i class="ki-outline ki-check-circle fs-6 text-primary"></i> {{ $day }}
                            </span>
                        @else
                            <span class="badge badge-light fw-medium border border-gray-300 text-gray-400 px-4 py-2 fs-7">
                                {{ $day }}
                            </span>
                        @endif
                    @endforeach
                @else
                    <span class="text-gray-500 fs-7 fw-medium w-100 p-3 bg-light rounded text-center border border-dashed">No specific days selected.</span>
                @endif
            </div>
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



