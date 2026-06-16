@extends('admin.layouts.app')

@section('title', 'View Bid Details')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Care Requests', 'url' => route('admin.requests.index')],
        ['label' => 'Request #' . ($bid->careRequest->reference_id ?? 'N/A'), 'url' => route('admin.requests.show', $bid->care_request_id)],
        ['label' => 'Bid Details'],
    ]" />

    <div class="d-flex flex-column gap-7 gap-lg-10">

        <x-alert-success />
        <x-form-errors />

        {{-- ── HEADER ───────────────────────────────────────────────────────── --}}
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url()->previous() }}" class="btn btn-icon btn-light btn-active-secondary btn-sm border border-gray-200">
                    <i class="ki-outline ki-arrow-left fs-4 text-gray-700"></i>
                </a>
                <h1 class="fw-bold text-gray-900 fs-3 mb-0">
                    Bid Details 
                </h1>
                
                <span class="badge badge-light-{{ $bid->status_color }} text-{{ $bid->status_color }} fs-8 px-3 py-2 border border-{{ $bid->status_color }}">
                    {{ $bid->status_text ?? 'Unknown' }}
                </span>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <span class="text-gray-500 fs-8 fw-semibold">Submitted {{ $bid->created_at ? $bid->created_at->diffForHumans() : 'N/A' }}</span>
            </div>
        </div>

        {{-- ── INFO CARDS (ABOVE TABS) ──────────────────────────────────────── --}}
        <div class="row g-5 g-xl-8">
            
            {{-- Nurse Profile Card --}}
            <div class="col-xl-4">
                <div class="card shadow-sm h-100 border border-primary">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                        <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Nurse Profile</h3>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.nurses.show', $bid->nurse->user_id ?? 0) }}" class="btn btn-sm btn-icon btn-light border border-gray-200 w-25px h-25px">
                                <i class="ki-outline ki-arrow-right fs-6 text-gray-700"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="symbol symbol-45px symbol-circle me-4">
                                @if(isset($bid->nurse->user->profile_photo) && $bid->nurse->user->profile_photo)
                                    <img src="{{ Storage::url($bid->nurse->user->profile_photo) }}" alt="{{ $bid->nurse->user->name ?? 'Nurse' }}" class="object-fit-cover" />
                                @else
                                    <span class="symbol-label bg-white text-gray-700 fs-5 fw-bold border border-gray-200">
                                        {{ mb_strtoupper(mb_substr($bid->nurse->user->name ?? 'N', 0, 2)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('admin.nurses.show', $bid->nurse->user_id ?? 0) }}" class="fs-6 text-gray-900 text-hover-primary fw-bold d-block">{{ $bid->nurse->user->name ?? 'Unknown' }}</a>
                                <span class="text-gray-600 fs-8">{{ $bid->nurse->user->phone ?? $bid->nurse->user->email ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="badge badge-light-primary border border-primary fw-bold px-2 py-1 fs-8 text-gray-700">ID: {{ $bid->nurse_id }}</span>
                            <span class="badge badge-light-primary border border-primary fw-bold px-2 py-1 fs-8 text-gray-700"><i class="ki-outline ki-star text-warning fs-8 me-1"></i> {{ $bid->nurse->rating ?? '0.0' }}</span>
                        </div>
                        <a href="{{ route('admin.nurses.show', $bid->nurse->user_id ?? 0) }}" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-sm w-100 text-uppercase fw-bold fs-9 px-3 py-2">
                            View Full Profile <i class="ki-outline ki-arrow-right fs-7 ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Request Summary Card --}}
            <div class="col-xl-4">
                <div class="card shadow-sm h-100 border border-primary">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                        <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Request Summary</h3>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.requests.show', $bid->care_request_id ?? 0) }}" class="btn btn-sm btn-icon btn-light border border-gray-200 w-25px h-25px">
                                <i class="ki-outline ki-arrow-right fs-6 text-gray-700"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex flex-stack">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Ref ID</span>
                                <span class="text-gray-900 fw-bold fs-8">{{ $bid->careRequest->reference_id ?? 'N/A' }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-200"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Care Type</span>
                                <span class="text-gray-900 fw-bold fs-8">{{ $bid->careRequest->careType->name ?? 'N/A' }}</span>
                            </div>
                            <div class="separator separator-dashed border-gray-200"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Patient</span>
                                <span class="text-gray-900 fw-bold fs-8">{{ $bid->careRequest->patient_name ?? 'N/A' }} ({{ $bid->careRequest->patient_age ?? '?' }}y)</span>
                            </div>
                            <div class="separator separator-dashed border-gray-200"></div>
                            <div class="d-flex flex-stack">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Account Owner</span>
                                <span class="text-gray-900 fw-bold fs-8">{{ $bid->careRequest->user->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.requests.show', $bid->care_request_id ?? 0) }}" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-sm w-100 text-uppercase fw-bold fs-9 px-3 py-2 mt-4">
                            Go to Request <i class="ki-outline ki-arrow-right fs-6 ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Location Details Card --}}
            <div class="col-xl-4">
                <div class="card shadow-sm h-100 border border-primary">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                        <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Location & Distance</h3>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        <div class="d-flex align-items-start mb-3">
                            <span class="bullet bullet-vertical h-30px bg-gray-400 me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">Request Location</span>
                                <span class="fw-bold fs-7 text-gray-900 d-block">{{ $bid->careRequest->address ?? 'N/A' }}</span>
                                <span class="fw-semibold fs-8 text-gray-700">{{ $bid->careRequest->city ?? '' }}, {{ $bid->careRequest->state ?? '' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <span class="bullet bullet-vertical h-30px bg-gray-500 me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">Nurse Location</span>
                                <span class="fw-bold fs-7 text-gray-900 d-block">{{ $bid->nurse->address ?? 'N/A' }}</span>
                                <span class="fw-semibold fs-8 text-gray-700">{{ $bid->nurse->city ?? '' }}, {{ $bid->nurse->state ?? '' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="bullet bullet-vertical h-30px bg-gray-600 me-3 mt-1"></span>
                            <div class="flex-grow-1">
                                <span class="text-gray-500 text-uppercase fw-bold d-block fs-9">Approximate Distance</span>
                                <span class="fw-bold fs-7 text-gray-900">{{ $bid->distance_km ?? 'N/A' }} km</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── FULL WIDTH TABS ────────────────────────────────────────────────── --}}
        <div>
            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold mb-5" id="bid-tabs">
                <li class="nav-item">
                    <a class="nav-link active text-gray-600 text-active-primary px-4 py-3" data-bs-toggle="tab" href="#tab-overview">Overview</a>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content" id="bid-tabs-content">

                {{-- ── Overview Tab ──────────────────────────────────────── --}}
                <div class="tab-pane fade show active" id="tab-overview">
                    
                    <div class="row g-5 g-xl-8">
                        {{-- Financial Breakdown --}}
                        <div class="col-xl-6">
                            <div class="card shadow-sm mb-5 border border-primary h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Financial Breakdown</h3>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="bg-white rounded p-4 border border-primary border-dashed ">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ki-outline ki-wallet fs-3 text-primary me-2"></i>
                                                    <span class="text-gray-700 fw-bold fs-7">Nurse Earnings</span>
                                                </div>
                                                <span class="fw-bolder fs-3 text-gray-900">₹{{ number_format($bid->nurse_amount ?? 0, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="bg-white rounded p-4 border border-primary border-dashed ">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ki-outline ki-bank fs-3 text-primary me-2"></i>
                                                    <span class="text-gray-700 fw-bold fs-7">Platform Commission</span>
                                                </div>
                                                <span class="fw-bolder fs-3 text-gray-900">₹{{ number_format($bid->commission_amount ?? 0, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="bg-white rounded p-4 border border-gray-400">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ki-outline ki-bill fs-3 text-gray-900 me-2"></i>
                                                    <span class="text-gray-900 fw-bold fs-7">Total (Patient Pays)</span>
                                                </div>
                                                <span class="fw-bolder fs-2 text-gray-900">₹{{ number_format($bid->total_amount ?? 0, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Specialities & Notes --}}
                        <div class="col-xl-6">
                            <div class="card shadow-sm mb-5 border border-primary h-100">
                                <div class="card-header border-bottom border-gray-200 pt-4 pb-3 min-h-50px">
                                    <h3 class="card-title fw-bold fs-5 text-gray-900 mb-0">Nurse Details & Notes</h3>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <div class="mb-5">
                                        <span class="text-gray-500 text-uppercase fw-bold fs-9 d-block mb-3">Nurse Specialities (Care Types)</span>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if(isset($bid->nurse->careTypes) && $bid->nurse->careTypes->count() > 0)
                                                @foreach($bid->nurse->careTypes as $spec)
                                                    <span class="badge badge-light text-gray-700 border border-gray-200 fs-8 px-3 py-2">{{ $spec->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-gray-600 fs-7">No specialities listed.</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($bid->notes)
                                    <div class="separator separator-dashed border-gray-200 my-4"></div>
                                    <div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="ki-outline ki-message-text-2 fs-4 text-primary me-2"></i>
                                            <span class="text-gray-900 fw-bold fs-7">Bid Notes from Nurse</span>
                                        </div>
                                        <p class="text-gray-700 fs-7 bg-white rounded p-3 border border-gray-200">
                                            {{ $bid->notes }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- ── COMMENTS SECTION ───────────────────────────────────────────────── --}}
        <div class="mt-8">
            <div class="d-flex align-items-center mb-5">
                <i class="ki-outline ki-message-text-2 fs-2 text-gray-900 me-2"></i>
                <h3 class="fw-bold text-gray-900 fs-4 mb-0">Discussion / Comments</h3>
            </div>
            <div class="card shadow-sm border border-gray-200">
                <div class="card-body">
                    <x-comments type="{{ \App\Models\Comment::TYPE_REQUEST_BID }}" :model-id="$bid->id" />
                </div>
            </div>
        </div>

    </div>

@endsection
