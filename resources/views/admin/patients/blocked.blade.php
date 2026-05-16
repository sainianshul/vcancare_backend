@extends('admin.layouts.app')

@section('title', 'Blocked Patients')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Patients', 'url' => route('admin.patients.index')],
        ['label' => 'Blocked Patients'],
    ]" />

    <div class="card shadow-sm card-bordered border-gray-300">

        {{-- Toolbar --}}
        <div class="card-header border-bottom border-gray-300 pt-5 pb-3">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-3">

                {{-- Search --}}
                <div class="d-flex align-items-center position-relative">
                    <i class="ki-outline ki-magnifier fs-5 text-gray-500 position-absolute ms-4 z-index-3"></i>
                    <input
                        type="text"
                        id="dt-search"
                        class="form-control form-control-solid w-250px ps-11 pe-4 fs-7 fw-semibold shadow-sm"
                        placeholder="Search blocked patients..."
                    />
                </div>

                {{-- Right Controls --}}
                <div class="d-flex align-items-center gap-2">
                    <a
                        href="{{ route('admin.patients.index') }}"
                        class="btn btn-sm btn-light-primary fw-semibold btn-flex btn-center border border-primary shadow-sm"
                    >
                        <i class="ki-outline ki-users fs-5 me-1"></i>
                        Active Patients
                    </a>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body py-4">

            <div id="patients-table-wrapper">

                <table
                    id="patients-table"
                    class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100"
                >
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                            <th class="w-50px">#</th>
                            <th class="min-w-250px">Patient</th>
                            <th class="min-w-120px">Status</th>
                            <th class="min-w-150px">Blocked Reason</th>
                            <th class="min-w-140px">Blocked Date</th>
                            <th class="text-end min-w-150px pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            @include('admin.layouts.partials._table-skeleton', [
                'id' => 'patients-skeleton'
            ])

            @include('admin.layouts.partials._table-empty', [
                'id' => 'patients-empty'
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

            // ── Init ──────────────────────────────────────────────────────────
            let table = $('#patients-table').DataTable({
                serverSide: true,
                processing: false,

                ajax: {
                    url: '{{ route('admin.patients.blocked.data') }}'
                },

                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'status', name: 'status' },
                    { data: 'blocked_reason', name: 'blocked_reason' },
                    { data: 'blocked_at', name: 'blocked_at' },
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
                    info: 'Showing _START_–_END_ of _TOTAL_ blocked patients',
                    infoEmpty: 'No blocked patients',
                    infoFiltered: '(filtered from _MAX_)',
                    lengthMenu: 'Show _MENU_',
                    paginate: {
                        previous: '<i class="ki-outline ki-arrow-left"></i>',
                        next: '<i class="ki-outline ki-arrow-right"></i>',
                    },
                },

                initComplete: function () {
                    $('#patients-skeleton').fadeOut(200, function () {
                        $(this).remove();
                    });
                },

                drawCallback: function () {
                    let total = this.api().page.info().recordsDisplay;
                    if (total === 0) {
                        $('#patients-table-wrapper').addClass('d-none');
                        $('#patients-empty').removeClass('d-none');
                    } else {
                        $('#patients-empty').addClass('d-none');
                        $('#patients-table-wrapper').removeClass('d-none');
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

            // ── Delete ───────────────────────────────────────────────────────
            $(document).on('click', '.btn-delete', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Delete Blocked Patient?',
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
                    $.post('/admin/patients/' + id, {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    }).done(function () {
                        table.ajax.reload(null, false);
                        toastr.success('Patient deleted.');
                    }).fail(function () {
                        toastr.error('Something went wrong.');
                    });
                });
            });

            // ── Unblock ───────────────────────────────────────────────────────
            $(document).on('click', '.btn-unblock', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Unblock Patient?',
                    text: 'They will regain access to their account.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Unblock',
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-light ms-2'
                    },
                    buttonsStyling: false,
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    $.post('/admin/patients/' + id + '/unblock', {
                        _token: '{{ csrf_token() }}'
                    }).done(function () {
                        table.ajax.reload(null, false);
                        toastr.success('Patient successfully unblocked.');
                    }).fail(function () {
                        toastr.error('Failed to unblock patient.');
                    });
                });
            });

        });
    </script>
@endpush
