@extends('admin.layouts.app')

@section('title', 'Add New Nurse')

@section('page_title', 'Create Nurse Profile')

@section('content')

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Add New Nurse" description="Create a new nurse profile manually" />
                <x-breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['label' => 'Nurses', 'url' => route('admin.nurses.index')],
                    ['label' => 'Add Nurse'],
                ]" />
            </div>
            
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.nurses.index') }}" class="btn btn-sm btn-light fw-bold fs-7">
                    Cancel
                </a>
                <button type="submit" form="kt_nurse_create_form" class="btn btn-sm btn-primary fw-bold shadow-sm fs-7">
                    <i class="ki-outline ki-check fs-5 me-1"></i>Create Nurse
                </button>
            </div>
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                    <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Error</h4>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <x-alert-success />
            <x-form-errors />

            <!--begin::Form-->
            <form id="kt_nurse_create_form" action="{{ route('admin.nurses.store') }}" method="POST" enctype="multipart/form-data" class="form d-flex flex-column gap-5 gap-lg-7">
                @csrf
                
                <div class="row g-5 g-lg-7">
                    <!-- LEFT COLUMN -->
                    <div class="col-lg-8">
                        
                        <!-- Personal Info Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-user fs-3 text-primary me-2"></i>Personal Details
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="row mb-5">
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Full Name</label>
                                        <div class="position-relative">
                                            <i class="ki-outline ki-user-edit fs-4 position-absolute top-50 translate-middle-y ms-3 text-gray-500"></i>
                                            <input type="text" name="name" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7 @error('name') is-invalid @enderror" value="{{ old('name') }}" required />
                                        </div>
                                        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Email Address</label>
                                        <div class="position-relative">
                                            <i class="ki-outline ki-sms fs-4 position-absolute top-50 translate-middle-y ms-3 text-gray-500"></i>
                                            <input type="email" name="email" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7 @error('email') is-invalid @enderror" value="{{ old('email') }}" required />
                                        </div>
                                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Phone Number</label>
                                        <div class="position-relative">
                                            <i class="ki-outline ki-phone fs-4 position-absolute top-50 translate-middle-y ms-3 text-gray-500"></i>
                                            <input type="text" name="phone" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required />
                                        </div>
                                        @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Emergency Contact</label>
                                        <div class="position-relative">
                                            <i class="ki-outline ki-call fs-4 position-absolute top-50 translate-middle-y ms-3 text-danger"></i>
                                            <input type="text" name="emergency_contact_phone" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7 @error('emergency_contact_phone') is-invalid @enderror" value="{{ old('emergency_contact_phone') }}" />
                                        </div>
                                        @error('emergency_contact_phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-12">
                                        <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Bio / Description</label>
                                        <textarea name="bio" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7 @error('bio') is-invalid @enderror" rows="3">{{ old('bio') }}</textarea>
                                        @error('bio') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-geolocation fs-3 text-info me-2"></i>Location Details
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="row mb-5">
                                    <div class="col-12">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Full Address</label>
                                        <div class="position-relative">
                                            <i class="ki-outline ki-map fs-4 position-absolute top-50 translate-middle-y ms-3 text-gray-500"></i>
                                            <input type="text" name="address" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7 @error('address') is-invalid @enderror" value="{{ old('address') }}" required />
                                        </div>
                                        @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">City</label>
                                        <input type="text" name="city" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7 @error('city') is-invalid @enderror" value="{{ old('city') }}" required />
                                        @error('city') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">State</label>
                                        <input type="text" name="state" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7 @error('state') is-invalid @enderror" value="{{ old('state') }}" required />
                                        @error('state') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Country</label>
                                        <input type="text" name="country" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7 @error('country') is-invalid @enderror" value="{{ old('country', 'India') }}" required />
                                        @error('country') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Pincode</label>
                                        <input type="text" name="pincode" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7 @error('pincode') is-invalid @enderror" value="{{ old('pincode') }}" required />
                                        @error('pincode') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Latitude</label>
                                        <input type="text" name="latitude" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7 @error('latitude') is-invalid @enderror" value="{{ old('latitude', '0') }}" required />
                                        @error('latitude') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Longitude</label>
                                        <input type="text" name="longitude" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7 @error('longitude') is-invalid @enderror" value="{{ old('longitude', '0') }}" required />
                                        @error('longitude') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Education Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-book-open fs-3 text-primary me-2"></i>Education History
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div id="educations-container">
                                    @php
                                        $oldEducations = old('educations', [[]]);
                                    @endphp
                                    @foreach($oldEducations as $index => $edu)
                                    <div class="row g-3 mb-3 education-row">
                                        <div class="col-md-3">
                                            <input type="text" name="educations[{{ $index }}][degree_name]" class="form-control form-control-sm @error("educations.$index.degree_name") is-invalid @enderror" placeholder="Degree Name (e.g. B.Sc Nursing)" value="{{ $edu['degree_name'] ?? '' }}">
                                            @error("educations.$index.degree_name") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="educations[{{ $index }}][institution_name]" class="form-control form-control-sm @error("educations.$index.institution_name") is-invalid @enderror" placeholder="Institution / College" value="{{ $edu['institution_name'] ?? '' }}">
                                            @error("educations.$index.institution_name") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="educations[{{ $index }}][start_date]" class="form-control form-control-sm @error("educations.$index.start_date") is-invalid @enderror" value="{{ $edu['start_date'] ?? '' }}">
                                            @error("educations.$index.start_date") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="educations[{{ $index }}][end_date]" class="form-control form-control-sm @error("educations.$index.end_date") is-invalid @enderror" placeholder="End Date" value="{{ $edu['end_date'] ?? '' }}">
                                            @error("educations.$index.end_date") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-2 d-flex align-items-center gap-2">
                                            @if($loop->index > 0)
                                                <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-edu"><i class="ki-outline ki-trash fs-6"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary mt-3" id="add-education">
                                    <i class="ki-outline ki-plus fs-6"></i> Add Another Degree
                                </button>
                                <div class="text-muted fs-8 mt-2">Leave blank if not applicable. To remove, delete the row.</div>
                            </div>
                        </div>

                        <!-- Work Experience Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-briefcase fs-3 text-info me-2"></i>Work Experience
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div id="experiences-container">
                                    @php
                                        $oldExperiences = old('experiences', [[]]);
                                    @endphp
                                    @foreach($oldExperiences as $index => $exp)
                                    <div class="row g-3 mb-3 experience-row">
                                        <div class="col-md-3">
                                            <input type="text" name="experiences[{{ $index }}][designation]" class="form-control form-control-sm @error("experiences.$index.designation") is-invalid @enderror" placeholder="Designation / Role" value="{{ $exp['designation'] ?? '' }}">
                                            @error("experiences.$index.designation") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="experiences[{{ $index }}][hospital_name]" class="form-control form-control-sm @error("experiences.$index.hospital_name") is-invalid @enderror" placeholder="Hospital / Clinic Name" value="{{ $exp['hospital_name'] ?? '' }}">
                                            @error("experiences.$index.hospital_name") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="experiences[{{ $index }}][start_date]" class="form-control form-control-sm @error("experiences.$index.start_date") is-invalid @enderror" value="{{ $exp['start_date'] ?? '' }}">
                                            @error("experiences.$index.start_date") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="experiences[{{ $index }}][end_date]" class="form-control form-control-sm @error("experiences.$index.end_date") is-invalid @enderror" value="{{ $exp['end_date'] ?? '' }}">
                                            @error("experiences.$index.end_date") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-2 d-flex flex-column gap-2">
                                            <div class="form-check form-check-sm">
                                                <input class="form-check-input" type="checkbox" name="experiences[{{ $index }}][is_currently_working]" value="1" {{ !empty($exp['is_currently_working']) ? 'checked' : '' }}>
                                                <label class="form-check-label fs-8">Present</label>
                                            </div>
                                            @if($loop->index > 0)
                                                <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-exp"><i class="ki-outline ki-trash fs-6"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-light-info mt-3" id="add-experience">
                                    <i class="ki-outline ki-plus fs-6"></i> Add Another Experience
                                </button>
                                <div class="text-muted fs-8 mt-2">Leave blank if not applicable. To remove, delete the row.</div>
                            </div>
                        </div>

                        <!-- Documents Upload Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-file fs-3 text-warning me-2"></i>Verification Documents
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <p class="text-muted fs-7 mb-5">Upload specific documents required for the nurse's onboarding verification. (PDF, JPG, PNG)</p>
                                <div class="row g-5">
                                    @php
                                        $documentTypes = \App\Models\NurseDocument::getDocumentTypeList();
                                    @endphp
                                    @foreach($documentTypes as $id => $label)
                                    <div class="col-md-6">
                                        <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">{{ $label }}</label>
                                        <input type="file" name="documents[{{ $id }}]" class="form-control form-control-sm border border-gray-300 bg-transparent @error('documents.'.$id) is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                        @error('documents.'.$id) <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-lg-4">

                        <!-- Profile Photo Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-picture fs-3 text-warning me-2"></i>Profile Image
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2 text-center">
                                <div class="position-relative d-inline-block mb-3">
                                    <!-- Avatar Wrapper -->
                                    <div class="rounded-circle border border-3 border-body shadow-sm overflow-hidden" style="width: 125px; height: 125px; background-color: var(--bs-gray-100);">
                                        <div id="avatar-preview-placeholder" class="w-100 h-100 d-flex align-items-center justify-content-center bg-light-primary text-primary fw-bold fs-2x">
                                            <i class="ki-outline ki-user fs-3x text-primary"></i>
                                        </div>
                                        <img id="avatar-preview" src="" class="w-100 h-100 object-fit-cover d-none" alt="Profile Photo" />
                                    </div>

                                    <!-- Edit Pencil Button -->
                                    <label for="avatar-upload" class="btn btn-icon btn-circle btn-active-color-primary w-30px h-30px bg-body shadow-sm position-absolute transition-all hover-scale" style="bottom: 5px; right: -5px; border: 1px solid #E4E6EF; cursor: pointer;" title="Change Profile Image">
                                        <i class="ki-outline ki-pencil fs-6 text-gray-700"></i>
                                    </label>

                                    <input type="file" name="profile_photo" id="avatar-upload" accept=".png, .jpg, .jpeg" class="d-none" />
                                </div>
                                @error('profile_photo')
                                    <div class="invalid-feedback d-block text-center mt-2">{{ $message }}</div>
                                @enderror
                                <div class="text-muted fs-8 mt-2">Allowed file types: png, jpg, jpeg. Max size: 2MB.</div>
                            </div>
                        </div>
                        
                        <!-- Account Creation & Approval Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-shield-tick fs-3 text-success me-2"></i>Onboarding Settings
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="d-flex flex-column gap-5">
                                    <!-- Auto Approve Checkbox -->
                                    <div>
                                        <div class="form-check form-check-custom form-check-solid mb-2">
                                            <input class="form-check-input" type="checkbox" name="auto_approve" value="1" id="auto_approve_check" checked />
                                            <label class="form-check-label fw-bold fs-7 text-gray-800" for="auto_approve_check">
                                                Auto-Approve & Make Live
                                            </label>
                                        </div>
                                        <div class="text-muted fs-8 ms-8">If checked, the nurse's profile will bypass the manual review process and immediately become active on the platform. All documents uploaded will be marked as "Approved".</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status & Schedule Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-time fs-3 text-primary me-2"></i>Availability & Settings
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="d-flex flex-column gap-5">
                                    <div>
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-2 d-block">Is Available?</label>
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input h-25px w-45px" type="checkbox" name="is_available" value="1" checked />
                                            <label class="form-check-label fw-bold fs-7 text-gray-800 ms-3">Currently Accepting Bookings</label>
                                        </div>
                                        @error('is_available') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <!-- Hidden input to handle unchecked checkbox -->
                                    <input type="hidden" name="is_available" value="0" id="is_available_hidden" disabled>

                                    <script>
                                        document.querySelector('input[type="checkbox"][name="is_available"]').addEventListener('change', function() {
                                            document.getElementById('is_available_hidden').disabled = this.checked;
                                        });
                                    </script>

                                    <div class="separator separator-dashed border-gray-300"></div>

                                    <div>
                                        <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Available Days</label>
                                        @php
                                            $days = [0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];
                                        @endphp
                                        <select class="form-select form-select-sm form-select-solid border border-gray-300 bg-transparent fs-7 @error('available_days') is-invalid @enderror" name="available_days[]" data-control="select2" data-placeholder="Select working days" multiple="multiple">
                                            @foreach($days as $val => $label)
                                                <option value="{{ $val }}" selected>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('available_days') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Start Time</label>
                                            <input type="time" name="available_from" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7 @error('available_from') is-invalid @enderror" value="{{ old('available_from', '09:00') }}" />
                                            @error('available_from') <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">End Time</label>
                                            <input type="time" name="available_to" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7 @error('available_to') is-invalid @enderror" value="{{ old('available_to', '18:00') }}" />
                                            @error('available_to') <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Care Types Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-heart fs-3 text-danger me-2"></i>Care Specializations
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <label class="form-label text-gray-700 fw-semibold fs-7 mb-1 required">Select Care Types</label>
                                <select class="form-select form-select-sm form-select-solid border border-gray-300 bg-transparent fs-7 @error('care_types') is-invalid @enderror" name="care_types[]" data-control="select2" data-placeholder="Select specializations" multiple="multiple">
                                    @foreach($careTypes as $careType)
                                        <option value="{{ $careType->id }}">
                                            {{ $careType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('care_types') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
@endsection

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        .hover-scale:hover { transform: scale(1.1); }
    </style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 manually with Bootstrap 5 Theme
        $('[data-control="select2"]').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // ── Image upload preview ──
        $('#avatar-upload').on('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#avatar-preview').attr('src', e.target.result).removeClass('d-none');
                $('#avatar-preview-placeholder').addClass('d-none');
            };
            reader.readAsDataURL(file);
        });

        // ── Repeaters for Education and Experience ──
        let eduIndex = {{ count(old('educations', [[]])) }};
        $('#add-education').click(function() {
            $('#educations-container').append(`
                <div class="row g-3 mb-3 education-row">
                    <div class="col-md-3"><input type="text" name="educations[${eduIndex}][degree_name]" class="form-control form-control-sm" placeholder="Degree Name (e.g. B.Sc Nursing)"></div>
                    <div class="col-md-3"><input type="text" name="educations[${eduIndex}][institution_name]" class="form-control form-control-sm" placeholder="Institution / College"></div>
                    <div class="col-md-2"><input type="date" name="educations[${eduIndex}][start_date]" class="form-control form-control-sm"></div>
                    <div class="col-md-2"><input type="date" name="educations[${eduIndex}][end_date]" class="form-control form-control-sm" placeholder="End Date"></div>
                    <div class="col-md-2 d-flex align-items-center gap-2"><button type="button" class="btn btn-icon btn-sm btn-light-danger remove-edu"><i class="ki-outline ki-trash fs-6"></i></button></div>
                </div>
            `);
            eduIndex++;
        });
        $(document).on('click', '.remove-edu', function() { $(this).closest('.education-row').remove(); });

        let expIndex = {{ count(old('experiences', [[]])) }};
        $('#add-experience').click(function() {
            $('#experiences-container').append(`
                <div class="row g-3 mb-3 experience-row">
                    <div class="col-md-3"><input type="text" name="experiences[${expIndex}][designation]" class="form-control form-control-sm" placeholder="Designation / Role"></div>
                    <div class="col-md-3"><input type="text" name="experiences[${expIndex}][hospital_name]" class="form-control form-control-sm" placeholder="Hospital / Clinic Name"></div>
                    <div class="col-md-2"><input type="date" name="experiences[${expIndex}][start_date]" class="form-control form-control-sm"></div>
                    <div class="col-md-2"><input type="date" name="experiences[${expIndex}][end_date]" class="form-control form-control-sm"></div>
                    <div class="col-md-2 d-flex align-items-center gap-2">
                        <div class="form-check form-check-sm"><input class="form-check-input" type="checkbox" name="experiences[${expIndex}][is_currently_working]" value="1"><label class="form-check-label fs-8">Present</label></div>
                        <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-exp"><i class="ki-outline ki-trash fs-6"></i></button>
                    </div>
                </div>
            `);
            expIndex++;
        });
        $(document).on('click', '.remove-exp', function() { $(this).closest('.experience-row').remove(); });
    });
</script>
@endpush
