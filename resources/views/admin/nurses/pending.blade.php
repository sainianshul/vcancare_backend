@extends('admin.layouts.app')
@section('title', 'Pending Approval')

@section('content')

    <x-breadcrumb :items="[
            ['label' => 'Nurses', 'url' => route('admin.nurses.index')],
            ['label' => 'Pending Approval'],
        ]" />

    <div class="card shadow-sm">

        <div class="card-header border-0 pt-5 pb-3">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-3">

                <div class="d-flex align-items-center position-relative">
                    <i class="ki-duotone ki-magnifier fs-5 text-gray-900 position-absolute ms-4 z-index-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" id="dt-search"
                        class="form-control form-control-transparent border border-gray-800 text-gray-900 w-250px ps-11 pe-4 fs-7 fw-semibold shadow-sm"
                        placeholder="Search nurses..." />
                </div>

                <div class="d-flex align-items-center gap-2">

                </div>

            </div>
        </div>

        <div class="card-body py-4">

            <div id="nurses-table-wrapper">
                <table id="nurses-table" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100">
                    <thead>
                        <tr class="text-start text-gray-900 fw-medium fs-7 text-uppercase gs-0 border-bottom border-gray-200 border-1">
                            <th class="w-50px">#</th>
                            <th class="min-w-320px">Nurse</th>
                            <th class="min-w-160px">Location</th>
                            <th class="min-w-170px">Profile Status</th>
                            <th class="min-w-140px">Joined</th>
                            <th class="text-end min-w-120px pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            @include('admin.layouts.partials._table-skeleton', ['id' => 'nurses-skeleton'])

            @include('admin.layouts.partials._table-empty', ['id' => 'nurses-empty'])

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

            var table = $('#nurses-table').DataTable({
                serverSide: true,
                processing: false,
                ajax: { url: '{{ route('admin.nurses.pending.data') }}' },
                columns: [
                    { data: 'id', name: 'id', searchable: false },
                    { data: 'nurse', name: 'nurse', orderable: false },
                    { data: 'location', name: 'location', orderable: false, searchable: false },
                    { data: 'profile_status', name: 'profile_status', orderable: false, searchable: false },
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
                    info: 'Showing _START_–_END_ of _TOTAL_ nurses',
                    infoEmpty: 'No nurses to show',
                    infoFiltered: '(filtered from _MAX_)',
                    lengthMenu: 'Show _MENU_',
                    paginate: {
                        previous: '<i class="ki-duotone ki-arrow-left"></i>',
                        next: '<i class="ki-duotone ki-arrow-right"></i>',
                    },
                },

                initComplete: function () {
                    $('#nurses-skeleton').fadeOut(200, function () { $(this).remove(); });
                },
                drawCallback: function () {
                    var total = this.api().page.info().recordsDisplay;
                    if (total === 0) {
                        $('#nurses-table-wrapper').addClass('d-none');
                        $('#nurses-empty').removeClass('d-none');
                    } else {
                        $('#nurses-empty').addClass('d-none');
                        $('#nurses-table-wrapper').removeClass('d-none');
                    }
                }
            });

            var searchTimer;
            $('#dt-search').on('input', function () {
                clearTimeout(searchTimer);
                var q = $(this).val();
                searchTimer = setTimeout(function () { table.search(q).draw(); }, 400);
            });



            $(document).on('click', '.btn-delete', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Delete Nurse?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-light ms-2' },
                    buttonsStyling: false,
                }).then(function (r) {
                    if (!r.isConfirmed) return;
                    $.post('/admin/nurses/' + id, { _method: 'DELETE', _token: '{{ csrf_token() }}' })
                        .done(function () { table.ajax.reload(null, false); toastr.success('Nurse deleted.'); })
                        .fail(function () { toastr.error('Something went wrong.'); });
                });
            });
        });
    </script>
@endpush



