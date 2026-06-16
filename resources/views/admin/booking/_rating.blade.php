                {{-- Ratings & Reviews Table (AJAX) --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Ratings & Reviews</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        @include('admin.layouts.partials._table-skeleton', ['id' => 'ratings-skeleton'])
                        <div id="ratings-table-wrapper" class="table-responsive d-none">
                            <table id="ratings-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3 w-100" data-server-side="true">
                                <thead>
                                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                        <th class="ps-3 rounded-start min-w-200px">User</th>
                                        <th class="min-w-100px">Rating</th>
                                        <th class="min-w-200px">Review</th>
                                        <th class="rounded-end min-w-100px">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



@push('datatables_js')
<script>
$(document).ready(function () {
    $('#ratings-table').DataTable(Object.assign({}, getDtOpts('ratings-skeleton', 'ratings-table-wrapper'), {
        ajax: '{{ route('admin.bookings.reviews-data', $booking->id) }}',
        columns: [
            { data: 'user', className: 'ps-3' },
            { data: 'rating' },
            { data: 'review', className: 'text-gray-700 fs-7 text-wrap' },
            { data: 'created_at' }
        ]
    }));
});
</script>
@endpush
