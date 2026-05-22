@extends('admin.layouts.app')

@section('title', 'Bids')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Bids', 'url' => route('admin.bids.index')],
        ['label' => $title ?? 'All Bids'],
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
                        placeholder="Search bids..."
                    />
                </div>

                {{-- Right Controls --}}
                <div class="d-flex align-items-center gap-2">

                    {{-- Date Filter --}}
                    <div style="width: 175px;">
                        <div class="position-relative">
                            <i class="ki-duotone ki-calendar fs-5 text-gray-900 position-absolute top-50 start-0 translate-middle-y ms-4 z-index-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input
                                type="text"
                                class="form-control form-control-transparent border border-gray-800 text-gray-900 form-control-sm fw-semibold ps-11 pe-8 shadow-sm cursor-pointer"
                                placeholder="Filter by Date"
                                id="filter-date"
                            />
                            <i class="ki-duotone ki-cross fs-3 text-gray-600 position-absolute top-50 end-0 translate-middle-y me-2 z-index-3 cursor-pointer d-none" id="clear-date-btn">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>

                    @if(!isset($hideStatusFilter))
                    {{-- Status Filter --}}
                    <div style="width: 160px;">
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
                                @foreach (\App\Models\RequestBid::getStatusList() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body py-4">

            <div id="bids-table-wrapper" class="table-responsive">
                <table
                    id="bids-table"
                    class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100"
                >
                    <thead>
                        <tr class="text-start text-gray-900 fw-medium fs-7 text-uppercase gs-0 border-bottom border-gray-200 border-1">
                            <th class="w-50px">ID</th>
                            <th class="min-w-175px">Care Request</th>
                            <th class="min-w-175px">Nurse</th>
                            <th class="min-w-120px">Amount</th>
                            <th class="min-w-120px">Status</th>
                            <th class="min-w-130px">Created At</th>
                            <th class="text-end min-w-80px pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            @include('admin.layouts.partials._table-skeleton', [
                'id' => 'bids-skeleton'
            ])

            @include('admin.layouts.partials._table-empty', [
                'id' => 'bids-empty'
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
            let table = $('#bids-table').DataTable({
                serverSide: true,
                processing: false,
                ajax: {
                    url: '{!! $dataUrl ?? route("admin.bids.data") !!}',
                    data: function (d) {
                        if ($('#filter-status').length) {
                            d.status = $('#filter-status').val();
                        }
                        d.date = $('#filter-date').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'care_request', name: 'care_request', orderable: false, searchable: false },
                    { data: 'nurse', name: 'nurse', orderable: false, searchable: false },
                    { data: 'amount', name: 'total_amount' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-3' },
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
                    info: 'Showing _START_–_END_ of _TOTAL_ bids',
                    infoEmpty: 'No bids to show',
                    infoFiltered: '(filtered from _MAX_)',
                    lengthMenu: 'Show _MENU_',
                    paginate: {
                        previous: '<i class="ki-duotone ki-arrow-left"></i>',
                        next: '<i class="ki-duotone ki-arrow-right"></i>',
                    },
                },
                initComplete: function () {
                    $('#bids-skeleton').fadeOut(200, function () {
                        $(this).remove();
                    });
                },
                drawCallback: function () {
                    let total = this.api().page.info().recordsDisplay;
                    if (total === 0) {
                        $('#bids-table-wrapper').addClass('d-none');
                        $('#bids-empty').removeClass('d-none');
                    } else {
                        $('#bids-empty').addClass('d-none');
                        $('#bids-table-wrapper').removeClass('d-none');
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

            // ── Filters ─────────────────────────────────────────────────────
            $('#filter-status').on('change', function () {
                table.ajax.reload();
            });

            let fp = $('#filter-date').flatpickr({
                altInput: true,
                altFormat: "d M Y",
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    if (dateStr) {
                        $('#clear-date-btn').removeClass('d-none');
                    } else {
                        $('#clear-date-btn').addClass('d-none');
                    }
                    table.ajax.reload();
                }
            });

            $('#clear-date-btn').on('click', function(e) {
                e.stopPropagation();
                fp.clear();
            });
        });
    </script>
@endpush
