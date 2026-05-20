@extends('admin.layouts.app')

@section('title', 'Edit Care Type')

@section('content')

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Edit — {{ $careType->name }}"
                    description="Modify the details of this care service category" />
                <x-breadcrumb :items="[
            ['label' => 'Services'],
            ['label' => 'Care Types', 'url' => route('admin.services.care-types.index')],
            ['label' => $careType->name],
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

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                    <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-success">Success</h4>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <x-form-errors />

            <form method="POST" action="{{ route('admin.services.care-types.update', $careType) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-5">

                    {{-- ════════════════════════════════
                    MAIN CONTENT (left 8 cols)
                    ════════════════════════════════ --}}
                    <div class="col-lg-8">

                        {{-- ── Section 1: Basic Info ── --}}
                        <div class="card shadow-sm mb-5">
                            <div class="card-header min-h-50px pt-4 pb-2">
                                <h3 class="card-title d-flex flex-column">
                                    <span class="fw-bold text-dark fs-4">Basic Information</span>
                                    <span class="text-dark fs-7 mt-2">Use a clear, recognisable name for this
                                        category.</span>
                                </h3>
                            </div>
                            <div class="card-body">

                                <div class="mb-6">
                                    <label class="required form-label">Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $careType->name) }}" autofocus />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">Description <span
                                            class="fs-7 text-dark">(Optional)</span></label>
                                    <textarea name="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="4">{{ old('description', $careType->description) }}</textarea>
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

                                    {{-- Existing image --}}
                                    @if(!empty(trim($careType->image_path ?? '')))
                                        <div id="img-preview-wrap">
                                            <img id="img-preview" src="{{ Storage::url($careType->image_path) }}" alt="preview"
                                                class="rounded"
                                                style="width:80px;height:80px;object-fit:cover;border:1px solid var(--bs-gray-300);" />
                                            <button type="button" id="img-remove"
                                                class="btn btn-icon btn-active-color-danger btn-sm w-20px h-20px bg-body shadow-sm rounded-circle position-absolute"
                                                style="top:-10px;right:-10px;">
                                                <i class="ki-outline ki-cross fs-6"></i>
                                            </button>
                                        </div>
                                        <div id="upload-default" class="d-none">
                                    @else
                                            <div id="img-preview-wrap" class="d-none">
                                                <img id="img-preview" src="" alt="preview" class="rounded"
                                                    style="width:80px;height:80px;object-fit:cover;border:1px solid var(--bs-gray-300);" />
                                                <button type="button" id="img-remove"
                                                    class="btn btn-icon btn-active-color-danger btn-sm w-20px h-20px bg-body shadow-sm rounded-circle position-absolute"
                                                    style="top:-10px;right:-10px;">
                                                    <i class="ki-outline ki-cross fs-6"></i>
                                                </button>
                                            </div>
                                            <div id="upload-default">
                                        @endif
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
                                        <input type="hidden" name="remove_image" id="remove_image" value="" />
                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 3: Commission ── --}}
                            @php $currentType = old('commision_type', $careType->commision_type ?? '1'); @endphp
                            <div class="card shadow-sm mb-5">
                                <div class="card-header min-h-50px pt-4 pb-2">
                                    <h3 class="card-title d-flex flex-column">
                                        <span class="fw-bold text-dark fs-4">Commission</span>
                                        <span class="fs-7 text-dark fw-semibold">Platform earnings model for bookings under
                                            this type.</span>
                                    </h3>
                                </div>
                                <div class="card-body">

                                    <div class="mb-6">
                                        <label class="form-label">Commission Type</label>
                                        <div class="row g-4" data-kt-buttons="true">
                                            <div class="col-sm-4">
                                                <label
                                                    class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-4 h-100 {{ $currentType != '0' && $currentType != '2' ? 'active' : '' }}"
                                                    id="tile-pct">
                                                    <span class="form-check form-check-custom form-check-sm align-items-start mt-1">
                                                        <input class="form-check-input" type="radio" name="commision_type"
                                                            value="1" {{ $currentType != '0' && $currentType != '2' ? 'checked' : '' }} />
                                                    </span>
                                                    <span class="ms-3">
                                                        <span class="fs-7 fw-bold text-gray-800 d-block">Percent %</span>
                                                        <span class="fs-8 text-dark">Of total</span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-sm-4">
                                                <label
                                                    class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-4 h-100 {{ $currentType == '0' ? 'active' : '' }}"
                                                    id="tile-fixed">
                                                    <span class="form-check form-check-custom form-check-sm align-items-start mt-1">
                                                        <input class="form-check-input" type="radio" name="commision_type"
                                                            value="0" {{ $currentType == '0' ? 'checked' : '' }} />
                                                    </span>
                                                    <span class="ms-3">
                                                        <span class="fs-7 fw-bold text-gray-800 d-block">Fixed / Day</span>
                                                        <span class="fs-8 text-dark">Per day fee</span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-sm-4">
                                                <label
                                                    class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-4 h-100 {{ $currentType == '2' ? 'active' : '' }}"
                                                    id="tile-fixed-total">
                                                    <span class="form-check form-check-custom form-check-sm align-items-start mt-1">
                                                        <input class="form-check-input" type="radio" name="commision_type"
                                                            value="2" {{ $currentType == '2' ? 'checked' : '' }} />
                                                    </span>
                                                    <span class="ms-3">
                                                        <span class="fs-7 fw-bold text-gray-800 d-block">Flat Fee</span>
                                                        <span class="fs-8 text-dark">Per booking</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="max-width:250px;">
                                        <label class="form-label">Value</label>
                                        <div class="input-group">
                                            <input type="number" name="commision_value" id="commision_value"
                                                class="form-control @error('commision_value') is-invalid @enderror" min="0"
                                                step="0.01"
                                                value="{{ old('commision_value', $careType->commision_value) }}" />
                                            <span class="input-group-text" id="commission-unit">
                                                {{ $currentType == '0' ? '₹ / day' : ($currentType == '2' ? '₹ flat' : '%') }}
                                            </span>
                                        </div>
                                        @error('commision_value')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                        </div>{{-- /col-lg-8 --}}

                        {{-- ════════════════════════════════
                        SIDEBAR (right 4 cols)
                        ════════════════════════════════ --}}
                        <div class="col-lg-4">
                            <div style="position:sticky;top:90px;display:flex;flex-direction:column;gap:15px;">

                                {{-- Publish Actions --}}
                                <div class="card shadow-sm">
                                    <div class="card-header min-h-40px py-3">
                                        <h3 class="card-title fs-7 fw-bold text-uppercase text-dark m-0">Publish</h3>
                                    </div>
                                    <div class="card-body p-5 d-flex flex-column gap-3">
                                        @if($careType->status === \App\Models\CareType::STATUS_ACTIVE || $careType->status === \App\Models\CareType::STATUS_INACTIVE)
                                            <div class="mb-2">
                                                <label class="form-label fs-7 fw-semibold text-gray-700">Status</label>
                                                <select name="status" class="form-select form-select-sm fw-medium">
                                                    @foreach(\App\Models\CareType::getStatusList() as $key => $label)
                                                        <option value="{{ $key }}" {{ $careType->status == $key ? 'selected' : '' }}>
                                                            {{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" name="action" value="save"
                                                class="btn btn-light-primary border border-primary fw-bold w-100 shadow-sm">
                                                <i class="ki-outline ki-check fs-4 me-1"></i>Save Changes
                                            </button>
                                        @else
                                            <button type="submit" name="action" value="publish"
                                                class="btn btn-light-primary border border-primary fw-bold w-100 shadow-sm">
                                                <i class="ki-outline ki-check fs-4 me-1"></i>Update & Publish
                                            </button>
                                            <button type="submit" name="action" value="draft"
                                                class="btn btn-outline btn-outline-dark fw-semibold w-100">
                                                Save as Draft
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                {{-- Meta --}}
                                <div class="card shadow-sm">
                                    <div class="card-header min-h-40px py-3">
                                        <h3 class="card-title fs-7 fw-bold text-uppercase text-dark m-0">Details</h3>
                                    </div>
                                    <div class="card-body p-5 d-flex flex-column gap-4">

                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fs-7 text-gray-600 fw-semibold">Status</span>
                                            @if($careType->status === \App\Models\CareType::STATUS_ACTIVE)
                                                <span
                                                    class="badge badge-light-success border border-success border-dashed fw-bold px-3 py-1">Active</span>
                                            @elseif($careType->status === \App\Models\CareType::STATUS_INACTIVE)
                                                <span
                                                    class="badge badge-light-danger border border-danger border-dashed fw-bold px-3 py-1">Inactive</span>
                                            @else
                                                <span
                                                    class="badge badge-light-warning border border-warning border-dashed fw-bold px-3 py-1">Draft</span>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fs-7 text-gray-600 fw-semibold">Created</span>
                                            <span class="fs-7 fw-bold text-gray-800">
                                                {{ $careType->created_at ? $careType->created_at->format('d M Y') : 'N/A' }}
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fs-7 text-gray-600 fw-semibold">Updated</span>
                                            <span class="fs-7 fw-bold text-gray-800">
                                                {{ $careType->updated_at ? $careType->updated_at->format('d M Y') : 'N/A' }}
                                            </span>
                                        </div>

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
                if (!$(e.target).closest('#img-remove').length && !$(e.target).closest('label').is('label[for="image-file"]')) {
                    if (!$(e.target).is('#image-file')) $('#image-file').trigger('click');
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
                    $('#remove_image').val('');
                };
                reader.readAsDataURL(file);
            });

            $('#img-remove').on('click', function (e) {
                e.stopPropagation();
                $('#image-file').val('');
                $('#img-preview-wrap').addClass('d-none');
                $('#upload-default').removeClass('d-none');
                $('#remove_image').val('1'); // tells controller to clear the image
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