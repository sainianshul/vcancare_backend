                {{-- Cancellation Info --}}
                @if($booking->isCancelled())
                    <div class="card shadow-sm mb-7 bg-light-danger border border-danger border-dashed">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-cross-circle fs-2 text-danger me-2"></i>
                                <span class="fw-bold text-gray-900 fs-5">Cancellation Details</span>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex flex-stack">
                                    <span class="text-gray-600 fw-semibold fs-7">Cancelled By</span>
                                    <span class="fw-bold fs-7 text-gray-900">
                                        @switch($booking->cancelled_by)
                                            @case(1) <span class="badge badge-light-warning border border-warning">User</span> @break
                                            @case(2) <span class="badge badge-light-info border border-info">Nurse</span> @break
                                            @case(3) <span class="badge badge-light-danger border border-danger">Admin</span> @break
                                            @case(4) <span class="badge badge-light-secondary border border-secondary">System</span> @break
                                            @default <span class="text-muted">Unknown</span>
                                        @endswitch
                                    </span>
                                </div>
                                <div class="separator separator-dashed border-gray-300"></div>
                                <div class="d-flex flex-stack">
                                    <span class="text-gray-600 fw-semibold fs-7">Cancelled At</span>
                                    <span class="fw-bold fs-7 text-gray-900">{{ $booking->cancelled_at ? $booking->cancelled_at->format('d M Y, h:i A') : 'N/A' }}</span>
                                </div>
                                @if($booking->cancellation_reason)
                                    <div class="separator separator-dashed border-gray-300"></div>
                                    <div>
                                        <span class="text-gray-600 fw-semibold fs-7 d-block mb-1">Reason</span>
                                        <span class="fw-semibold fs-7 text-gray-900">{{ $booking->cancellation_reason }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
