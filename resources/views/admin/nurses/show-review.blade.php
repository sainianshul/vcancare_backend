@extends('admin.layouts.app')

@section('title', 'Review Nurse Application')

@section('content')
    @inject('onboardingService', 'App\Services\OnboardingService')

    @php
        $isReadOnly = !in_array($profile->status, [\App\Models\NurseProfile::STATUS_PENDING, \App\Models\NurseProfile::STATUS_UNDER_REVIEW]);
    @endphp

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Application Review" description="Verify onboarding sections independently" />
                <x-breadcrumb :items="[
            ['label' => 'People'],
            ['label' => 'Nurses', 'url' => route('admin.nurses.index')],
            ['label' => $user->name],
        ]" />
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.nurses.index') }}" class="btn btn-sm btn-light fw-semibold border border-gray-300">
                    <i class="ki-outline ki-arrow-left fs-4 me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            @if($profile->status == \App\Models\NurseProfile::STATUS_REJECTED)
                <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex align-items-center w-100 p-4 mb-10 shadow-sm">
                    <i class="ki-outline ki-cross-square fs-1 text-danger me-4"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <h6 class="mb-1 text-danger fw-bold">Application Rejected</h6>
                        <span class="text-gray-800 fw-medium fs-7">Reason:
                            {{ $profile->rejection_reason ?? 'No final reason provided.' }}</span>
                    </div>
                </div>
            @endif

            <!--begin::Navbar (Same as Pending view)-->
            <div class="card card-bordered border-gray-300 mb-5 mb-xl-10 shadow-none">
                <div class="card-body pt-9 pb-9">
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <span
                                    class="symbol-label bg-light-primary border border-primary fs-2x fw-bold text-primary">
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 2)) }}
                                </span>
                                <div
                                    class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-primary rounded-circle border border-4 border-body h-20px w-20px">
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <h1 class="text-gray-900 fs-1 fw-bold me-2">{{ $user->name }}</h1>
                                            <span
                                                class="badge badge-light-primary border border-primary fw-semibold px-3 py-1 me-2">
                                                <i class="ki-outline ki-magnifier fs-7 text-primary me-1"></i> Under Review
                                            </span>
                                            <x-api-token-badge :token="$apiToken" :user-id="$user->id" />
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button
                                                class="btn btn-sm btn-light-primary border border-primary fw-bold px-4 py-2 shadow-sm">
                                                <i class="ki-outline ki-sms fs-5 me-1"></i> Send SMS
                                            </button>
                                            <button
                                                class="btn btn-sm btn-light-info border border-info fw-bold px-4 py-2 shadow-sm">
                                                <i class="ki-outline ki-sms fs-5 me-1"></i> Send Email
                                            </button>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-3 mt-4">
                                        <div class="d-flex align-items-center gap-6">
                                            <span class="d-flex align-items-center text-gray-900 fw-semibold fs-7">
                                                <i class="ki-outline ki-phone fs-5 me-2 text-primary"></i>
                                                {{ $user->phone ?: '—' }}
                                            </span>
                                            <span class="d-flex align-items-center text-gray-900 fw-semibold fs-7">
                                                <i class="ki-outline ki-sms fs-5 me-2 text-primary"></i>
                                                {{ $user->email }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-4 mt-2">
                                            <span
                                                class="d-flex align-items-center text-gray-900 border border-gray-300 border-dashed rounded px-3 py-1 bg-light fs-8">
                                                <i class="ki-outline ki-calendar fs-6 me-2 text-primary"></i>
                                                <span class="fw-semibold">Joined:&nbsp;</span>
                                                <span
                                                    class="text-gray-900 fw-bold">{{ $user->created_at->format('d M Y') }}</span>
                                            </span>
                                            <span
                                                class="d-flex align-items-center text-gray-900 border border-gray-300 border-dashed rounded px-3 py-1 bg-light fs-8">
                                                <i class="ki-outline ki-fingerprint-scan fs-6 me-2 text-primary"></i>
                                                <span class="fw-semibold">Last Login:&nbsp;</span>
                                                <span
                                                    class="text-gray-900 fw-bold">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Navbar-->

            @php
                $steps = [
                    1 => ['name' => 'Personal info', 'desc' => 'Review identity and contact details'],
                    2 => ['name' => 'Care Types', 'desc' => 'Review selected specializations'],
                    3 => ['name' => 'Education', 'desc' => 'Review degrees and certifications'],
                    4 => ['name' => 'Work History', 'desc' => 'Review past employment records'],
                    5 => ['name' => 'Documents', 'desc' => 'Review uploaded legal documents'],
                    6 => ['name' => 'Availability', 'desc' => 'Review shift preferences and schedules'],
                ];
            @endphp

            <div class="row gx-8">

                <!-- Left Sidebar: Uber Style Vertical Nav -->
                <div class="col-xl-3 mb-8 mb-xl-0">
                    <div class="d-flex flex-column gap-2" id="verification-nav">

                        @foreach($steps as $stepId => $stepData)
                            @php
                                $verification = $profile->verifications->where('step_id', $stepId)->first();
                                $status = $verification ? $verification->status : \App\Models\NurseProfileVerification::STATUS_PENDING;

                                // Default Pending (Empty)
                                $circleClass = 'bg-light border-gray-300 text-gray-500 border';
                                $iconContent = '<span class="fw-medium fs-8">' . $stepId . '</span>';

                                if ($status == \App\Models\NurseProfileVerification::STATUS_APPROVED) {
                                    $circleClass = 'bg-light-success border-success border';
                                    $iconContent = '<i class="ki-outline ki-check fs-7 text-success"></i>';
                                } elseif ($status == \App\Models\NurseProfileVerification::STATUS_REJECTED) {
                                    $circleClass = 'bg-light-danger border-danger border';
                                    $iconContent = '<i class="ki-outline ki-cross fs-7 text-danger"></i>';
                                }
                            @endphp

                            <div class="step-nav-item cursor-pointer d-flex align-items-center px-4 py-3 rounded transition-all {{ $loop->first ? 'bg-light-primary border border-primary' : 'hover-bg-light border border-transparent' }}"
                                data-step="{{ $stepId }}" onclick="showStep({{ $stepId }}, this)">

                                <div
                                    class="w-25px h-25px rounded d-flex align-items-center justify-content-center me-4 {{ $circleClass }}">
                                    {!! $iconContent !!}
                                </div>
                                <span
                                    class="fw-semibold fs-7 {{ $loop->first ? 'text-primary' : 'text-gray-900' }} nav-label">{{ $stepData['name'] }}</span>
                            </div>
                        @endforeach

                        <div class="separator separator-dashed my-2 border-gray-300"></div>

                        <!-- Final Approval Tab -->
                        @if(!$isReadOnly)
                            <div class="step-nav-item cursor-pointer d-flex align-items-center px-4 py-3 rounded transition-all hover-bg-light border border-transparent"
                                data-step="final" onclick="showStep('final', this)">
                                <div
                                    class="w-25px h-25px rounded bg-dark d-flex align-items-center justify-content-center me-4">
                                    <span class="text-white fw-bold fs-8">F</span>
                                </div>
                                <span class="fw-semibold fs-7 text-gray-900 nav-label">Final decision</span>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Right Side: Content Sections -->
                <div class="col-xl-9">
                    <div id="step-content-container">
                        <!-- Content will be loaded here via AJAX -->
                        <div class="card shadow-none border border-gray-300 bg-white">
                            <div class="card-body py-10 text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="text-gray-600 mt-4 fw-semibold">Loading section details...</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <x-comments type="{{ \App\Models\Comment::TYPE_NURSE }}" :model-id="$user->id" />

        </div>
    </div>

@endsection

@push('styles')
    <style>
        .step-nav-item.transition-all {
            transition: all 0.2s ease;
        }

        .hover-bg-light:hover {
            background-color: var(--bs-gray-100);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load first step automatically
            const firstStep = document.querySelector('.step-nav-item');
            if (firstStep) {
                firstStep.click();
            }
        });

        function showStep(stepId, element) {
            // Reset nav styles
            document.querySelectorAll('.step-nav-item').forEach(el => {
                el.classList.remove('bg-light-primary', 'border-primary');
                el.classList.add('hover-bg-light', 'border-transparent');

                let label = el.querySelector('.nav-label');
                if (label) {
                    label.classList.remove('text-primary');
                    label.classList.add('text-gray-900');
                }
            });

            // Set active style
            element.classList.remove('hover-bg-light', 'border-transparent');
            element.classList.add('bg-light-primary', 'border-primary');

            let activeLabel = element.querySelector('.nav-label');
            if (activeLabel) {
                activeLabel.classList.remove('text-gray-900');
                activeLabel.classList.add('text-primary');
            }

            // Show loading state
            const container = document.getElementById('step-content-container');
            container.innerHTML = `
                        <div class="card shadow-none border border-gray-300 bg-white">
                            <div class="card-header border-0 pt-8 pb-4">
                                <h3 class="card-title align-items-start flex-column w-100 placeholder-glow">
                                    <span class="placeholder col-4 bg-secondary rounded mb-2" style="height:25px;"></span>
                                    <span class="placeholder col-6 bg-secondary rounded" style="height:15px;"></span>
                                </h3>
                            </div>
                            <div class="card-body pt-0 pb-8">
                                @include('admin.layouts.partials._table-skeleton', ['id' => 'review-skeleton'])
                            </div>
                        </div>
                    `;

            // Load via AJAX
            let isReadOnlyParam = '{{ $isReadOnly ? "1" : "0" }}';
            let url = `{{ url('admin/nurses') }}/{{ $user->id }}/review-step-view/${stepId}?readonly=${isReadOnlyParam}`;

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                })
                .catch(error => {
                    container.innerHTML = `
                                <div class="card shadow-none border border-danger bg-light-danger">
                                    <div class="card-body py-10 text-center">
                                        <i class="ki-outline ki-cross-circle fs-3x text-danger mb-4"></i>
                                        <div class="text-danger fw-bold fs-6">Failed to load section data. Please try again.</div>
                                    </div>
                                </div>
                            `;
                });
        }

        function processStepReview(stepId, status, existingReason = '') {
            if (status === {{ \App\Models\NurseProfileVerification::STATUS_REJECTED }}) {
                Swal.fire({
                    html: `
                        <div class="d-flex flex-column text-start">
                            <div class="d-flex align-items-center mb-4">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-danger">
                                        <i class="ki-outline ki-cross-square fs-1 text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="fw-bold text-gray-900 mb-1 fs-5">Reject Section</h4>
                                    <span class="text-gray-500 fs-8">Please provide a reason to help the nurse fix this.</span>
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-4"></div>
                            <div class="d-flex flex-column mb-2 fv-row">
                                <label class="d-flex align-items-center fs-7 fw-semibold mb-2">
                                    <span>Rejection Reason</span>
                                </label>
                                <textarea id="swal-step-reject-reason" class="form-control form-control-sm fs-7 resize-none" rows="3" placeholder="Type your detailed reason here...">${existingReason}</textarea>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: 'Submit Rejection',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'shadow-sm',
                        confirmButton: 'btn btn-sm btn-danger fw-bold px-5',
                        cancelButton: 'btn btn-sm btn-light btn-active-light-primary fw-bold px-5'
                    },
                    preConfirm: () => {
                        return document.getElementById('swal-step-reject-reason').value.trim();
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitStepReview(stepId, status, result.value);
                    }
                });
            } else {
                submitStepReview(stepId, status, null);
            }
        }

        function submitStepReview(stepId, status, reason) {
            $.ajax({
                url: '{{ route("admin.nurses.review-step", $user->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    step_id: stepId,
                    status: status,
                    reason: reason
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Update sidebar dynamically instead of reloading page
                        updateSidebarStatus(stepId, status);

                        // Reload the step content to reflect changes in the header badge
                        const activeStepElement = document.querySelector(`.step-nav-item[data-step="${stepId}"]`);
                        if (activeStepElement) {
                            showStep(stepId, activeStepElement);
                        }
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', 'An error occurred while saving the verification.', 'error');
                }
            });
        }

        function updateSidebarStatus(stepId, status) {
            const navItem = document.querySelector(`.step-nav-item[data-step="${stepId}"]`);
            if (!navItem) return;

            const iconContainer = navItem.querySelector('.w-25px.h-25px.rounded');
            if (!iconContainer) return;

            // Reset classes
            iconContainer.className = 'w-25px h-25px rounded d-flex align-items-center justify-content-center me-4';

            if (status == {{ \App\Models\NurseProfileVerification::STATUS_APPROVED }}) {
                iconContainer.classList.add('bg-light-success', 'border', 'border-success');
                iconContainer.innerHTML = '<i class="ki-outline ki-check fs-7 text-success"></i>';
            } else if (status == {{ \App\Models\NurseProfileVerification::STATUS_REJECTED }}) {
                iconContainer.classList.add('bg-light-danger', 'border', 'border-danger');
                iconContainer.innerHTML = '<i class="ki-outline ki-cross fs-7 text-danger"></i>';
            } else {
                iconContainer.classList.add('bg-light', 'border-gray-300', 'text-gray-500', 'border');
                iconContainer.innerHTML = `<span class="fw-medium fs-8">${stepId}</span>`;
            }
        }

        function finalizeReview(status) {
            if (status === {{ \App\Models\NurseProfile::STATUS_REJECTED }}) {
                Swal.fire({
                    html: `
                        <div class="d-flex flex-column text-start">
                            <div class="d-flex align-items-center mb-4">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-danger">
                                        <i class="ki-outline ki-shield-cross fs-1 text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="fw-bold text-gray-900 mb-1 fs-5">Reject Application</h4>
                                    <span class="text-gray-500 fs-8">This will mark the nurse's profile as rejected.</span>
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-4"></div>
                            <div class="d-flex flex-column mb-4 fv-row">
                                <label class="d-flex align-items-center fs-7 fw-semibold mb-2">
                                    <span class="required">Rejection Reason</span>
                                </label>
                                <textarea id="swal-reject-reason" class="form-control form-control-sm fs-7 resize-none" rows="3" placeholder="Explain why the application was rejected..."></textarea>
                            </div>
                            <div class="d-flex align-items-center bg-light-warning border border-warning border-dashed rounded p-3">
                                <div class="d-flex flex-stack w-100">
                                    <div class="d-flex flex-column me-2">
                                        <span class="fw-bold text-gray-900 fs-7">Allow Reapplication</span>
                                    </div>
                                    <div class="form-check form-check-custom form-check-sm form-check-solid form-check-warning form-switch">
                                        <input class="form-check-input w-35px h-20px" type="checkbox" id="swal-can-reapply" checked />
                                    </div>
                                </div>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: 'Confirm Rejection',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'shadow-sm',
                        confirmButton: 'btn btn-sm btn-danger fw-bold px-5',
                        cancelButton: 'btn btn-sm btn-light btn-active-light-primary fw-bold px-5'
                    },
                    preConfirm: () => {
                        const reason = document.getElementById('swal-reject-reason').value.trim();
                        const canReapply = document.getElementById('swal-can-reapply').checked ? 1 : 0;
                        if (!reason) {
                            Swal.showValidationMessage('You need to provide a reason!');
                            return false;
                        }
                        return { reason: reason, canReapply: canReapply };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitFinalReview(status, result.value.reason, result.value.canReapply);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Approve Application',
                    text: 'Are you sure you want to officially approve this nurse?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Approve',
                    confirmButtonColor: '#252f4a', // Dark theme color
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitFinalReview(status, null, null);
                    }
                });
            }
        }

        function submitFinalReview(status, reason, canReapply) {
            $.ajax({
                url: '{{ route("admin.nurses.finalize-review", $user->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status,
                    reason: reason,
                    can_reapply: canReapply
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Completed',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '{{ route("admin.nurses.index") }}';
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', 'An error occurred while finalizing.', 'error');
                }
            });
        }
    </script>
@endpush