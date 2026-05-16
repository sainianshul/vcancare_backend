@extends('admin.layouts.app')

@section('title', 'Edit Patient')

@section('content')

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Edit Patient" description="Update patient details and status" />
                <x-breadcrumb :items="[
                    ['label' => 'People'],
                    ['label' => 'Patients', 'url' => route('admin.patients.index')],
                    ['label' => 'Edit Patient'],
                ]" />
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.patients.index') }}" class="btn btn-sm btn-light fw-semibold">
                    <i class="ki-outline ki-arrow-left fs-4 me-1"></i>Back
                </a>
                <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-sm btn-light-info border border-info fw-bold shadow-sm">
                    <i class="ki-outline ki-eye fs-4 me-1"></i>View Profile
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <x-form-errors />

            <form method="POST" action="{{ route('admin.patients.update', $patient) }}" class="form d-flex flex-column flex-lg-row">
                @csrf
                
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <!--begin::Status-->
                    <div class="card card-flush py-4 card-bordered border-gray-300">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="fs-5 fw-bold text-gray-900 m-0">Account Status</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <select name="status" class="form-select text-gray-900 border border-gray-300 bg-transparent" data-control="select2" data-hide-search="true">
                                @foreach (\App\Models\User::getStatusList() as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $patient->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-gray-600 fs-7 mt-2">Set the patient account status.</div>
                        </div>
                    </div>
                    <!--end::Status-->
                    
                    <div class="d-flex flex-column gap-3">
                        <button type="submit" class="btn btn-light-primary border border-primary fw-bold w-100 shadow-sm">
                            <i class="ki-outline ki-check fs-4 me-1"></i>Save Changes
                        </button>
                    </div>
                </div>
                <!--end::Aside column-->

                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <div class="card card-flush py-4 card-bordered border-gray-300">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="fs-5 fw-bold text-gray-900 m-0">Patient Information</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <!--begin::Input group-->
                            <div class="mb-7">
                                <label class="required form-label text-gray-900 fw-semibold">Full Name</label>
                                <input type="text" class="form-control text-gray-900 border border-gray-300 bg-transparent"
                                    value="{{ $patient->name }}" disabled readonly />
                                <div class="text-gray-600 fs-7 mt-2">Name is linked to their mobile authentication and cannot be edited.</div>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="mb-7">
                                <label class="required form-label text-gray-900 fw-semibold">Phone Number</label>
                                <div class="position-relative">
                                    <i class="ki-outline ki-phone fs-2 position-absolute top-50 translate-middle-y ms-4 text-gray-600"></i>
                                    <input type="text" class="form-control text-gray-900 border border-gray-300 bg-light ps-12"
                                        value="{{ $patient->phone }}" disabled readonly />
                                </div>
                                <div class="text-gray-600 fs-7 mt-2">Phone number is used for OTP login and cannot be modified.</div>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="mb-7">
                                <label class="form-label text-gray-900 fw-semibold">Email Address</label>
                                <div class="position-relative">
                                    <i class="ki-outline ki-sms fs-2 position-absolute top-50 translate-middle-y ms-4 text-gray-900"></i>
                                    <input type="email" name="email" class="form-control text-gray-900 border border-gray-300 bg-transparent ps-12"
                                        placeholder="Enter email address" value="{{ old('email', $patient->email) }}" />
                                </div>
                                <div class="text-gray-600 fs-7 mt-2">Optional email address for communication and notifications.</div>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
                <!--end::Main column-->
            </form>
        </div>
    </div>
    <!--end::Content-->

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('[data-control="select2"]').select2({
            minimumResultsForSearch: Infinity
        });
    });
</script>
@endpush
