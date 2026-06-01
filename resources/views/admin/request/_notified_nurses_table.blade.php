                {{-- Notified Nurses Table --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-5 mb-0">Notified Nurses</span>
                        </h3>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative">
                                <i class="ki-outline ki-magnifier fs-6 text-gray-600 position-absolute ms-3 z-index-3"></i>
                                <input type="text" id="nurses-search"
                                    class="form-control form-control-sm form-control-solid border border-gray-300 text-gray-900 w-200px ps-9 fw-semibold fs-8"
                                    placeholder="Search nurses...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="table-responsive">
                            <table id="notified-nurses-table"
                                class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                        <th class="ps-3 min-w-150px rounded-start">Nurse</th>
                                        <th class="min-w-100px">Dist.</th>
                                        <th class="min-w-100px">Notified At</th>
                                        <th class="min-w-100px">Status</th>
                                        <th class="text-end pe-3 rounded-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

@push('datatables_js')
<script>
$(document).ready(function () {
    // ── Notified Nurses DataTable ─────────────────────────────────────
    let nursesTable = $('#notified-nurses-table').DataTable({
        serverSide: true,
        processing: false,
        ajax: {
            url: '{{ route('admin.requests.notified-nurses-data', $careRequest->id) }}'
        },
        columns: [
            { data: 'nurse', name: 'nurse', orderable: false, searchable: true, className: 'ps-3' },
            { data: 'distance', name: 'distance', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-3' },
        ],
        order: [[2, 'desc']], // Order by notified at descending initially
        pageLength: 5,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        dom:
            "<'row'<'col-12'tr>>" +
            "<'row align-items-center mt-3 pt-3 flex-nowrap'" +
            "<'col-sm-12 col-md-5 fs-8 text-gray-600 fw-semibold'i>" +
            "<'col-sm-12 col-md-7 d-flex justify-content-md-end align-items-center gap-2'lp>>",
        language: {
            emptyTable: '<span class="text-gray-500 fs-7">No nurses notified yet.</span>',
            zeroRecords: '<span class="text-gray-500 fs-7">No matching nurses found.</span>',
            info: 'Showing _START_ to _END_ of _TOTAL_',
            lengthMenu: '_MENU_',
            paginate: {
                previous: '<i class="ki-outline ki-arrow-left fs-8"></i>',
                next: '<i class="ki-outline ki-arrow-right fs-8"></i>',
            },
        }
    });

    // ── Search Notified Nurses ───────────────────────────────────────
    let nursesSearchTimer;
    $('#nurses-search').on('input', function () {
        clearTimeout(nursesSearchTimer);
        let query = $(this).val();
        nursesSearchTimer = setTimeout(function () {
            nursesTable.search(query).draw();
        }, 400);
    });
});
</script>
@endpush
