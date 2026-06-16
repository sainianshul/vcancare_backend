@extends('admin.layouts.app')

@section('title', 'Communication Logs')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'System'],
        ['label' => 'Communication Logs'],
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
                        placeholder="Search users or message..."
                    />
                </div>

                {{-- Right Controls --}}
                <div class="d-flex align-items-center gap-2">

                    {{-- Channel Filter --}}
                    <div style="width: 145px;">
                        <div class="position-relative">
                            <i class="ki-duotone ki-filter fs-5 text-gray-900 position-absolute top-50 start-0 translate-middle-y ms-4 z-index-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <select
                                id="filter-channel"
                                class="form-select form-select-transparent border border-gray-800 text-gray-900 form-select-sm fw-semibold ps-11 shadow-sm"
                                data-control="select2"
                                data-placeholder="All Channels"
                                data-allow-clear="true"
                                data-hide-search="true"
                            >
                                <option></option>
                                <option value="mail">Email</option>
                                <option value="sms">SMS / Twilio</option>
                                <option value="push">Push (FCM)</option>
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
                                data-placeholder="All Status"
                                data-allow-clear="true"
                                data-hide-search="true"
                            >
                                <option></option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
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

            <div id="communication-logs-table-wrapper" class="table-responsive">

                <table
                    id="communication-logs-table"
                    class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100"
                >

                    <thead>
                        <tr class="text-start text-gray-900 fw-medium fs-7 text-uppercase gs-0 border-bottom border-gray-200 border-1">
                            <th class="w-50px">S.No</th>
                            <th class="min-w-200px">User</th>
                            <th class="min-w-150px">Notification</th>
                            <th class="min-w-300px">Message Content</th>
                            <th class="min-w-130px">Status</th>
                            <th class="min-w-140px">Created At</th>
                        </tr>
                    </thead>

                    <tbody></tbody>

                </table>

            </div>

            @include('admin.layouts.partials._table-skeleton', [
                'id' => 'communication-logs-skeleton'
            ])

            @include('admin.layouts.partials._table-empty', [
                'id' => 'communication-logs-empty'
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
            var table = $('#communication-logs-table').DataTable({
                serverSide: true,
                processing: false,
                ajax: {
                    url: '{{ route('admin.system.communication-logs.data') }}',
                    data: function (d) {
                        d.status = $('#filter-status').val();
                        d.channel = $('#filter-channel').val();
                    }
                },

                columns: [
                    {
                        data: null,
                        name: 'id',
                        render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user',
                        name: 'user',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'notification',
                        name: 'type',
                        orderable: false
                    },
                    {
                        data: 'content',
                        name: 'content',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],

                order: [[5, 'desc']],
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
                    info: 'Showing _START_–_END_ of _TOTAL_ logs',
                    infoEmpty: 'No logs to show',
                    infoFiltered: '(filtered from _MAX_)',
                    lengthMenu: 'Show _MENU_',
                    paginate: {
                        previous: '<i class="ki-duotone ki-arrow-left"></i>',
                        next: '<i class="ki-duotone ki-arrow-right"></i>',
                    },
                },

                initComplete: function () {
                    $('#communication-logs-skeleton').fadeOut(200, function () {
                        $(this).remove();
                    });
                },

                drawCallback: function () {
                    var total = this.api().page.info().recordsDisplay;

                    if (total === 0) {
                        $('#communication-logs-table-wrapper').addClass('d-none');
                        $('#communication-logs-empty').removeClass('d-none');
                    } else {
                        $('#communication-logs-empty').addClass('d-none');
                        $('#communication-logs-table-wrapper').removeClass('d-none');
                    }
                }
            });

            // ── Search ─────────────────────────────────────────────
            var searchTimer;
            $('#dt-search').on('input', function () {
                clearTimeout(searchTimer);
                var q = $(this).val();
                searchTimer = setTimeout(function () {
                    table.search(q).draw();
                }, 400);
            });

            // ── Filter ─────────────────────────────────────────────
            $('#filter-status, #filter-channel').on('change', function () {
                table.ajax.reload();
            });

            // ── Empty Logs ─────────────────────────────────────────
            $('#btn-empty-logs').on('click', function () {
                Swal.fire({
                    title: 'Empty Communication Logs?',
                    text: 'All communication history will be permanently removed.',
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

                    $.post('{{ route('admin.system.communication-logs.empty') }}', {
                        _token: '{{ csrf_token() }}'
                    })
                    .done(function () {
                        table.ajax.reload();
                        toastr.success('Communication logs cleared successfully.');
                    })
                    .fail(function () {
                        toastr.error('Something went wrong.');
                    });
                });
            });

        });
    </script>
@endpush
