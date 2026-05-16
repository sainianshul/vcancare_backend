@extends('admin.layouts.app')

@section('title', 'Care Types')

@section('content')

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Care Types" description="Manage and configure the care service categories" />
                <x-breadcrumb :items="[
                    ['label' => 'Services'],
                    ['label' => 'Care Types']
                ]" />
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.services.care-types.create') }}" class="btn btn-sm btn-light-primary border border-primary fw-bold shadow-sm">
                    <i class="ki-outline ki-plus fs-3 me-1"></i>Add Care Type
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            @if(session('success'))
                <div
                    class="alert alert-dismissible bg-light-success border border-success border-dashed d-flex align-items-center p-4 mb-6">
                    <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                    <div class="d-flex flex-column">
                        <span class="fw-semibold text-gray-800">{{ session('success') }}</span>
                    </div>
                    <button type="button"
                        class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                        data-bs-dismiss="alert">
                        <i class="ki-outline ki-cross fs-1 text-success"></i>
                    </button>
                </div>
            @endif

            <!--begin::Search + Count bar-->
            <div class="d-flex align-items-center justify-content-between mb-6">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-gray-500 fs-7 fw-semibold">
                        {{ $careTypes->count() }} {{ Str::plural('type', $careTypes->count()) }}
                    </span>
                </div>
                <div class="position-relative">
                    <i
                        class="ki-outline ki-magnifier fs-4 position-absolute top-50 translate-middle-y ms-4 text-gray-900"></i>
                    <input type="text" id="search-care-types"
                        class="form-control form-control-sm bg-transparent border border-gray-400 text-gray-900 w-220px ps-11"
                        placeholder="Search care types…" />
                </div>
            </div>
            <!--end::Search bar-->

            <!--begin::Cards Grid-->
            <div class="row g-5" id="care-types-grid">

                @forelse($careTypes as $careType)
                    <div class="col-sm-6 col-lg-4 col-xl-3 care-type-card-col" data-name="{{ strtolower($careType->name) }}">
                        <div class="card h-100 card-bordered border-dark position-relative">

                            <!--begin::Status ribbon-->
                            <span class="position-absolute top-0 end-0 mt-3 me-3">
                                @if($careType->status === \App\Models\CareType::STATUS_ACTIVE)
                                    <span class="badge badge-light-success border border-success fw-medium fs-8 px-3 py-1">
                                        <i class="ki-outline ki-check-circle fs-7 text-success me-1"></i>Active
                                    </span>
                                @elseif($careType->status === \App\Models\CareType::STATUS_INACTIVE)
                                    <span class="badge badge-light-danger border border-danger fw-medium fs-8 px-3 py-1">
                                        <i class="ki-outline ki-cross-circle fs-7 text-danger me-1"></i>Inactive
                                    </span>
                                @else
                                    <span class="badge badge-light-warning border border-warning fw-medium fs-8 px-3 py-1">
                                        <i class="ki-outline ki-time fs-7 text-warning me-1"></i>Draft
                                    </span>
                                @endif
                            </span>
                            <!--end::Status ribbon-->

                            <div class="card-body d-flex flex-column p-6">

                                <!--begin::Image / Avatar-->
                                <div class="mb-5">
                                    @if(!empty(trim($careType->image_path ?? '')))
                                        <div class="symbol" style="width: 80px; height: 50px; border-radius: 4px; overflow: hidden;">
                                            <img src="{{ Storage::url($careType->image_path) }}" alt="{{ $careType->name }}"
                                                class="object-fit-cover w-100 h-100" />
                                        </div>
                                    @else
                                        <div class="symbol" style="width: 80px; height: 50px; border-radius: 4px; overflow: hidden;">
                                            <span class="symbol-label bg-light-primary fs-3 fw-medium text-primary w-100 h-100">
                                                {{ strtoupper(substr($careType->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <!--end::Image-->

                                <!--begin::Title-->
                                <div class="mb-2">
                                    <a href="{{ route('admin.services.care-types.edit', $careType) }}"
                                        class="text-dark fw-medium fs-5 text-hover-primary d-block mb-1" style="letter-spacing: -0.3px;">
                                        {{ $careType->name }}
                                    </a>
                                </div>
                                <!--end::Title-->

                                <!--begin::Separator-->
                                <div class="separator my-4" style="border-color: #f3f4f6;"></div>
                                <!--end::Separator-->

                                <!--begin::Info-->
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <span class="text-dark fs-8 fw-medium text-uppercase" style="letter-spacing: 0.5px;">Created At</span>
                                    <span class="text-dark fs-8 fw-medium">{{ $careType->created_at ? $careType->created_at->format('d M Y') : 'N/A' }}</span>
                                </div>
                                <!--end::Info-->

                                <!--begin::Footer actions-->
                                <div class="d-flex align-items-center justify-content-end gap-2 mt-auto">
                                    <a href="{{ route('admin.services.care-types.show', $careType) }}"
                                        class="btn btn-icon btn-sm btn-active-light-info text-gray-500" data-bs-toggle="tooltip" title="View">
                                        <i class="ki-outline ki-eye fs-5"></i>
                                    </a>
                                    <a href="{{ route('admin.services.care-types.edit', $careType) }}"
                                        class="btn btn-icon btn-sm btn-active-light-primary text-gray-500" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ki-outline ki-pencil fs-5"></i>
                                    </a>
                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-danger text-gray-500 btn-delete"
                                        data-id="{{ $careType->id }}" data-name="{{ $careType->name }}" data-bs-toggle="tooltip" title="Delete">
                                        <i class="ki-outline ki-trash fs-5"></i>
                                    </button>
                                </div>
                                <!--end::Footer actions-->

                            </div>
                        </div>
                    </div>
                @empty
                    <!--begin::Empty state-->
                    <div class="col-12" id="empty-state">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body py-20 d-flex flex-column align-items-center text-center">
                                <div class="mb-6">
                                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="80" height="80" rx="16" fill="#F5F8FA" />
                                        <path d="M26 28h28a2 2 0 0 1 2 2v20a2 2 0 0 1-2 2H26a2 2 0 0 1-2-2V30a2 2 0 0 1 2-2Z"
                                            stroke="#B5B5C3" stroke-width="1.5" fill="none" />
                                        <path d="M24 34h32M34 28v6M46 28v6" stroke="#B5B5C3" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <circle cx="53" cy="51" r="9" fill="#F5F8FA" stroke="#B5B5C3" stroke-width="1.5" />
                                        <path d="M53 47v4l2.5 2.5" stroke="#B5B5C3" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                </div>
                                <h3 class="text-gray-800 fw-bold fs-4 mb-2">No care types yet</h3>
                                <p class="text-gray-500 fs-6 mb-7 w-300px">Define the categories of care services your platform
                                    supports.</p>
                                <a href="{{ route('admin.services.care-types.create') }}"
                                    class="btn btn-dark btn-sm fw-semibold">
                                    <i class="ki-outline ki-plus fs-3 me-1"></i>Add First Care Type
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--end::Empty state-->
                @endforelse

            </div>
            <!--end::Cards Grid-->

            <!--begin::No results (search)-->
            <div id="no-results" class="d-none text-center py-16">
                <p class="text-gray-500 fs-6">No care types match your search.</p>
            </div>
            <!--end::No results-->

        </div>
    </div>
    <!--end::Content-->

@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet" />

@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script>
        $(document).ready(function () {

            // ── Client-side search ──
            $('#search-care-types').on('input', function () {
                const q = $(this).val().toLowerCase().trim();
                let visible = 0;

                $('.care-type-card-col').each(function () {
                    const name = $(this).data('name') || '';
                    const match = name.includes(q);
                    $(this).toggleClass('d-none', !match);
                    if (match) visible++;
                });

                $('#no-results').toggleClass('d-none', visible > 0);
            });

            // ── Soft Delete ──
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                const name = $(this).data('name');
                const row = $(this).closest('.care-type-card-col');

                Swal.fire({
                    title: 'Remove "' + name + '"?',
                    text: 'This care type will be archived and can be restored later.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-light ms-2'
                    },
                    buttonsStyling: false
                }).then(result => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: '{{ route("admin.services.care-types.index") }}/' + id,
                        type: 'POST',
                        data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                        success: function () {
                            row.fadeOut(250, function () {
                                $(this).remove();
                                const remaining = $('.care-type-card-col:visible').length;
                                if (remaining === 0) {
                                    // Show inline empty message without full reload
                                    $('#care-types-grid').append(`
                                                                        <div class="col-12 text-center py-16">
                                                                            <p class="text-gray-500 fs-6">No care types found.</p>
                                                                            <a href="{{ route('admin.services.care-types.create') }}" class="btn btn-sm btn-dark mt-3">Add Care Type</a>
                                                                        </div>
                                                                    `);
                                }
                            });
                            toastr.success('Care type archived successfully.');
                        },
                        error: function () {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    });
                });
            });

            // ── Tooltips ──
            $('[data-bs-toggle="tooltip"]').tooltip({ trigger: 'hover' });

        });
    </script>
@endpush