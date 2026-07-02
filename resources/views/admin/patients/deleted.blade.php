@extends('admin.layouts.app')
@section('title', 'Deleted Patients')

@section('content')

    <x-breadcrumb :items="[
            ['label' => 'Patients', 'url' => route('admin.patients.index')],
            ['label' => 'Deleted Patients'],
        ]" />

    <div class="card shadow-sm">
        <div class="card-header border-0 pt-5 pb-3">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-3">
                {{-- Search --}}
                <div class="d-flex align-items-center position-relative">
                    <i class="ki-duotone ki-magnifier fs-5 text-gray-900 position-absolute ms-4 z-index-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" id="dt-search"
                        class="form-control form-control-transparent border border-gray-800 text-gray-900 w-250px ps-11 pe-4 fs-7 fw-semibold shadow-sm"
                        placeholder="Search deleted patients..." />
                </div>

                <div class="d-flex align-items-center gap-2">
                    {{-- Refresh Button --}}
                    <button type="button" class="btn btn-icon btn-light btn-active-light-primary border border-gray-300 w-35px h-35px" id="refresh-table-btn" data-bs-toggle="tooltip" title="Refresh">
                        <i class="ki-outline ki-arrows-circle fs-3"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body py-4">

            <div id="patients-table-wrapper" class="table-responsive">
                <table id="patients-table" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100">
                    <thead>
                        <tr class="text-start text-gray-900 fw-medium fs-7 text-uppercase gs-0 border-bottom border-gray-200 border-1">
                            <th class="w-50px">S.No</th>
                            <th class="min-w-250px">Patient</th>
                            <th class="min-w-140px">Status</th>
                            <th class="min-w-150px">Last Login</th>
                            <th class="min-w-150px">Deleted At</th>
                            <th class="text-end min-w-120px pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            @include('admin.layouts.partials._table-skeleton', ['id' => 'patients-skeleton'])
            @include('admin.layouts.partials._table-empty', ['id' => 'patients-empty'])

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
            var table = $('#patients-table').DataTable({
                serverSide: true,
                processing: false,
                ajax: {
                    url: '{{ route('admin.patients.deleted.data') }}'
                },
                columns: [
                    { data: null, name: 'id', render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }, orderable: false, searchable: false },
                    { data: 'name', name: 'name', orderable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'last_login_at', name: 'last_login_at', orderable: false, searchable: false },
                    { data: 'deleted_at', name: 'deleted_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-3' },
                ],
                order: [[4, 'desc']],
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
                    info: 'Showing _START_–_END_ of _TOTAL_ patients',
                    infoEmpty: 'No patients to show',
                    infoFiltered: '(filtered from _MAX_)',
                    lengthMenu: 'Show _MENU_',
                    paginate: {
                        previous: '<i class="ki-duotone ki-arrow-left"></i>',
                        next: '<i class="ki-duotone ki-arrow-right"></i>',
                    },
                },
                initComplete: function () {
                    $('#patients-skeleton').fadeOut(200, function () { $(this).remove(); });
                },
                drawCallback: function () {
                    var total = this.api().page.info().recordsDisplay;
                    if (total === 0) {
                        $('#patients-table-wrapper').addClass('d-none');
                        $('#patients-empty').removeClass('d-none');
                    } else {
                        $('#patients-empty').addClass('d-none');
                        $('#patients-table-wrapper').removeClass('d-none');
                    }
                }
            });

            var searchTimer;
            $('#dt-search').on('input', function () {
                clearTimeout(searchTimer);
                var q = $(this).val();
                searchTimer = setTimeout(function () { table.search(q).draw(); }, 400);
            });

            $('#refresh-table-btn').on('click', function () { 
                table.ajax.reload(function() {
                    toastr.success('Refreshed successfully.');
                }, false); 
            });

            // Restore
            $(document).on('click', '.btn-restore', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Restore Patient?',
                    text: 'This patient will be active again.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Restore',
                    customClass: { confirmButton: 'btn btn-success', cancelButton: 'btn btn-light ms-2' },
                    buttonsStyling: false,
                }).then(function (r) {
                    if (!r.isConfirmed) return;
                    $.post('/admin/patients/' + id + '/restore', { _token: '{{ csrf_token() }}' })
                        .done(function () { table.ajax.reload(null, false); toastr.success('Patient restored.'); })
                        .fail(function () { toastr.error('Something went wrong.'); });
                });
            });
        });
    </script>
@endpush
