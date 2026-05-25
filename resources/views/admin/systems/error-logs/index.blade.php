@extends('admin.layouts.app')

@section('title', 'Application Errors')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'System'],
        ['label' => 'Application Errors'],
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
                        placeholder="Search by error ID..."
                    />

                </div>

                {{-- Right Controls --}}
                <div class="d-flex align-items-center gap-2">

                    {{-- Status --}}
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
                                data-placeholder="All Status"
                                data-allow-clear="true"
                                data-hide-search="true"
                            >
                                <option></option>

                                <option value="0">Open</option>

                                <option value="1">Resolved</option>

                                <option value="2">Ignored</option>

                            </select>

                        </div>

                    </div>

                    {{-- Severity --}}
                    <div style="width: 145px;">

                        <select
                            id="filter-severity"
                            class="form-select form-select-transparent border border-gray-800 text-gray-900 form-select-sm fw-semibold shadow-sm"
                            data-control="select2"
                            data-placeholder="Severity"
                            data-allow-clear="true"
                            data-hide-search="true"
                        >
                            <option></option>

                            <option value="1">Low</option>

                            <option value="2">Medium</option>

                            <option value="3">High</option>

                            <option value="4">Critical</option>

                        </select>

                    </div>

                    {{-- Empty Logs --}}
                    <button
                        type="button"
                        id="btn-empty-logs"
                        class="btn btn-sm btn-light-danger fw-semibold border border-danger-subtle shadow-sm px-4"
                    >

                        <i class="ki-duotone ki-trash-square fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>

                        Empty Logs

                    </button>

                </div>

            </div>

        </div>

        {{-- Body --}}
        <div class="card-body py-4">

            <div id="errors-table-wrapper" class="table-responsive">

                <table
                    id="errors-table"
                    class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100"
                >

                    <thead>

                        <tr class="text-start text-gray-900 fw-medium fs-7 text-uppercase gs-0 border-bottom border-gray-200 border-1">

                            <th class="w-50px">#</th>

                            <th class="min-w-320px">
                                Error
                            </th>

                            <th class="min-w-120px">
                                Severity
                            </th>

                            <th class="min-w-120px">
                                Status
                            </th>

                            <th class="min-w-220px">
                                Request
                            </th>

                            <th class="min-w-140px">
                                Created
                            </th>

                            <th class="text-end min-w-120px pe-3">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

            @include('admin.layouts.partials._table-skeleton', [
                'id' => 'errors-skeleton'
            ])

            @include('admin.layouts.partials._table-empty', [
                'id' => 'errors-empty'
            ])

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

            let table = $('#errors-table').DataTable({

                serverSide: true,

                processing: false,

                ajax: {
                    url: '{{ route('admin.system.errors.data') }}',

                    data: function (d) {

                        d.status = $('#filter-status').val();

                        d.severity = $('#filter-severity').val();
                    }
                },

                columns: [

                    {
                        data: 'id',
                        name: 'id',
                        searchable: false
                    },

                    {
                        data: 'error',
                        name: 'error',
                        searchable: true
                    },

                    {
                        data: 'severity',
                        name: 'severity',
                        searchable: false
                    },

                    {
                        data: 'status',
                        name: 'status',
                        searchable: false
                    },

                    {
                        data: 'request',
                        name: 'url',
                        searchable: false
                    },

                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false
                    },

                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-end pe-3'
                    },
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

                    info: 'Showing _START_–_END_ of _TOTAL_ errors',

                    infoEmpty: 'No errors to show',

                    infoFiltered: '(filtered from _MAX_)',

                    lengthMenu: 'Show _MENU_',

                    paginate: {
                        previous: '<i class="ki-duotone ki-arrow-left"></i>',
                        next: '<i class="ki-duotone ki-arrow-right"></i>',
                    },
                },

                initComplete: function () {

                    $('#errors-skeleton').fadeOut(200, function () {
                        $(this).remove();
                    });
                },

                drawCallback: function () {

                    let total = this.api().page.info().recordsDisplay;

                    if (total === 0) {

                        $('#errors-table-wrapper').addClass('d-none');

                        $('#errors-empty').removeClass('d-none');

                    } else {

                        $('#errors-empty').addClass('d-none');

                        $('#errors-table-wrapper').removeClass('d-none');
                    }

                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger: 'hover'
                    });
                }
            });

            // ── Search ───────────────────────────────────────────────
            let searchTimer;

            $('#dt-search').on('input', function () {

                clearTimeout(searchTimer);

                let query = $(this).val();

                searchTimer = setTimeout(function () {

                    table.search(query).draw();

                }, 400);
            });

            // ── Filters ──────────────────────────────────────────────
            $('#filter-status, #filter-severity').on('change', function () {

                table.ajax.reload();
            });

            // ── Empty Logs ───────────────────────────────────────────
            $('#btn-empty-logs').on('click', function () {

                Swal.fire({

                    title: 'Empty Error Logs?',

                    text: 'All application error logs will be permanently removed.',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Yes, Empty Logs',

                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-light ms-2'
                    },

                    buttonsStyling: false,

                }).then(function (result) {

                    if (!result.isConfirmed) {
                        return;
                    }

                    $.post('{{ route('admin.system.errors.empty') }}', {

                        _token: '{{ csrf_token() }}'

                    })

                    .done(function () {

                        table.ajax.reload();

                        toastr.success('All logs cleared successfully.');

                    })

                    .fail(function () {

                        toastr.error('Something went wrong.');
                    });
                });
            });

            // ── Change Status ───────────────────────────────────────────
            $(document).on('click', '.btn-status', function () {
                let id = $(this).data('id');
                let status = $(this).data('status');
                
                $.post(`/admin/system/error-logs/${id}/status`, {
                    _token: '{{ csrf_token() }}',
                    status: status
                }).done(function (res) {
                    toastr.success(res.message);
                    table.ajax.reload(null, false);
                }).fail(function () {
                    toastr.error('Something went wrong.');
                });
            });

        });

    </script>

@endpush



