                {{-- Payment Logs Table (AJAX) --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-5 mb-0">Payment Logs</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="table-responsive">
                            <table id="payment-logs-table" class="table align-middle table-row-dashed table-row-gray-200 gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-gray-700 bg-light fs-8 text-uppercase gs-0">
                                        <th class="ps-3 rounded-start">Event</th>
                                        <th>Amount</th>
                                        <th>Gateway</th>
                                        <th>Order ID</th>
                                        <th>Payment ID</th>
                                        <th>Status</th>
                                        <th class="rounded-end">Time</th>
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
    $('#payment-logs-table').DataTable(Object.assign({}, getDtOpts('payment-logs-skeleton', 'payment-logs-table-wrapper'), {
        ajax: '{{ route('admin.bookings.payment-logs-data', $booking->id) }}',
        columns: [
            { data: 'event', className: 'ps-3' },
            { data: 'amount' },
            { data: 'gateway' },
            { data: 'gateway_order_id' },
            { data: 'gateway_payment_id' },
            { data: 'status' },
            { data: 'created_at', className: 'text-gray-700 fs-8' }
        ]
    }));
});
</script>
@endpush
