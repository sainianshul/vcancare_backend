<div class="card card-bordered border-gray-300">
    <div class="card-body py-6">
        @include('admin.layouts.partials._table-skeleton', ['id' => 'patient-bookings-skeleton'])
        
        <div id="patient-bookings-table-wrapper" class="table-responsive d-none">
            <table id="patient-bookings-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-4 w-100" data-server-side="true">
                <thead>
                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                        <th class="ps-3 rounded-start min-w-100px">Reference</th>
                        <th class="min-w-150px">Nurse</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-100px">Payment</th>
                        <th class="min-w-100px">Total</th>
                        <th class="rounded-end min-w-125px">Created At</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    if (typeof jQuery !== 'undefined') {
        $('#patient-bookings-table').DataTable({
            processing: false,
            serverSide: true,
            ajax: '{{ route('admin.patients.bookings.data', $patient->id) }}',
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
                emptyTable: "No bookings found for this patient.",
                info: "Showing _START_ to _END_ of _TOTAL_ bookings",
                infoEmpty: "Showing 0 to 0 of 0 bookings",
                paginate: {
                    previous: '<i class="ki-duotone ki-arrow-left"></i>',
                    next: '<i class="ki-duotone ki-arrow-right"></i>',
                }
            },
            columns: [
                { data: 'reference_id', className: 'ps-3' },
                { data: 'nurse' },
                { data: 'status' },
                { data: 'payment_status' },
                { data: 'total_amount' },
                { data: 'created_at' }
            ],
            initComplete: function () {
                $('#patient-bookings-skeleton').fadeOut(200, function () {
                    $(this).remove();
                    $('#patient-bookings-table-wrapper').removeClass('d-none').hide().fadeIn(200);
                });
            }
        });
    }
</script>
