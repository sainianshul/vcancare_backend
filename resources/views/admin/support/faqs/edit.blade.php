@extends('admin.layouts.app')

@section('title', 'Edit FAQ')

@section('content')

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Edit FAQ" description="Update the frequently asked question #{{ $faq->id }}" />
                <x-breadcrumb :items="[
                    ['label' => 'Support', 'url' => route('admin.support.index')],
                    ['label' => 'FAQs', 'url' => route('admin.support.faqs.index')],
                    ['label' => 'Edit FAQ'],
                ]" />
            </div>
            <a href="{{ route('admin.support.faqs.index') }}"
                class="btn btn-sm btn-light fw-semibold">
                <i class="ki-outline ki-arrow-left fs-4 me-1"></i>Back
            </a>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <x-form-errors />

            <form method="POST" action="{{ route('admin.support.faqs.update', $faq) }}" class="form d-flex flex-column flex-lg-row">
                @csrf
                @method('PUT')

                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 me-lg-10">
                    <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="fs-5 fw-bold text-gray-900 m-0">FAQ Details</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">

                            <!--begin::Input group-->
                            <div class="mb-7">
                                <label class="required form-label text-gray-900 fw-semibold">Category</label>
                                <select name="support_category_id" class="form-select text-gray-900 border border-gray-300 bg-transparent @error('support_category_id') is-invalid @enderror" data-control="select2" data-placeholder="Select a category" required>
                                    <option value=""></option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('support_category_id', $faq->support_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-gray-600 fs-7 mt-2">Select the relevant topic category for this FAQ.</div>
                                @error('support_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="mb-7">
                                <label class="required form-label text-gray-900 fw-semibold">Question</label>
                                <div class="position-relative">
                                    <i class="ki-outline ki-message-question fs-2 position-absolute top-50 translate-middle-y ms-4 text-gray-900"></i>
                                    <input type="text" name="question" class="form-control text-gray-900 border border-gray-300 bg-transparent ps-12 @error('question') is-invalid @enderror"
                                        placeholder="Enter the question..." value="{{ old('question', $faq->question) }}" required />
                                </div>
                                <div class="text-gray-600 fs-7 mt-2">The question exactly as the user might ask it.</div>
                                @error('question')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="mb-7">
                                <label class="required form-label text-gray-900 fw-semibold">Answer</label>
                                <textarea name="answer" class="form-control text-gray-900 border border-gray-300 bg-transparent @error('answer') is-invalid @enderror" rows="6" placeholder="Enter the detailed answer..." required>{{ old('answer', $faq->answer) }}</textarea>
                                <div class="text-gray-600 fs-7 mt-2">Provide a clear, helpful, and concise answer.</div>
                                @error('answer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Input group-->

                        </div>
                    </div>
                </div>
                <!--end::Main column-->

                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7">
                    <!--begin::Status-->
                    <div class="card card-flush py-4 card-bordered border-gray-300 shadow-sm" style="position:sticky;top:90px;">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="fs-5 fw-bold text-gray-900 m-0">Publish Status</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <select name="status" class="form-select text-gray-900 border border-gray-300 bg-transparent" data-control="select2" data-hide-search="true" required>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $faq->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-gray-600 fs-7 mt-2">Set the visibility of this FAQ.</div>
                        </div>
                        
                        <div class="card-footer pt-0 border-0 mt-5">
                            <button type="submit" class="btn btn-light-primary border border-primary fw-bold w-100 shadow-sm">
                                <i class="ki-outline ki-check fs-4 me-1"></i>Update FAQ
                            </button>
                        </div>
                    </div>
                    <!--end::Status-->
                </div>
                <!--end::Aside column-->

            </form>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('[data-control="select2"]').select2({
            minimumResultsForSearch: 10
        });
    });
</script>
@endpush
