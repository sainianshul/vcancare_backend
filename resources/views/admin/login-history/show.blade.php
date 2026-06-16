@extends('admin.layouts.app')

@section('title', 'Login Details')

@section('content')

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Login Details" description="View details of this login session" />
                <x-breadcrumb :items="[
                    ['label' => 'System', 'url' => ''],
                    ['label' => 'Login History', 'url' => route('admin.login-history.index')],
                    ['label' => 'Details'],
                ]" />
            </div>
            
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-light fw-bold fs-7">
                    <i class="ki-outline ki-arrow-left fs-5 me-1"></i>Back
                </a>
            </div>
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <div class="row g-5 g-xl-8 mb-5 mb-xl-8">

        {{-- Main Column --}}
        <div class="col-xl-8">
            
            {{-- IP Location Overview --}}
            <div class="card shadow-sm border border-gray-200 mb-5 mb-xl-8">
                <div class="card-header border-bottom border-gray-200 pt-5 pb-4">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-6 mb-1 text-gray-800">IP Intelligence</span>
                        <span class="text-gray-500 mt-1 text-uppercase fw-bold fs-9">Geographic & network routing data</span>
                    </h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary fw-bold px-3 py-2 fs-8">
                            <i class="ki-outline ki-geolocation fs-7 text-primary me-1"></i>
                            {{ $loginHistory->ip_address }}
                        </span>
                    </div>
                </div>
                <div class="card-body pt-4 pb-4">
                    
                    @if($ipLocation && $ipLocation->isSuccess())
                        <div class="d-flex flex-wrap gap-5">
                            
                            {{-- Huge side icon for visual appeal as requested --}}
                            <div class="d-flex align-items-center justify-content-center bg-white border border-primary border-dashed rounded" style="width: 120px; height: 120px; border: 1px dashed var(--bs-primary);">
                                <i class="ki-outline ki-map fs-5x text-primary opacity-75"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="row g-0">
                                    {{-- Left Stats --}}
                                    <div class="col-sm-6 pe-sm-5">
                                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-200">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Country</span>
                                            <span class="text-gray-800 fw-bold fs-7">{{ $ipLocation->getCountry() ?: '—' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-200">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Region</span>
                                            <span class="text-gray-800 fw-bold fs-7">{{ $ipLocation->getRegion() ?: '—' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-3">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">City</span>
                                            <span class="text-gray-800 fw-bold fs-7">{{ $ipLocation->getCity() ?: '—' }}</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Right Stats --}}
                                    <div class="col-sm-6 ps-sm-5 border-sm-start border-gray-200">
                                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-200">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">ISP</span>
                                            <span class="text-gray-800 fw-bold fs-7">{{ $ipLocation->getIsp() ?: '—' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-200">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Zip Code</span>
                                            <span class="text-gray-800 fw-bold fs-7">{{ $ipLocation->getZip() ?: '—' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-3">
                                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Coordinates</span>
                                            <span class="text-gray-800 fw-bold fs-7">{{ $ipLocation->getLat() ?: '—' }}, {{ $ipLocation->getLon() ?: '—' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @else
                        <!-- Fallback when API fails or is local IP -->
                        <div class="d-flex align-items-center bg-light-warning border border-warning border-dashed rounded p-5">
                            <i class="ki-outline ki-information-5 fs-1 text-warning me-4"></i>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold fs-6 mb-1">Data Unavailable</span>
                                <span class="text-gray-600 fw-medium fs-8">Location routing details could not be determined for this IP. It may be a local or reserved address.</span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>



        </div>

        {{-- Side Column --}}
        <div class="col-xl-4">
            
            {{-- User Profile --}}
            <div class="card shadow-sm border border-gray-200 mb-5 mb-xl-8">
                <div class="card-header border-bottom border-gray-200 pt-5 pb-4">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-6 mb-1 text-gray-800">Account Identity</span>
                    </h3>
                </div>
                <div class="card-body pt-2 pb-6">
                    
                    @if($loginHistory->user)
                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-40px me-3">
                                <span class="symbol-label bg-light-primary text-primary fw-bold fs-5 border border-primary border-dashed">
                                    {{ mb_strtoupper(mb_substr($loginHistory->user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold fs-6">{{ $loginHistory->user->name }}</span>
                                <span class="text-gray-500 fw-semibold fs-8">ID: #{{ $loginHistory->user->id }}</span>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Privilege</span>
                                @if((int)$loginHistory->user->role === \App\Models\User::ROLE_ADMIN)
                                    <span class="badge badge-light-danger fw-bold px-2 py-1 fs-8">Admin</span>
                                @elseif((int)$loginHistory->user->role === \App\Models\User::ROLE_USER)
                                    <span class="badge badge-light-primary fw-bold px-2 py-1 fs-8">Patient</span>
                                @elseif((int)$loginHistory->user->role === \App\Models\User::ROLE_NURSE)
                                    <span class="badge badge-light-success fw-bold px-2 py-1 fs-8">Nurse</span>
                                @else
                                    <span class="badge badge-light-secondary fw-bold px-2 py-1 fs-8">Guest</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Email</span>
                                <span class="text-gray-800 fw-bold fs-7">{{ $loginHistory->user->email ?: '—' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-gray-500 text-uppercase fw-bold fs-9">Phone</span>
                                <span class="text-gray-800 fw-bold fs-7">{{ $loginHistory->user->phone ?: '—' }}</span>
                            </div>
                        </div>

                        @if((int)$loginHistory->user->role === \App\Models\User::ROLE_USER)
                            <a href="{{ route('admin.patients.show', $loginHistory->user->id) }}" class="btn btn-light-primary border border-primary btn-sm w-100 fw-bold fs-8 px-3 py-2 mt-4">
                                View Full Profile <i class="ki-outline ki-arrow-right fs-7 ms-2"></i>
                            </a>
                        @elseif((int)$loginHistory->user->role === \App\Models\User::ROLE_NURSE)
                            <a href="{{ route('admin.nurses.show', $loginHistory->user->id) }}" class="btn btn-light-success border border-success btn-sm w-100 fw-bold fs-8 px-3 py-2 mt-4">
                                View Full Profile <i class="ki-outline ki-arrow-right fs-7 ms-2"></i>
                            </a>
                        @endif
                    @else
                        <div class="d-flex flex-column align-items-center text-center py-4">
                            <i class="ki-outline ki-user-cross fs-3x text-gray-400 mb-2"></i>
                            <span class="text-gray-800 fw-bold fs-6">Unresolved Entity</span>
                            <span class="text-gray-500 fw-semibold fs-8 mt-1">This user account is untraceable or deleted.</span>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Session Result --}}
            <div class="card shadow-sm border border-gray-200">
                <div class="card-body p-6">
                    
                    <div class="d-flex align-items-center mb-5">
                        <div class="d-flex align-items-center justify-content-center bg-light w-40px h-40px rounded border border-gray-200 me-3">
                            <i class="ki-outline ki-security-user fs-4 text-gray-600"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold fs-6">Authentication</span>
                            <span class="text-gray-500 fw-semibold fs-8">Session outcome</span>
                        </div>
                        <div class="ms-auto">
                            @if((int)$loginHistory->status === 1)
                                <span class="badge badge-light-success border border-success fw-bold px-2 py-1 fs-8">Authorized</span>
                            @else
                                <span class="badge badge-light-danger border border-danger fw-bold px-2 py-1 fs-8">Denied</span>
                            @endif
                        </div>
                    </div>

                    <div class="separator separator-dashed border-gray-200 mb-5"></div>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Timestamp</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $loginHistory->logged_in_at ? $loginHistory->logged_in_at->format('d M, Y') : '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-gray-500 text-uppercase fw-bold fs-9">Clock</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $loginHistory->logged_in_at ? $loginHistory->logged_in_at->format('H:i:s') : '—' }}</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

        </div>
    </div>

    <x-comments type="{{ \App\Models\Comment::TYPE_LOGIN_HISTORY }}" :model-id="$loginHistory->id" />

@endsection
