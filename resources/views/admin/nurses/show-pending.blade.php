@extends('admin.layouts.app')

@section('title', 'Nurse Profile - Pending Onboarding')

@section('content')

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <x-page-header title="Pending Profile" description="Nurse onboarding is currently incomplete" />
                <x-breadcrumb :items="[
                    ['label' => 'People'],
                    ['label' => 'Nurses', 'url' => route('admin.nurses.index')],
                    ['label' => $user->name],
                ]" />
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.nurses.index') }}" class="btn btn-sm btn-light fw-semibold">
                    <i class="ki-outline ki-arrow-left fs-4 me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!--begin::Navbar-->
            <div class="card card-bordered border-gray-300 mb-5 mb-xl-10 shadow-none">
                <div class="card-body pt-9 pb-9">
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        
                        <!--begin: Pic-->
                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-circle position-relative shadow-sm" style="border: 4px solid #fff;">
                                @if($user->profile_photo)
                                    <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}" class="object-fit-cover" />
                                @else
                                    <span class="symbol-label bg-light-warning border border-warning fs-2x fw-bold text-warning">
                                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </span>
                                @endif
                                <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-warning rounded-circle border border-4 border-body h-20px w-20px"></div>
                            </div>
                        </div>
                        <!--end::Pic-->

                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <h1 class="text-gray-900 fs-1 fw-bold me-2">{{ $user->name }}</h1>
                                            <span class="badge badge-light-warning border border-warning fw-semibold px-3 py-1 me-2">
                                                <i class="ki-outline ki-time fs-7 text-warning me-1"></i> Pending Onboarding
                                            </span>

                                        </div>

                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-light-primary border border-primary fw-bold px-4 py-2 shadow-sm">
                                                <i class="ki-outline ki-sms fs-5 me-1"></i> Send SMS
                                            </button>
                                            <button class="btn btn-sm btn-light-info border border-info fw-bold px-4 py-2 shadow-sm">
                                                <i class="ki-outline ki-sms fs-5 me-1"></i> Send Email
                                            </button>
                                            <a href="{{ route('admin.nurses.edit', $user->id) }}" class="btn btn-sm btn-light-warning border border-warning fw-bold px-4 py-2 shadow-sm">
                                                <i class="ki-outline ki-pencil fs-5 me-1"></i> Edit
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Upar Niche Data Layout -->
                                    <div class="d-flex flex-column gap-3 mt-4">
                                        <div class="d-flex align-items-center gap-6">
                                            <span class="d-flex align-items-center text-gray-900 fw-semibold fs-7">
                                                <i class="ki-outline ki-phone fs-5 me-2 text-primary"></i>
                                                {{ $user->phone ?: '—' }}
                                            </span>
                                            <span class="d-flex align-items-center text-gray-900 fw-semibold fs-7">
                                                <i class="ki-outline ki-sms fs-5 me-2 text-primary"></i>
                                                {{ $user->email }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-4 mt-2">
                                            <span class="d-flex align-items-center text-gray-900 border border-gray-300 border-dashed rounded px-3 py-1 bg-light fs-8">
                                                <i class="ki-outline ki-calendar fs-6 me-2 text-primary"></i>
                                                <span class="fw-semibold">Joined:&nbsp;</span> 
                                                <span class="text-gray-900 fw-bold">{{ $user->created_at->format('d M Y') }}</span>
                                            </span>
                                            <span class="d-flex align-items-center text-gray-900 border border-gray-300 border-dashed rounded px-3 py-1 bg-light fs-8">
                                                <i class="ki-outline ki-fingerprint-scan fs-6 me-2 text-primary"></i>
                                                <span class="fw-semibold">Last Login:&nbsp;</span> 
                                                <span class="text-gray-900 fw-bold">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
            </div>
            <!--end::Navbar-->

            <!--begin::Onboarding Progress-->
            <div class="card card-bordered border-gray-300 shadow-none">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1 text-gray-900">Application Progress</span>
                        <span class="text-gray-800 mt-1 fw-bold fs-7">This nurse is currently completing their onboarding steps.</span>
                    </h3>
                </div>
                <div class="card-body pt-8 pb-10">
                    
                    @php
                        $steps = [
                            1 => ['name' => 'Basic Profile', 'icon' => 'ki-user'],
                            2 => ['name' => 'Care Types', 'icon' => 'ki-heart'],
                            3 => ['name' => 'Education', 'icon' => 'ki-book'],
                            4 => ['name' => 'Work History', 'icon' => 'ki-briefcase'],
                            5 => ['name' => 'Documents', 'icon' => 'ki-document'],
                            6 => ['name' => 'Submit', 'icon' => 'ki-send']
                        ];
                        
                        $currentStep = $profile->onboarding_step ?: 1;
                        $isCompleted = $profile->is_onboarding_completed;
                    @endphp

                    <!-- Custom Horizontal Stepper -->
                    <div class="py-10">
                        <div class="d-flex justify-content-between w-100 position-relative">
                            
                            <!-- Background line -->
                            <div class="position-absolute border-bottom border-2 border-primary opacity-25" style="top: 20px; left: 50px; right: 50px; z-index: 0;"></div>

                            @foreach($steps as $stepId => $stepData)
                                @php
                                    $isPast = $isCompleted || $stepId < $currentStep;
                                    $isCurrent = !$isCompleted && $stepId == $currentStep;
                                    
                                    if ($isPast) {
                                        // Completed: Light purple with purple border and icon
                                        $bgClass = 'bg-light-primary border-primary';
                                        $iconClass = 'text-primary';
                                        $labelState = 'text-primary fw-bold';
                                        $icon = 'ki-check'; // Show checkmark for completed
                                    } elseif ($isCurrent) {
                                        // Current: Solid purple border, light purple bg
                                        $bgClass = 'bg-light-primary border-primary';
                                        $iconClass = 'text-primary';
                                        $labelState = 'text-gray-900 fw-bold';
                                        $icon = $stepData['icon'];
                                    } else {
                                        // Pending: White bg, dashed gray border
                                        $bgClass = 'bg-white border-gray-300 border-dashed';
                                        $iconClass = 'text-gray-400';
                                        $labelState = 'text-gray-600 fw-bold';
                                        $icon = $stepData['icon'];
                                    }
                                @endphp

                                <div class="d-flex flex-column align-items-center position-relative z-index-1" style="width: 100px;">
                                    <div class="w-40px h-40px d-flex align-items-center justify-content-center rounded-circle border border-2 {{ $bgClass }} mb-3 shadow-sm">
                                        <i class="ki-outline {{ $icon }} fs-3 {{ $iconClass }}"></i>
                                    </div>
                                    <span class="fs-7 text-center {{ $labelState }}">{{ $stepData['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex align-items-center border border-warning rounded p-5 mt-4 bg-light-warning">
                        <i class="ki-outline ki-information-5 fs-2x text-warning me-4"></i>
                        <div class="d-flex flex-column">
                            <span class="text-gray-900 fw-bold fs-6 mb-1">Waiting for User Action</span>
                            <span class="text-gray-800 fw-bold fs-7">
                                You cannot review or approve this profile until the nurse completes all steps and officially submits their application for review.
                            </span>
                        </div>
                    </div>

                </div>
            </div>
            <!--end::Onboarding Progress-->

            <x-comments type="{{ \App\Models\Comment::TYPE_NURSE }}" :model-id="$user->id" />

        </div>
    </div>
    <!--end::Content-->

@endsection
