@extends('admin.layouts.app')

@section('title', 'Edit Nurse: ' . $user->name)

@section('page_title', 'Edit Nurse Profile')

@section('content')

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Edit Nurse: {{ $user->name }}" description="Update nurse profile details and settings" />
                <x-breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['label' => 'Nurses', 'url' => route('admin.nurses.index')],
                    ['label' => $user->name, 'url' => route('admin.nurses.show', $user->id)],
                    ['label' => 'Edit Profile'],
                ]" />
            </div>
            
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.nurses.show', $user->id) }}" class="btn btn-sm btn-light fw-bold fs-7">
                    Cancel
                </a>
                <button type="submit" form="kt_nurse_edit_form" class="btn btn-sm btn-primary fw-bold shadow-sm fs-7">
                    <i class="ki-outline ki-check fs-5 me-1"></i>Save Changes
                </button>
            </div>
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <x-alert-success />
            <x-form-errors />



            <!--begin::Form-->
            <form id="kt_nurse_edit_form" action="{{ route('admin.nurses.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="form d-flex flex-column gap-5 gap-lg-7">
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
                                            <input type="text" name="name" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7" value="{{ old('name', $user->name) }}" required />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Email Address</label>
                                        <div class="position-relative">
                                            <i class="ki-outline ki-sms fs-4 position-absolute top-50 translate-middle-y ms-3 text-gray-500"></i>
                                            <input type="email" name="email" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7" value="{{ old('email', $user->email) }}" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Phone Number</label>
                                        <div class="position-relative">
                                            <i class="ki-outline ki-phone fs-4 position-absolute top-50 translate-middle-y ms-3 text-gray-500"></i>
                                            <input type="text" name="phone" class="form-control form-control-sm text-gray-500 border border-gray-300 bg-secondary ps-10 fs-7" value="{{ old('phone', $user->phone) }}" disabled readonly />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Emergency Contact</label>
                                        <div class="position-relative">
                                            <i class="ki-outline ki-call fs-4 position-absolute top-50 translate-middle-y ms-3 text-danger"></i>
                                            <input type="text" name="emergency_contact_phone" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7 @error('emergency_contact_phone') is-invalid @enderror" value="{{ old('emergency_contact_phone', $user->nurseProfile->emergency_contact_phone ?? '') }}" />
                                        </div>
                                        @error('emergency_contact_phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-12">
                                        <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Bio / Description</label>
                                        <textarea name="bio" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7" rows="3">{{ old('bio', $user->nurseProfile->bio ?? '') }}</textarea>
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
                                            <input type="text" name="address" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7" value="{{ old('address', $user->nurseProfile->address ?? '') }}" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">City</label>
                                        <input type="text" name="city" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7" value="{{ old('city', $user->nurseProfile->city ?? '') }}" required />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">State</label>
                                        <input type="text" name="state" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7" value="{{ old('state', $user->nurseProfile->state ?? '') }}" required />
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Country</label>
                                        <input type="text" name="country" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7" value="{{ old('country', $user->nurseProfile->country ?? '') }}" required />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Pincode</label>
                                        <input type="text" name="pincode" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7" value="{{ old('pincode', $user->nurseProfile->pincode ?? '') }}" required />
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-lg-6 mb-5 mb-lg-0">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Latitude</label>
                                        <input type="text" name="latitude" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7" value="{{ old('latitude', $user->nurseProfile->latitude ?? '') }}" required />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-1">Longitude</label>
                                        <input type="text" name="longitude" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7" value="{{ old('longitude', $user->nurseProfile->longitude ?? '') }}" required />
                                    </div>
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
                                        @if($user->profile_photo)
                                            <img id="avatar-preview" src="{{ Storage::url($user->profile_photo) }}" class="w-100 h-100 object-fit-cover" alt="Profile Photo" />
                                        @else
                                            <div id="avatar-preview-placeholder" class="w-100 h-100 d-flex align-items-center justify-content-center bg-light-primary text-primary fw-bold fs-2x">
                                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                            </div>
                                            <img id="avatar-preview" src="" class="w-100 h-100 object-fit-cover d-none" alt="Profile Photo" />
                                        @endif
                                    </div>

                                    <!-- Edit Pencil Button -->
                                    <label for="avatar-upload" class="btn btn-icon btn-circle btn-active-color-primary w-30px h-30px bg-body shadow-sm position-absolute transition-all hover-scale" style="bottom: 5px; right: -5px; border: 1px solid #E4E6EF; cursor: pointer;" title="Change Profile Image">
                                        <i class="ki-outline ki-pencil fs-6 text-gray-700"></i>
                                    </label>

                                    <input type="file" name="profile_photo" id="avatar-upload" accept=".png, .jpg, .jpeg" class="d-none" />
                                    @error('profile_photo')
                                        <div class="invalid-feedback d-block text-center mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="text-muted fs-8 mt-2">Allowed file types: png, jpg, jpeg. Max size: 2MB.</div>
                            </div>
                        </div>

                        <!-- Status Card -->
                        <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm mb-5 mb-lg-7">
                            <div class="card-header border-0 pt-4 min-h-40px">
                                <div class="card-title">
                                    <h2 class="fs-6 fw-bold text-gray-800 text-uppercase m-0">
                                        <i class="ki-outline ki-shield-tick fs-3 text-success me-2"></i>Status & Settings
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="d-flex flex-column gap-5">
                                    <div>
                                        <label class="required form-label text-gray-700 fw-semibold fs-7 mb-2 d-block">Is Available?</label>
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input h-25px w-45px" type="checkbox" name="is_available" value="1" {{ old('is_available', $user->nurseProfile->is_available ?? false) ? 'checked' : '' }} />
                                            <label class="form-check-label fw-bold fs-7 text-gray-800 ms-3">Currently Accepting Bookings</label>
                                        </div>
                                    </div>
                                    
                                    <!-- Hidden input to handle unchecked checkbox -->
                                    <input type="hidden" name="is_available" value="0" {{ old('is_available', $user->nurseProfile->is_available ?? false) ? 'disabled' : '' }} id="is_available_hidden">

                                    <script>
                                        document.querySelector('input[type="checkbox"][name="is_available"]').addEventListener('change', function() {
                                            document.getElementById('is_available_hidden').disabled = this.checked;
                                        });
                                    </script>

                                    <div class="separator separator-dashed border-gray-300"></div>

                                    <div>
                                        <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Available Days</label>
                                        @php
                                            $selectedDays = old('available_days', $user->nurseProfile->available_days ?? []);
                                            $days = [0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];
                                        @endphp
                                        <select class="form-select form-select-sm form-select-solid border border-gray-300 bg-transparent fs-7" name="available_days[]" data-control="select2" data-placeholder="Select working days" multiple="multiple">
                                            @foreach($days as $val => $label)
                                                <option value="{{ $val }}" {{ in_array($val, $selectedDays) ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Start Time</label>
                                            <input type="time" name="available_from" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7" value="{{ old('available_from', $user->nurseProfile->available_from ? \Carbon\Carbon::parse($user->nurseProfile->available_from)->format('H:i') : '') }}" />
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">End Time</label>
                                            <input type="time" name="available_to" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent fs-7" value="{{ old('available_to', $user->nurseProfile->available_to ? \Carbon\Carbon::parse($user->nurseProfile->available_to)->format('H:i') : '') }}" />
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
                                @php
                                    $selectedCareTypes = old('care_types', $user->nurseProfile->careTypes->pluck('id')->toArray() ?? []);
                                @endphp
                                <select class="form-select form-select-sm form-select-solid border border-gray-300 bg-transparent fs-7" name="care_types[]" data-control="select2" data-placeholder="Select specializations" multiple="multiple">
                                    @foreach($careTypes as $careType)
                                        <option value="{{ $careType->id }}" {{ in_array($careType->id, $selectedCareTypes) ? 'selected' : '' }}>
                                            {{ $careType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> <!-- end card -->
                    </div> <!-- end col-lg-4 -->
                </div> <!-- end row -->

            <div class="separator separator-dashed border-gray-300 my-8"></div>
            
            <h2 class="fs-3 fw-bold text-gray-900 mb-5">Professional History & Verification</h2>

            <div class="row g-5 g-lg-7">
                <div class="col-lg-12">
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
                                    $oldEducations = old('educations', $user->nurseProfile->educations->toArray() ?: [[]]);
                                @endphp
                                @foreach($oldEducations as $index => $edu)
                                <div class="row g-3 mb-3 education-row">
                                    <div class="col-md-3">
                                        <input type="text" name="educations[{{ $index }}][degree_name]" class="form-control form-control-sm @error("educations.$index.degree_name") is-invalid @enderror" placeholder="Degree Name (e.g. B.Sc Nursing)" value="{{ $edu['degree_name'] ?? $edu['degree_or_course'] ?? '' }}">
                                        @error("educations.$index.degree_name") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="educations[{{ $index }}][institution_name]" class="form-control form-control-sm @error("educations.$index.institution_name") is-invalid @enderror" placeholder="Institution / College" value="{{ $edu['institution_name'] ?? $edu['institute_name'] ?? '' }}">
                                        @error("educations.$index.institution_name") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="educations[{{ $index }}][start_date]" class="form-control form-control-sm @error("educations.$index.start_date") is-invalid @enderror" value="{{ isset($edu['start_date']) ? \Carbon\Carbon::parse($edu['start_date'])->format('Y-m-d') : (isset($edu['start_year']) ? \Carbon\Carbon::parse($edu['start_year'])->format('Y-m-d') : '') }}">
                                        @error("educations.$index.start_date") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="educations[{{ $index }}][end_date]" class="form-control form-control-sm @error("educations.$index.end_date") is-invalid @enderror" placeholder="End Date" value="{{ isset($edu['end_date']) && $edu['end_date'] ? \Carbon\Carbon::parse($edu['end_date'])->format('Y-m-d') : (isset($edu['end_year']) && $edu['end_year'] ? \Carbon\Carbon::parse($edu['end_year'])->format('Y-m-d') : '') }}">
                                        @error("educations.$index.end_date") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center gap-2">
                                        @if($loop->index > 0 || count($oldEducations) > 1)
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
                </div>

                <div class="col-lg-12">
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
                                    $oldExperiences = old('experiences', $user->nurseProfile->workHistories->toArray() ?: [[]]);
                                @endphp
                                @foreach($oldExperiences as $index => $exp)
                                <div class="row g-3 mb-3 experience-row">
                                    <div class="col-md-3">
                                        <input type="text" name="experiences[{{ $index }}][designation]" class="form-control form-control-sm @error("experiences.$index.designation") is-invalid @enderror" placeholder="Designation / Role" value="{{ $exp['designation'] ?? $exp['role_or_position'] ?? '' }}">
                                        @error("experiences.$index.designation") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="experiences[{{ $index }}][hospital_name]" class="form-control form-control-sm @error("experiences.$index.hospital_name") is-invalid @enderror" placeholder="Hospital / Clinic Name" value="{{ $exp['hospital_name'] ?? $exp['organization_name'] ?? '' }}">
                                        @error("experiences.$index.hospital_name") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="experiences[{{ $index }}][start_date]" class="form-control form-control-sm @error("experiences.$index.start_date") is-invalid @enderror" value="{{ isset($exp['start_date']) ? \Carbon\Carbon::parse($exp['start_date'])->format('Y-m-d') : '' }}">
                                        @error("experiences.$index.start_date") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="experiences[{{ $index }}][end_date]" class="form-control form-control-sm @error("experiences.$index.end_date") is-invalid @enderror" value="{{ isset($exp['end_date']) && $exp['end_date'] ? \Carbon\Carbon::parse($exp['end_date'])->format('Y-m-d') : '' }}">
                                        @error("experiences.$index.end_date") <div class="invalid-feedback d-block fs-8">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2 d-flex flex-column gap-2">
                                        <div class="form-check form-check-sm">
                                            <input class="form-check-input" type="checkbox" name="experiences[{{ $index }}][is_currently_working]" value="1" {{ !empty($exp['is_currently_working']) ? 'checked' : '' }}>
                                            <label class="form-check-label fs-8">Present</label>
                                        </div>
                                        @if($loop->index > 0 || count($oldExperiences) > 1)
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
                </div>

                <div class="col-lg-12">
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
                            <p class="text-muted fs-7 mb-5">Upload new documents or view existing ones. Existing documents will be kept unless deleted.</p>
                            
                            <!-- Existing Documents -->
                            @if($user->nurseProfile->documents->count() > 0)
                            <div class="mb-5">
                                <h4 class="fs-6 fw-bold text-gray-800 mb-3">Existing Documents</h4>
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-7 gy-3">
                                        <thead>
                                            <tr class="text-start text-muted fw-bold text-uppercase gs-0">
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-800 fw-medium">
                                            @foreach($user->nurseProfile->documents as $doc)
                                            <tr id="doc-row-{{ $doc->id }}">
                                                <td>{{ $doc->title }}</td>
                                                <td>{{ strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) }}</td>
                                                <td>
                                                    @if($doc->status == \App\Models\NurseDocument::STATUS_APPROVED)
                                                        <span class="badge badge-light-success">Approved</span>
                                                    @elseif($doc->status == \App\Models\NurseDocument::STATUS_REJECTED)
                                                        <span class="badge badge-light-danger">Rejected</span>
                                                    @else
                                                        <span class="badge badge-light-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <!-- Hidden input to keep this document -->
                                                    <input type="hidden" name="existing_documents[]" value="{{ $doc->id }}">
                                                    
                                                    <a href="{{ route('admin.nurses.document', $doc->id) }}" target="_blank" class="btn btn-icon btn-sm btn-light-primary me-2" title="View"><i class="ki-outline ki-eye fs-6"></i></a>
                                                    <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-existing-doc" data-id="{{ $doc->id }}" title="Delete"><i class="ki-outline ki-trash fs-6"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            <div class="separator separator-dashed border-gray-300 my-5"></div>

                            <h4 class="fs-6 fw-bold text-gray-800 mb-3">Upload New Documents</h4>
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

        // Repeater JS
        let eduIndex = $('.education-row').length;
        $('#add-education').click(function() {
            let row = `
            <div class="row g-3 mb-3 education-row">
                <div class="col-md-3">
                    <input type="text" name="educations[`+eduIndex+`][degree_name]" class="form-control form-control-sm" placeholder="Degree Name (e.g. B.Sc Nursing)" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="educations[`+eduIndex+`][institution_name]" class="form-control form-control-sm" placeholder="Institution / College" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="educations[`+eduIndex+`][start_date]" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="educations[`+eduIndex+`][end_date]" class="form-control form-control-sm" placeholder="End Date">
                </div>
                <div class="col-md-2 d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-edu"><i class="ki-outline ki-trash fs-6"></i></button>
                </div>
            </div>`;
            $('#educations-container').append(row);
            eduIndex++;
        });

        $(document).on('click', '.remove-edu', function() {
            $(this).closest('.education-row').remove();
        });

        let expIndex = $('.experience-row').length;
        $('#add-experience').click(function() {
            let row = `
            <div class="row g-3 mb-3 experience-row">
                <div class="col-md-3">
                    <input type="text" name="experiences[`+expIndex+`][designation]" class="form-control form-control-sm" placeholder="Designation / Role" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="experiences[`+expIndex+`][hospital_name]" class="form-control form-control-sm" placeholder="Hospital / Clinic Name" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="experiences[`+expIndex+`][start_date]" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="experiences[`+expIndex+`][end_date]" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 d-flex flex-column gap-2">
                    <div class="form-check form-check-sm">
                        <input class="form-check-input" type="checkbox" name="experiences[`+expIndex+`][is_currently_working]" value="1">
                        <label class="form-check-label fs-8">Present</label>
                    </div>
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-exp"><i class="ki-outline ki-trash fs-6"></i></button>
                </div>
            </div>`;
            $('#experiences-container').append(row);
            expIndex++;
        });

        $(document).on('click', '.remove-exp', function() {
            $(this).closest('.experience-row').remove();
        });

        $(document).on('click', '.remove-existing-doc', function() {
            if(confirm('Are you sure you want to remove this document? It will be permanently deleted upon saving.')) {
                $(this).closest('tr').remove();
            }
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
    });
</script>
@endpush





