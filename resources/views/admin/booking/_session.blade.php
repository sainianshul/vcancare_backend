                {{-- Sessions Table (AJAX) --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-5 mb-0">Sessions</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">{{ $booking->completed_sessions }} of {{ $booking->total_sessions }} completed</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                            @include('admin.layouts.partials._table-skeleton', ['id' => 'sessions-skeleton'])
                            <div id="sessions-table-wrapper" class="d-none">
                                <table id="sessions-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3 w-100">
                                    <thead>
                                        <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                            <th class="ps-3 rounded-start">#</th>
                                            <th class="min-w-100px">Date</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Started At</th>
                                            <th>Ended At</th>
                                            <th>Status</th>
                                            <th>OTP</th>
                                            <th class="rounded-end">Notes</th>
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
    $('#sessions-table').DataTable(Object.assign({}, getDtOpts('sessions-skeleton', 'sessions-table-wrapper'), {
        ajax: '{{ route('admin.bookings.sessions-data', $booking->id) }}',
        columns: [
            { data: 'session_number', className: 'ps-3 fw-bold text-gray-800' },
            { data: 'session_date', className: 'fw-semibold text-gray-800' },
            { data: 'start_time', className: 'text-gray-700' },
            { data: 'end_time', className: 'text-gray-700' },
            { data: 'started_at', className: 'text-gray-700 fs-8' },
            { data: 'ended_at', className: 'text-gray-700 fs-8' },
            { data: 'status' },
            { data: 'otp_verified' },
            { data: 'nurse_notes', className: 'text-gray-600 fs-8', render: function(data) {
                return '<div style="max-width:150px; white-space:normal;">' + data + '</div>';
            }}
        ]
    }));
});
</script>
@endpush
