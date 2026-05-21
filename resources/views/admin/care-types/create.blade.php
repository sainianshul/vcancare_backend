@extends('admin.layouts.app')

@section('title', 'Add Care Type')

@section('content')

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Add Care Type" description="Create a new care service category for the platform" />
                <x-breadcrumb :items="[
            ['label' => 'Services'],
            ['label' => 'Care Types', 'url' => route('admin.services.care-types.index')],
            ['label' => 'Add New'],
        ]" />
            </div>
            <a href="{{ route('admin.services.care-types.index') }}"
                class="btn btn-sm btn-outline btn-outline-dark fw-semibold">
                <i class="ki-outline ki-arrow-left fs-4 me-1"></i>Back
            </a>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <x-form-errors />

            <form method="POST" action="{{ route('admin.services.care-types.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-5">
                    <div class="col-lg-8">

                        {{-- ── Section 1: Basic Info ── --}}
                        <div class="card shadow-sm mb-5">
                            <div class="card-header min-h-50px pt-4 pb-2">
                                <h3 class="card-title d-flex flex-column">
                                    <span class="fw-bold text-dark fs-4">Basic Information</span>
                                    <span class="fs-7 text-dark fw-semibold">Name and description of this care
                                        category.</span>
                                </h3>
                            </div>
                            <div class="card-body">

                                {{-- Name --}}
                                <div class="mb-6">
                                    <label class="required form-label">Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" autofocus />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="text-dark fs-7 mt-2">Use a clear, recognisable name for this category.</div>
                                </div>

                                {{-- Description --}}
                                <div>
                                    <label class="form-label">Description <span
                                            class="fs-7 text-dark">(Optional)</span></label>
                                    <textarea name="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── Section 2: Thumbnail ── --}}
                        <div class="card shadow-sm mb-5">
                            <div class="card-header min-h-50px pt-4 pb-2">
                                <h3 class="card-title d-flex flex-column">
                                    <span class="fw-bold text-dark fs-4">Thumbnail</span>
                                    <span class="fs-7 text-dark fw-semibold">Square image · PNG, JPG or JPEG · Max
                                        2MB</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="border border-dashed border-gray-300 rounded px-7 py-8 text-center position-relative bg-hover-light cursor-pointer"
                                    id="upload-zone" style="transition: background 0.2s;">
                                    {{-- Preview (hidden until file chosen) --}}
                                    <div id="img-preview-wrap" class="d-none">
                                        <img id="img-preview" src="" alt="preview" class="rounded"
                                            style="width:80px;height:80px;object-fit:cover;border:1px solid var(--bs-gray-300);" />
                                        <button type="button" id="img-remove"
                                            class="btn btn-icon btn-active-color-danger btn-sm w-20px h-20px bg-body shadow-sm rounded-circle position-absolute"
                                            style="top:-10px;right:-10px;">
                                            <i class="ki-outline ki-cross fs-6"></i>
                                        </button>
                                    </div>

                                    {{-- Default state --}}
                                    <div id="upload-default">
                                        <i class="ki-outline ki-picture fs-3x text-dark mb-3"></i>
                                        <div class="fw-bold fs-6 text-gray-800">
                                            Drop image here or
                                            <label for="image-file"
                                                class="text-primary cursor-pointer text-hover-primary text-decoration-underline ms-1">
                                                browse
                                            </label>
                                        </div>
                                        <span class="fs-7 text-dark">PNG, JPG up to 2MB</span>
                                    </div>

                                    <input type="file" name="image" id="image-file" accept=".png,.jpg,.jpeg,.webp"
                                        class="d-none" />
                                </div>
                            </div>
                        </div>

                        {{-- ── Section 3: Commission ── --}}
                        <div class="card shadow-sm mb-5">
                            <div class="card-header min-h-50px pt-4 pb-2">
                                <h3 class="card-title d-flex flex-column">
                                    <span class="fw-bold text-dark fs-4">Commission</span>
                                    <span class="fs-7 text-dark fw-semibold">Platform earnings model for bookings under this
                                        type.</span>
                                </h3>
                            </div>
                            <div class="card-body">

                                {{-- Type selector --}}
                                <div class="mb-6">
                                    <label class="form-label">Commission Type</label>
                                    <div class="row g-4" data-kt-buttons="true">
                                        <div class="col-sm-4">
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-4 h-100 {{ old('commision_type', '1') == '1' ? 'active' : '' }}"
                                                id="tile-pct">
                                                <span class="form-check form-check-custom form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" name="commision_type"
                                                        value="1" {{ old('commision_type', '1') == '1' ? 'checked' : '' }} />
                                                </span>
                                                <span class="ms-3">
                                                    <span class="fs-7 fw-bold text-gray-800 d-block">Percent %</span>
                                                    <span class="fs-8 text-dark">Of total</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-4 h-100 {{ old('commision_type') == '0' ? 'active' : '' }}"
                                                id="tile-fixed">
                                                <span class="form-check form-check-custom form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" name="commision_type"
                                                        value="0" {{ old('commision_type') == '0' ? 'checked' : '' }} />
                                                </span>
                                                <span class="ms-3">
                                                    <span class="fs-7 fw-bold text-gray-800 d-block">Fixed / Day</span>
                                                    <span class="fs-8 text-dark">Per day fee</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-4 h-100 {{ old('commision_type') == '2' ? 'active' : '' }}"
                                                id="tile-fixed-total">
                                                <span class="form-check form-check-custom form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" name="commision_type"
                                                        value="2" {{ old('commision_type') == '2' ? 'checked' : '' }} />
                                                </span>
                                                <span class="ms-3">
                                                    <span class="fs-7 fw-bold text-gray-800 d-block">Flat Fee</span>
                                                    <span class="fs-8 text-dark">Per booking</span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Value --}}
                                <div style="max-width:250px;">
                                    <label class="form-label">Value</label>
                                    <div class="input-group">
                                        <input type="number" name="commision_value" id="commision_value"
                                            class="form-control @error('commision_value') is-invalid @enderror" min="0"
                                            step="0.01" value="{{ old('commision_value') }}" />
                                        <span class="input-group-text" id="commission-unit">%</span>
                                    </div>
                                    @error('commision_value')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                    </div>{{-- /col-lg-8 --}}

                    {{-- Right Sidebar --}}
                    <div class="col-lg-4">
                        <div style="position:sticky;top:90px;">
                            <div class="card shadow-sm">
                                <div class="card-header min-h-40px py-3">
                                    <h3 class="card-title fs-7 fw-bold text-uppercase text-dark m-0">Publish</h3>
                                </div>
                                <div class="card-body p-5 d-flex flex-column gap-3">
                                    <button type="submit" name="action" value="publish"
                                        class="btn btn-light-primary border border-primary fw-bold w-100 shadow-sm">
                                        <i class="ki-outline ki-check fs-4 me-1"></i>Save & Publish
                                    </button>
                                    <button type="submit" name="action" value="draft"
                                        class="btn btn-outline btn-outline-dark fw-semibold w-100">
                                        Save as Draft
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /row --}}
            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            // ── Image upload preview ──
            $('#upload-zone').on('click', function (e) {
                if (!$(e.target).closest('#img-remove').length && !$(e.target).is('label') && !$(e.target).is('#image-file')) {
                    $('#image-file').trigger('click');
                }
            });

            $('#image-file').on('change', function () {
                const file = this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-preview').attr('src', e.target.result);
                    $('#img-preview-wrap').removeClass('d-none');
                    $('#upload-default').addClass('d-none');
                };
                reader.readAsDataURL(file);
            });

            $('#img-remove').on('click', function (e) {
                e.stopPropagation();
                $('#image-file').val('');
                $('#img-preview-wrap').addClass('d-none');
                $('#upload-default').removeClass('d-none');
            });

            // ── Commission tile toggle ──
            function syncTiles() {
                const val = $('input[name="commision_type"]:checked').val();
                $('.btn-outline').removeClass('active');

                if (val == '0') {
                    $('#tile-fixed').addClass('active');
                    $('#commission-unit').text('₹ / day');
                } else if (val == '2') {
                    $('#tile-fixed-total').addClass('active');
                    $('#commission-unit').text('₹ flat');
                } else {
                    $('#tile-pct').addClass('active');
                    $('#commission-unit').text('%');
                }
            }

            $('.btn-outline').on('click', function () {
                $(this).find('input[type="radio"]').prop('checked', true);
                syncTiles();
            });

            $('input[name="commision_type"]').on('change', syncTiles);

            syncTiles();

        });
    </script>
@endpush