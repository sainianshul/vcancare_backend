@extends('admin.layouts.app')

@section('title', 'View Bid Details')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Care Requests', 'url' => route('admin.requests.index')],
        ['label' => 'Request #' . ($bid->careRequest->reference_id ?? 'N/A'), 'url' => route('admin.requests.show', $bid->care_request_id)],
        ['label' => 'Bid Details'],
    ]" />

    <div class="d-flex flex-column gap-7 gap-lg-10">

        {{-- ── HEADER ───────────────────────────────────────────────────────── --}}
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.requests.show', $bid->care_request_id) }}" class="btn btn-icon btn-light btn-active-secondary btn-sm border border-gray-300">
                    <i class="ki-outline ki-arrow-left fs-4 text-gray-700"></i>
                </a>
                <h1 class="fw-bold text-gray-900 fs-3 mb-0">
                    Bid Details 
                </h1>
                
                @php
                    $bidColors = [
                        \App\Models\RequestBid::STATUS_PENDING => 'warning',
                        \App\Models\RequestBid::STATUS_SELECTED => 'success',
                        \App\Models\RequestBid::STATUS_REJECTED => 'danger',
                        \App\Models\RequestBid::STATUS_CANCELLED => 'dark',
                    ];
                    $bColor = $bidColors[$bid->status ?? 0] ?? 'dark';
                @endphp
                <span class="badge badge-light-{{ $bColor }} text-{{ $bColor }} fs-8 px-3 py-2 border border-{{ $bColor }}">
                    {{ $bid->status_text ?? 'Unknown' }}
                </span>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <span class="text-gray-500 fs-8 fw-semibold">Submitted {{ $bid->created_at ? $bid->created_at->diffForHumans() : 'N/A' }}</span>
            </div>
        </div>

        <div class="row g-7">
            {{-- ── LEFT COLUMN ─────────────────────────────────── --}}
            <div class="col-lg-8">
                
                {{-- Financials Card --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 pb-2 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-gray-900 fs-5 mb-0">Financial Breakdown</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="row g-5">
                            <div class="col-sm-4">
                                <div class="bg-light rounded p-4 border border-gray-200">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ki-outline ki-wallet fs-3 text-primary me-2"></i>
                                        <span class="text-gray-700 fw-bold fs-7">Nurse Earnings</span>
                                    </div>
                                    <span class="fw-bolder fs-3 text-gray-900">₹{{ number_format($bid->nurse_amount ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="bg-light-success rounded p-4 border border-success border-dashed">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ki-outline ki-bank fs-3 text-success me-2"></i>
                                        <span class="text-success fw-bold fs-7">Platform Commission</span>
                                    </div>
                                    <span class="fw-bolder fs-3 text-success">₹{{ number_format($bid->commission_amount ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="bg-gray-900 rounded p-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ki-outline ki-bill fs-3 text-white me-2"></i>
                                        <span class="text-gray-300 fw-bold fs-7">Total (Patient Pays)</span>
                                    </div>
                                    <span class="fw-bolder fs-3 text-white">₹{{ number_format($bid->total_amount ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nurse Details --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 pb-2 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-gray-900 fs-5 mb-0">Nurse Profile</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.nurses.show', $bid->nurse->user_id ?? 0) }}" class="btn btn-sm btn-light-primary fw-bold fs-8 px-3 py-2">
                                View Profile
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex align-items-sm-center flex-column flex-sm-row mb-5">
                            <div class="symbol symbol-60px symbol-circle me-4 mb-3 mb-sm-0 shadow-sm" style="border: 3px solid #fff;">
                                @if(isset($bid->nurse->user->profile_photo) && $bid->nurse->user->profile_photo)
                                    <img src="{{ Storage::url($bid->nurse->user->profile_photo) }}" alt="{{ $bid->nurse->user->name ?? 'Nurse' }}" class="object-fit-cover" />
                                @else
                                    <span class="symbol-label bg-light-primary text-primary fw-bolder fs-2 border border-primary">
                                        {{ mb_strtoupper(mb_substr($bid->nurse->user->name ?? '?', 0, 2)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <span class="text-gray-900 fw-bolder fs-4 mb-1">{{ $bid->nurse->user->name ?? 'Unknown' }}</span>
                                <span class="text-gray-600 fw-semibold fs-7 mb-2">
                                    <i class="ki-outline ki-sms fs-6 me-1"></i> {{ $bid->nurse->user->phone ?? $bid->nurse->user->email ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="separator separator-dashed mb-5"></div>
                        
                        <div class="row g-4">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-verify fs-4 text-primary me-2"></i>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-500 fs-8 fw-semibold">Nurse ID</span>
                                        <span class="text-gray-900 fs-7 fw-bold">{{ $bid->nurse_id }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-star fs-4 text-warning me-2"></i>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-500 fs-8 fw-semibold">Rating</span>
                                        <span class="text-gray-900 fs-7 fw-bold">{{ $bid->nurse->rating ?? '0.0' }} / 5.0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <span class="text-gray-500 fs-8 fw-semibold d-block mb-2">Specialities (Care Types)</span>
                                <div class="d-flex flex-wrap gap-2">
                                    @if(isset($bid->nurse->careTypes) && $bid->nurse->careTypes->count() > 0)
                                        @foreach($bid->nurse->careTypes as $spec)
                                            <span class="badge badge-light text-gray-700 border border-gray-300 fs-8">{{ $spec->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-600 fs-7">No specialities listed.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Comments Component --}}
                <x-comments 
                    type="{{ \App\Models\RequestBid::class }}"
                    :model-id="$bid->id"
                />

            </div>
            
            {{-- ── RIGHT COLUMN ────────────────────────── --}}
            <div class="col-lg-4">
                
                {{-- Request Context Card --}}
                <div class="card shadow-sm mb-7 border border-gray-300">
                    <div class="card-header border-0 pt-4 pb-2 min-h-50px">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-gray-900 fs-5 mb-0">Request Summary</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.requests.show', $bid->care_request_id ?? 0) }}" class="btn btn-sm btn-icon btn-light border border-gray-300 w-25px h-25px">
                                <i class="ki-outline ki-arrow-right fs-6 text-gray-700"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-5">
                        <div class="d-flex flex-column gap-4">
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Ref ID</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $bid->careRequest->reference_id ?? 'N/A' }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Care Type</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $bid->careRequest->careType->name ?? 'N/A' }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Patient</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $bid->careRequest->patient_name ?? 'N/A' }} ({{ $bid->careRequest->patient_age ?? '?' }}y)</span>
                            </div>
                            <div class="separator separator-dashed border-gray-300"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-600 fw-semibold fs-7">Account Owner</span>
                                <span class="text-gray-900 fw-bold fs-7">{{ $bid->careRequest->user->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <div class="bg-light-primary rounded p-3 mt-5 border border-primary border-dashed">
                            <div class="d-flex align-items-center mb-1">
                                <i class="ki-outline ki-geolocation fs-5 text-primary me-2"></i>
                                <span class="text-gray-900 fw-bold fs-7">Location</span>
                            </div>
                            <div class="text-gray-700 fs-8 ps-7">
                                {{ $bid->careRequest->address ?? 'N/A' }}<br>
                                {{ $bid->careRequest->city ?? '' }}, {{ $bid->careRequest->state ?? '' }}
                            </div>
                        </div>

                        <a href="{{ route('admin.requests.show', $bid->care_request_id ?? 0) }}"
                            class="btn btn-light-primary border border-primary btn-sm w-100 fw-bold fs-8 px-3 py-2 mt-4">
                            Go to Request <i class="ki-outline ki-arrow-right fs-6 ms-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
