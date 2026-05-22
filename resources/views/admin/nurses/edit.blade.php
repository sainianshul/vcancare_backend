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

            <!-- Top Warning Alert -->
            <div class="alert alert-dismissible bg-light-warning border border-warning d-flex flex-column flex-sm-row p-4 mb-7">
                <i class="ki-outline ki-information-5 fs-1 text-warning mb-2 mb-sm-0 me-3"></i>
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <h5 class="fw-semibold text-warning mb-1 fs-6">Important Notice</h5>
                    <span class="text-gray-700 fs-7">You cannot edit the Nurse's core Onboarding data (e.g., Education, Work History, Documents) from this screen. To update those, the nurse must re-submit their onboarding application.</span>
                </div>
            </div>

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
                                            <input type="text" name="emergency_contact_phone" class="form-control form-control-sm text-gray-900 border border-gray-300 bg-transparent ps-10 fs-7" value="{{ old('emergency_contact_phone', $user->nurseProfile->emergency_contact_phone ?? '') }}" />
                                        </div>
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
                                <label class="form-label text-gray-700 fw-semibold fs-7 mb-1">Select Care Types</label>
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
    });
</script>
@endpush
