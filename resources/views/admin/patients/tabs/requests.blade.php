<div class="card card-bordered border-gray-300">
    <div class="card-body py-6">
        @include('admin.layouts.partials._table-skeleton', ['id' => 'patient-requests-skeleton'])
        
        <div id="patient-requests-table-wrapper" class="table-responsive d-none">
            <table id="patient-requests-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-4 w-100" data-server-side="true">
                <thead>
                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                        <th class="ps-3 rounded-start min-w-100px">Reference</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-100px">Date & Time</th>
                        <th class="min-w-150px">Location</th>
                        <th class="min-w-125px">Bidding Ends At</th>
                        <th class="min-w-125px">Created At</th>
                        <th class="rounded-end text-end min-w-80px pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof jQuery !== 'undefined') {
            $('#patient-requests-table').DataTable({
                processing: false,
                serverSide: true,
                ajax: '{{ route('admin.patients.requests.data', $patient->id) }}',
                paging: true,
                pageLength: 10,
                searching: false,
                info: true,
                ordering: false,
                dom: "<'row'<'col-sm-12'tr>>" +
                     "<'row mt-3'" +
                     "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                     "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
                language: {
                    emptyTable: "No requests found for this patient.",
                    info: "Showing _START_ to _END_ of _TOTAL_ requests",
                    infoEmpty: "Showing 0 to 0 of 0 requests",
                    paginate: {
                        previous: '<i class="ki-duotone ki-arrow-left"></i>',
                        next: '<i class="ki-duotone ki-arrow-right"></i>',
                    }
                },
                columns: [
                    { data: 'reference_id', name: 'reference_id', className: 'ps-3' },
                    { data: 'status', name: 'status' },
                    { data: 'date_time', name: 'date_time', orderable: false, searchable: false },
                    { data: 'location', name: 'location', orderable: false, searchable: false },
                    { data: 'bidding_ends_at', name: 'bidding_ends_at' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-3' },
                ],
                order: [[5, 'desc']],
                initComplete: function () {
                    $('#patient-requests-skeleton').fadeOut(200, function () {
                        $(this).remove();
                        $('#patient-requests-table-wrapper').removeClass('d-none').hide().fadeIn(200);
                    });
                }
            });
        }

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
                    $('#patient-requests-table').DataTable().ajax.reload(null, false);
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Care request deleted.');
                    } else {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Care request deleted.', showConfirmButton: false, timer: 1500 });
                    }
                })
                .fail(function () {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Something went wrong.');
                    } else {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Something went wrong.', showConfirmButton: false, timer: 1500 });
                    }
                });
            });
        });

    });
</script>
@endpush
