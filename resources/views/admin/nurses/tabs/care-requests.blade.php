<div class="card shadow-sm border border-gray-300">
    <div class="card-body pt-6 pb-5">
        @include('admin.layouts.partials._table-skeleton', ['id' => 'nurse-care-requests-skeleton'])
        
        <div id="nurse-care-requests-table-wrapper" class="table-responsive d-none">
            <table id="nurse-care-requests-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-4 w-100" data-server-side="true">
                <thead>
                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                        <th class="ps-3 rounded-start min-w-100px">Request ID</th>
                        <th class="min-w-150px">Patient</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-125px">Expires At</th>
                        <th class="rounded-end min-w-125px">Notified At</th>
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
        $('#nurse-care-requests-table').DataTable({
            processing: false,
            serverSide: true,
            ajax: '{{ route('admin.nurses.care-requests.data', $user->id) }}',
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
                emptyTable: "No active care requests found for this nurse.",
                info: "Showing _START_ to _END_ of _TOTAL_ requests",
                infoEmpty: "Showing 0 to 0 of 0 requests",
                paginate: {
                    previous: '<i class="ki-duotone ki-arrow-left"></i>',
                    next: '<i class="ki-duotone ki-arrow-right"></i>',
                }
            },
            columns: [
                { data: 'request', className: 'ps-3' },
                { data: 'patient' },
                { data: 'status' },
                { data: 'expires_at' },
                { data: 'created_at' }
            ],
            initComplete: function () {
                $('#nurse-care-requests-skeleton').fadeOut(200, function () {
                    $(this).remove();
                    $('#nurse-care-requests-table-wrapper').removeClass('d-none').hide().fadeIn(200);
                });
            }
        });
    }
</script>
