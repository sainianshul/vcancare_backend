@extends('admin.layouts.app')

@section('title', 'Care Requests')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Care Requests', 'url' => route('admin.requests.index')],
        ['label' => 'All Requests'],
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
                        placeholder="Search requests..."
                    />
                </div>

                {{-- Right Controls --}}
                <div class="d-flex align-items-center gap-2">

                    @if(empty($isToday))
                    {{-- Date Filter --}}
                    <div style="width: 175px;">
                        <div class="position-relative">
                            <i class="ki-duotone ki-calendar fs-5 text-gray-900 position-absolute top-50 start-0 translate-middle-y ms-4 z-index-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input
                                type="date"
                                class="form-control form-control-transparent border border-gray-800 text-gray-900 form-control-sm fw-semibold ps-11 shadow-sm"
                                placeholder="Filter by Date"
                                id="filter-date"
                            />
                        </div>
                    </div>
                    @endif

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
                                @foreach (\App\Models\CareRequest::getStatusList() as $value => $label)
                                    <option value="{{ $value }}">
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body py-4">

            <div id="requests-table-wrapper" class="table-responsive">
                <table
                    id="requests-table"
                    class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100"
                >
                    <thead>
                        <tr class="text-start text-gray-900 fw-medium fs-7 text-uppercase gs-0 border-bottom border-gray-200 border-1">
                            <th class="w-50px">ID</th>
                            <th class="min-w-200px">User</th>
                            <th class="min-w-150px">Status</th>
                            <th class="min-w-150px">Date & Time</th>
                            <th class="min-w-200px">Location</th>
                            <th class="min-w-150px">Bidding Ends At</th>
                            <th class="min-w-150px">Created At</th>
                            <th class="text-end min-w-120px pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            @include('admin.layouts.partials._table-skeleton', [
                'id' => 'requests-skeleton'
            ])

            @include('admin.layouts.partials._table-empty', [
                'id' => 'requests-empty'
            ])

        </div>
    </div>

@endsection

{{-- DataTables Bundle --}}
@push('datatables_css')
    @include('admin.layouts.partials._datatable-cdn-css')
@endpush

@push('datatables_js')
    @include('admin.layouts.partials._datatable-cdn-js')

    <script>
        $(function () {
            // ── Init ──────────────────────────────────────────────────────────
            let table = $('#requests-table').DataTable({
                serverSide: true,
                processing: false,
                ajax: {
                    url: '{{ route('admin.requests.data') }}',
                    data: function (d) {
                        d.status = $('#filter-status').val();
                        d.date = $('#filter-date').val();
                        d.is_today = '{{ $isToday ?? false }}';
                    }
                },
                columns: [
                    { data: 'reference_id', name: 'reference_id' },
                    { data: 'user', name: 'user', orderable: false, searchable: false },
                    { data: 'status', name: 'status' },
                    { data: 'date_time', name: 'date_time', orderable: false, searchable: false },
                    { data: 'location', name: 'location', orderable: false, searchable: false },
                    { data: 'bidding_ends_at', name: 'bidding_ends_at' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-3' },
                ],
                order: [[6, 'desc']],
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
                    info: 'Showing _START_–_END_ of _TOTAL_ requests',
                    infoEmpty: 'No requests to show',
                    infoFiltered: '(filtered from _MAX_)',
                    lengthMenu: 'Show _MENU_',
                    paginate: {
                        previous: '<i class="ki-duotone ki-arrow-left"></i>',
                        next: '<i class="ki-duotone ki-arrow-right"></i>',
                    },
                },
                initComplete: function () {
                    $('#requests-skeleton').fadeOut(200, function () {
                        $(this).remove();
                    });
                },
                drawCallback: function () {
                    let total = this.api().page.info().recordsDisplay;
                    if (total === 0) {
                        $('#requests-table-wrapper').addClass('d-none');
                        $('#requests-empty').removeClass('d-none');
                    } else {
                        $('#requests-empty').addClass('d-none');
                        $('#requests-table-wrapper').removeClass('d-none');
                    }
                    $('[data-bs-toggle="tooltip"]').tooltip({ trigger: 'hover' });
                }
            });

            // ── Search ───────────────────────────────────────────────────────
            let searchTimer;
            $('#dt-search').on('input', function () {
                clearTimeout(searchTimer);
                let query = $(this).val();
                searchTimer = setTimeout(function () {
                    table.search(query).draw();
                }, 400);
            });

            // ── Status Filter ────────────────────────────────────────────────
            $('#filter-status').on('change', function () {
                table.ajax.reload();
            });

            @if(empty($isToday))
            // ── Date Filter ──────────────────────────────────────────────────
            $('#filter-date').on('change', function () {
                table.ajax.reload();
            });
            @endif

            // ── Delete ───────────────────────────────────────────────────────
            $(document).on('click', '.btn-delete', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Delete Request?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-light ms-2'
                    },
                    buttonsStyling: false,
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    $.post('/admin/requests/' + id, {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    })
                    .done(function () {
                        table.ajax.reload(null, false);
                        toastr.success('Care request deleted.');
                    })
                    .fail(function () {
                        toastr.error('Something went wrong.');
                    });
                });
            });
        });
    </script>
@endpush
