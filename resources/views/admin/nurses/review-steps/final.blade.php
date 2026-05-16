<div class="card shadow-none border border-gray-300 bg-white">
    <div class="card-header border-0 pt-8 pb-4">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-2 mb-2 text-gray-900">Final Decision</span>
            <span class="text-gray-500 fw-semibold fs-7">Approve or reject the entire application</span>
        </h3>
    </div>
    <div class="card-body pt-0 pb-8">
        <div class="d-flex align-items-center border border-primary border-dashed bg-light-primary rounded p-5 mb-8">
            <i class="ki-outline ki-shield-tick fs-2x text-primary me-4"></i>
            <div class="d-flex flex-column">
                <span class="text-gray-900 fw-bold fs-6 mb-1">Ensure all sections are reviewed</span>
                <span class="text-gray-800 fw-semibold fs-7">Before making a final decision, ensure all 6 sections on the left have been appropriately approved or rejected.</span>
            </div>
        </div>
        <div class="d-flex flex-column gap-3">
            <button type="button" class="btn btn-dark text-white hover-scale fw-bold py-3 fs-6 w-100" onclick="finalizeReview({{ \App\Models\NurseProfile::STATUS_APPROVED }})">
                <i class="ki-outline ki-check-circle fs-3 me-2 text-white"></i> Officially Approve Application
            </button>
            <button type="button" class="btn btn-light border border-gray-300 text-gray-700 hover-bg-light fw-bold py-3 fs-6 w-100" onclick="finalizeReview({{ \App\Models\NurseProfile::STATUS_REJECTED }})">
                <i class="ki-outline ki-cross-circle fs-3 me-2 text-gray-600"></i> Reject Entire Application
            </button>
        </div>
    </div>
</div>

