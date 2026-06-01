                {{-- Booking Timeline --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5 mb-0 text-gray-900">Booking Timeline</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="timeline">
                            {{-- Created --}}
                            <div class="timeline-item">
                                <div class="timeline-line w-40px"></div>
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-plus-square fs-3 text-primary"></i>
                                    </div>
                                </div>
                                <div class="timeline-content mb-10 mt-n1">
                                    <div class="pe-3 mb-2">
                                        <div class="fs-6 fw-bold text-gray-900 mb-1">Booking Created</div>
                                        <div class="d-flex align-items-center fs-8 fw-semibold text-gray-600">
                                            <i class="ki-outline ki-time fs-7 me-1"></i>
                                            {{ $booking->created_at->format('d M Y, h:i A') }}
                                            <span class="text-muted ms-2">({{ $booking->created_at->diffForHumans() }})</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge badge-light-primary border border-primary px-2 py-1 fs-9">Ref: {{ $booking->reference_id }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment --}}
                            @if($booking->payment_status >= 1)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-dollar fs-3 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Payment Received</div>
                                            <div class="d-flex align-items-center fs-8 fw-semibold text-gray-600">
                                                <i class="ki-outline ki-time fs-7 me-1"></i>
                                                <span>₹{{ number_format($booking->total_amount, 2) }}</span>
                                                @if($booking->payment_method)
                                                    <span class="badge badge-light-info px-2 py-1 ms-2 fs-9">{{ $booking->payment_method_text }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if($booking->wallet_amount_used > 0)
                                                <span class="badge badge-light-warning border border-warning px-2 py-1 fs-9">Wallet: ₹{{ number_format($booking->wallet_amount_used, 2) }}</span>
                                            @endif
                                            @if($booking->gateway_amount > 0)
                                                <span class="badge badge-light-primary border border-primary px-2 py-1 fs-9">Gateway: ₹{{ number_format($booking->gateway_amount, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Active --}}
                            @if($booking->status >= 2)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-info">
                                            <i class="ki-outline ki-rocket fs-3 text-info"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Booking Activated</div>
                                            <div class="fs-8 fw-semibold text-gray-600">Sessions started being tracked</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Completed --}}
                            @if($booking->status === \App\Models\Booking::STATUS_COMPLETED)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-check-circle fs-3 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Booking Completed</div>
                                            <div class="fs-8 fw-semibold text-gray-600">All {{ $booking->total_sessions }} sessions completed</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Cancelled --}}
                            @if($booking->status === \App\Models\Booking::STATUS_CANCELLED)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-danger">
                                            <i class="ki-outline ki-cross-circle fs-3 text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Booking Cancelled</div>
                                            <div class="d-flex align-items-center fs-8 fw-semibold text-gray-600">
                                                <i class="ki-outline ki-time fs-7 me-1"></i>
                                                {{ $booking->cancelled_at ? $booking->cancelled_at->format('d M Y, h:i A') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Refund --}}
                            @if($booking->refund_amount > 0)
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-warning">
                                            <i class="ki-outline ki-arrow-circle-left fs-3 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-2">
                                            <div class="fs-6 fw-bold text-gray-900 mb-1">Refund Processed</div>
                                            <div class="fs-8 fw-semibold text-gray-600">₹{{ number_format($booking->refund_amount, 2) }} refunded</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Updated --}}
                            <div class="timeline-item">
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light">
                                        <i class="ki-outline ki-time fs-3 text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="timeline-content mt-n1">
                                    <div class="pe-3">
                                        <div class="fs-7 fw-semibold text-gray-600">Last Updated: {{ $booking->updated_at->format('d M Y, h:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
