                {{-- Bids Table --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-5 mb-0">Received Bids</span>
                        </h3>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative">
                                <i class="ki-outline ki-magnifier fs-6 text-gray-600 position-absolute ms-3 z-index-3"></i>
                                <input type="text" id="bids-search"
                                    class="form-control form-control-sm form-control-solid border border-gray-300 text-gray-900 w-200px ps-9 fw-semibold fs-8"
                                    placeholder="Search bids...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="table-responsive">
                            <table id="bids-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                        <th class="ps-3 min-w-150px rounded-start">Nurse</th>
                                        <th class="min-w-100px">Nurse Amt</th>
                                        <th class="min-w-100px">Comm.</th>
                                        <th class="min-w-100px">Total</th>
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
    let table = $('#bids-table').DataTable({
        serverSide: true,
        processing: false,
        ajax: {
            url: '{{ route('admin.requests.bids-data', $careRequest->id) }}'
        },
        columns: [
            { data: 'nurse', name: 'nurse', orderable: false, searchable: true, className: 'ps-3' },
            { data: 'nurse_amount', name: 'nurse_amount' },
            { data: 'commission_amount', name: 'commission_amount' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end pe-3' },
        ],
        order: [[3, 'asc']], // Order by total amount ascending initially
        pageLength: 5,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        dom:
            "<'row'<'col-12'tr>>" +
            "<'row align-items-center mt-3 pt-3 flex-nowrap'" +
            "<'col-sm-12 col-md-5 fs-8 text-gray-600 fw-semibold'i>" +
            "<'col-sm-12 col-md-7 d-flex justify-content-md-end align-items-center gap-2'lp>>",
        language: {
            emptyTable: '<span class="text-gray-500 fs-7">No bids received yet.</span>',
            zeroRecords: '<span class="text-gray-500 fs-7">No matching bids found.</span>',
            info: 'Showing _START_ to _END_ of _TOTAL_',
            lengthMenu: '_MENU_',
            paginate: {
                previous: '<i class="ki-outline ki-arrow-left fs-8"></i>',
                next: '<i class="ki-outline ki-arrow-right fs-8"></i>',
            },
        }
    });

    // ── Search ───────────────────────────────────────────────────────
    let searchTimer;
    $('#bids-search').on('input', function () {
        clearTimeout(searchTimer);
        let query = $(this).val();
        searchTimer = setTimeout(function () {
            table.search(query).draw();
        }, 400);
    });
});
</script>
@endpush
