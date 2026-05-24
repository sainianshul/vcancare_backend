@extends('admin.layouts.app')

@section('title', 'FAQs')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Support', 'url' => route('admin.support.index')],
        ['label' => 'FAQs'],
    ]" />

    <div class="card shadow-sm">

        {{-- Toolbar --}}
        <div class="card-header border-0 pt-5 pb-3">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-3">

                {{-- Search --}}
                <div class="d-flex align-items-center position-relative">
                    <i class="ki-duotone ki-magnifier fs-5 text-gray-900 position-absolute ms-4 z-index-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input
                        type="text"
                        id="dt-search"
                        class="form-control form-control-transparent border border-gray-800 text-gray-900 w-250px ps-11 pe-4 fs-7 fw-semibold shadow-sm"
                        placeholder="Search FAQs..."
                    />
                </div>

                {{-- Right Controls --}}
                <div class="d-flex align-items-center gap-2">

                    {{-- Category Filter --}}
                    <div style="width: 200px;">
                        <div class="position-relative">
                            <i class="ki-duotone ki-filter fs-5 text-gray-900 position-absolute top-50 start-0 translate-middle-y ms-4 z-index-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <select
                                id="filter-category"
                                class="form-select form-select-transparent border border-gray-800 text-gray-900 form-select-sm fw-semibold ps-11 shadow-sm"
                                data-control="select2"
                                data-placeholder="All Categories"
                                data-allow-clear="true"
                            >
                                <option></option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div style="width: 145px;">
                        <div class="position-relative">
                            <i class="ki-duotone ki-filter fs-5 text-gray-900 position-absolute top-50 start-0 translate-middle-y ms-4 z-index-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <select
                                id="filter-status"
                                class="form-select form-select-transparent border border-gray-800 text-gray-900 form-select-sm fw-semibold ps-11 shadow-sm"
                                data-control="select2"
                                data-placeholder="All Statuses"
                                data-allow-clear="true"
                                data-hide-search="true"
                            >
                                <option></option>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Add FAQ --}}
                    <a href="{{ route('admin.support.faqs.create') }}" class="btn btn-sm btn-primary fw-semibold btn-flex btn-center">
                        <i class="ki-duotone ki-plus-square fs-5 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Add FAQ
                    </a>

                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body py-4">

            <div id="faqs-table-wrapper" class="table-responsive">
                <table id="faqs-table" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100">
                    <thead>
                        <tr class="text-start text-gray-900 fw-medium fs-7 text-uppercase gs-0 border-bottom border-gray-200 border-1">
                            <th class="w-50px">#</th>
                            <th class="min-w-250px">Question</th>
                            <th class="min-w-150px">Category</th>
                            <th class="min-w-120px">Status</th>
                            <th class="min-w-150px">Created At</th>
                            <th class="text-end min-w-100px pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            @include('admin.layouts.partials._table-skeleton', ['id' => 'faqs-skeleton'])
            @include('admin.layouts.partials._table-empty', ['id' => 'faqs-empty'])

        </div>
    </div>

@endsection

@push('datatables_css')
    @include('admin.layouts.partials._datatable-cdn-css')
@endpush

@push('datatables_js')
    @include('admin.layouts.partials._datatable-cdn-js')

    <script>
        $(function () {
            let table = $('#faqs-table').DataTable({
                serverSide: true,
                processing: false,
                ajax: {
                    url: '{{ route('admin.support.faqs.data') }}',
                    data: function (d) {
                        d.status = $('#filter-status').val();
                        d.support_category_id = $('#filter-category').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'question', name: 'question' },
                    { data: 'category', name: 'supportCategory.name' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-3' },
                ],
                order: [[0, 'desc']],
                pageLength: 15,
                lengthMenu: [[10, 15, 25, 50], [10, 15, 25, 50]],
                dom:
                    "<'row'<'col-12'tr>>" +
                    "<'row align-items-center mt-3 pt-3 flex-nowrap'" +
                    "<'col-sm-12 col-md-5'i>" +
                    "<'col-sm-12 col-md-7 d-flex justify-content-md-end align-items-center gap-3'lp>>",
                language: {
                    emptyTable: ' ',
                    zeroRecords: ' ',
                    loadingRecords: ' ',
                    info: 'Showing _START_–_END_ of _TOTAL_ FAQs',
                    infoEmpty: 'No FAQs to show',
                    infoFiltered: '(filtered from _MAX_)',
                    lengthMenu: 'Show _MENU_',
                    paginate: {
                        previous: '<i class="ki-duotone ki-arrow-left"></i>',
                        next: '<i class="ki-duotone ki-arrow-right"></i>',
                    },
                },
                initComplete: function () {
                    $('#faqs-skeleton').fadeOut(200, function () {
                        $(this).remove();
                    });
                },
                drawCallback: function () {
                    let total = this.api().page.info().recordsDisplay;
                    if (total === 0) {
                        $('#faqs-table-wrapper').addClass('d-none');
                        $('#faqs-empty').removeClass('d-none');
                    } else {
                        $('#faqs-empty').addClass('d-none');
                        $('#faqs-table-wrapper').removeClass('d-none');
                    }
                    $('[data-bs-toggle="tooltip"]').tooltip({ trigger: 'hover' });
                }
            });

            let searchTimer;
            $('#dt-search').on('input', function () {
                clearTimeout(searchTimer);
                let query = $(this).val();
                searchTimer = setTimeout(function () {
                    table.search(query).draw();
                }, 400);
            });

            $('#filter-status, #filter-category').on('change', function () {
                table.ajax.reload();
            });
        });

        function confirmDelete(url) {
            Swal.fire({
                title: 'Delete FAQ?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-light ms-2' },
                buttonsStyling: false,
            }).then(function (result) {
                if (!result.isConfirmed) return;
                $.post(url, { _method: 'DELETE', _token: '{{ csrf_token() }}' })
                .done(function () {
                    $('#faqs-table').DataTable().ajax.reload(null, false);
                    toastr.success('FAQ deleted.');
                })
                .fail(function () {
                    toastr.error('Something went wrong.');
                });
            });
        }
    </script>
@endpush
