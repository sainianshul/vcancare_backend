@extends('admin.layouts.app')

@section('title', $careType->name)

@section('content')

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="{{ $careType->name }}" description="View care service details" />
                <x-breadcrumb :items="[
            ['label' => 'Services'],
            ['label' => 'Care Types', 'url' => route('admin.services.care-types.index')],
            ['label' => $careType->name],
        ]" />
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.services.care-types.index') }}" class="btn btn-sm btn-light fw-semibold">
                    <i class="ki-outline ki-arrow-left fs-4 me-1"></i>Back
                </a>
                <a href="{{ route('admin.services.care-types.edit', $careType) }}" class="btn btn-sm btn-light-primary border border-primary fw-bold shadow-sm">
                    <i class="ki-outline ki-pencil fs-4 me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <div class="row g-5">
                <div class="col-lg-8">
                    <!-- Overview Card -->
                    <div class="card h-100" style="border: 1px solid var(--bs-gray-300); background: var(--bs-body-bg);">
                        <div class="card-header border-bottom" style="border-color: var(--bs-gray-200);">
                            <h3 class="card-title fw-bold fs-4" style="color: var(--bs-text-gray-900);">Overview</h3>
                        </div>
                        <div class="card-body p-8">
                            <div class="d-flex flex-column gap-6">

                                <!-- Image -->
                                <div>
                                    <h6 class="fw-semibold text-uppercase mb-3"
                                        style="font-size: 11px; letter-spacing: 0.5px; color: var(--bs-gray-600);">Thumbnail
                                    </h6>
                                    @if(!empty(trim($careType->image_path ?? '')))
                                        <div class="symbol"
                                            style="width: 140px; height: 90px; border-radius: 6px; overflow: hidden; border: 1px solid var(--bs-gray-300);">
                                            <img src="{{ Storage::url($careType->image_path) }}" alt="{{ $careType->name }}"
                                                class="object-fit-cover w-100 h-100" />
                                        </div>
                                    @else
                                        <div class="symbol"
                                            style="width: 140px; height: 90px; border-radius: 6px; overflow: hidden; border: 1px solid var(--bs-gray-300);">
                                            <span class="symbol-label bg-light-primary fs-1 fw-medium text-primary w-100 h-100">
                                                {{ strtoupper(substr($careType->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="separator my-2" style="border-color: var(--bs-gray-200);"></div>

                                <!-- Name -->
                                <div>
                                    <h6 class="fw-semibold text-uppercase mb-2"
                                        style="font-size: 11px; letter-spacing: 0.5px; color: var(--bs-gray-600);">Name</h6>
                                    <p class="fw-medium fs-5 m-0" style="color: var(--bs-text-gray-900);">
                                        {{ $careType->name }}
                                    </p>
                                </div>

                                <div class="separator my-2" style="border-color: var(--bs-gray-200);"></div>

                                <!-- Description -->
                                <div>
                                    <h6 class="fw-semibold text-uppercase mb-2"
                                        style="font-size: 11px; letter-spacing: 0.5px; color: var(--bs-gray-600);">
                                        Description</h6>
                                    <p class="fw-normal fs-6 m-0" style="line-height: 1.6; color: var(--bs-text-gray-900);">
                                        {{ $careType->description ?: 'No description provided.' }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Meta Card -->
                    <div class="card mb-5" style="border: 1px solid var(--bs-gray-300); background: var(--bs-body-bg);">
                        <div class="card-header border-bottom" style="border-color: var(--bs-gray-200);">
                            <h3 class="card-title fw-bold fs-4" style="color: var(--bs-text-gray-900);">Details</h3>
                        </div>
                        <div class="card-body p-6">
                            <div class="d-flex flex-column gap-5">

                                <!-- Status -->
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-medium fs-7" style="color: var(--bs-gray-600);">Status</span>
                                    @if($careType->status === \App\Models\CareType::STATUS_ACTIVE)
                                        <span class="badge badge-light-success border border-success fw-medium px-3 py-1">
                                            <i class="ki-outline ki-check-circle fs-7 text-success me-1"></i>Active
                                        </span>
                                    @elseif($careType->status === \App\Models\CareType::STATUS_INACTIVE)
                                        <span class="badge badge-light-danger border border-danger fw-medium px-3 py-1">
                                            <i class="ki-outline ki-cross-circle fs-7 text-danger me-1"></i>Inactive
                                        </span>
                                    @else
                                        <span class="badge badge-light-warning border border-warning fw-medium px-3 py-1">
                                            <i class="ki-outline ki-time fs-7 text-warning me-1"></i>Draft
                                        </span>
                                    @endif
                                </div>

                                <!-- Commission -->
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-medium fs-7" style="color: var(--bs-gray-600);">Commission</span>
                                    @if($careType->commision_value)
                                        <span class="badge badge-light-primary border border-primary fw-medium px-3 py-1">
                                            {{ $careType->commission_text }}
                                        </span>
                                    @else
                                        <span class="badge badge-light fw-medium px-3 py-1 border border-gray-300"
                                            style="color: var(--bs-text-gray-900);">N/A</span>
                                    @endif
                                </div>

                                <div class="separator my-1" style="border-color: var(--bs-gray-200);"></div>

                                <!-- Created -->
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-medium fs-7" style="color: var(--bs-gray-600);">Created On</span>
                                    <span class="fw-medium fs-7"
                                        style="color: var(--bs-text-gray-900);">{{ $careType->created_at ? $careType->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                                </div>

                                <!-- Updated -->
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-medium fs-7" style="color: var(--bs-gray-600);">Last Updated</span>
                                    <span class="fw-medium fs-7"
                                        style="color: var(--bs-text-gray-900);">{{ $careType->updated_at ? $careType->updated_at->format('d M Y, h:i A') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection