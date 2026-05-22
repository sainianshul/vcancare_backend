<div class="card shadow-sm border border-gray-300">
    <div class="card-body pt-6 pb-5">
        @include('admin.layouts.partials._table-skeleton', ['id' => 'nurse-login-skeleton'])
        
        <div id="nurse-login-table-wrapper" class="table-responsive d-none">
            <table id="nurse-login-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-4 w-100" data-server-side="true">
                <thead>
                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                        <th class="ps-3 rounded-start min-w-125px">IP Address</th>
                        <th class="min-w-200px">Device / Browser</th>
                        <th class="min-w-150px">Date & Time</th>
                        <th class="rounded-end min-w-100px text-end pe-3">Actions</th>
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
        $('#nurse-login-table').DataTable({
            processing: false,
            serverSide: true,
            ajax: '{{ route('admin.nurses.login-history.data', $user->id) }}',
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
                emptyTable: "No login history found for this nurse.",
                info: "Showing _START_ to _END_ of _TOTAL_ logins",
                infoEmpty: "Showing 0 to 0 of 0 logins",
                paginate: {
                    previous: '<i class="ki-duotone ki-arrow-left"></i>',
                    next: '<i class="ki-duotone ki-arrow-right"></i>',
                }
            },
            columns: [
                { data: 'ip_address', className: 'ps-3' },
                { data: 'user_agent' },
                { data: 'created_at' },
                { data: 'action', className: 'text-end pe-3', orderable: false, searchable: false }
            ],
            initComplete: function () {
                $('#nurse-login-skeleton').fadeOut(200, function () {
                    $(this).remove();
                    $('#nurse-login-table-wrapper').removeClass('d-none').hide().fadeIn(200);
                });
            }
        });
    }
</script>
