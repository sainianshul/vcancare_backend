<div class="card shadow-none border border-gray-300 bg-white">
<div class="card-header border-0 pt-8 pb-4">
    <h3 class="card-title align-items-start flex-column">
        <span class="card-label fw-bold fs-2 mb-2 text-gray-900">Care Types</span>
        <span class="text-gray-500 fw-semibold fs-7">Review selected specializations</span>
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
</div><div class="card-body pt-0 pb-8">

        @if($status == \App\Models\NurseProfileVerification::STATUS_REJECTED && !empty($verification->review_message))
        <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-7">
            <i class="ki-outline ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0"></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h5 class="mb-1 text-danger">Rejection Reason</h5>
                <span class="text-gray-800 fw-medium">{{ $verification->review_message }}</span>
            </div>
        </div>
        @endif

    <div class="border border-gray-300 border-dashed rounded p-5 mb-4 bg-light">
        <span class="text-gray-600 fw-semibold fs-7 d-block mb-3">Care Types Selected:</span>
        <div class="d-flex flex-wrap gap-2">
            @forelse($sectionData['care_types'] ?? [] as $careType)
                <span class="badge badge-light-primary border border-primary fw-medium px-4 py-2 fs-7">{{ $careType['name'] }}</span>
            @empty
                <span class="text-gray-500 fs-7 fw-medium">No Care Types Selected</span>
            @endforelse
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
</div></div>




