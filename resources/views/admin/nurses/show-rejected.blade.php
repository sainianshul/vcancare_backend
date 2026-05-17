@extends('admin.layouts.app')

@section('title', 'Nurse Profile - Rejected')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Nurses', 'url' => route('admin.nurses.index')],
        ['label' => 'Rejected Profile'],
    ]" />

    <div class="card shadow-none border border-gray-300 mb-5 mb-xl-8">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1 text-gray-800">Application Rejected</span>
                <span class="text-gray-500 mt-1 fw-semibold fs-7">This nurse's application was declined.</span>
            </h3>
            <div class="card-toolbar">
                <button class="btn btn-sm btn-light-primary border border-primary fw-bold px-4 py-2 me-2">
                    <i class="ki-outline ki-sms fs-5 me-1"></i> Send SMS
                </button>
                <button class="btn btn-sm btn-light-info border border-info fw-bold px-4 py-2">
                    <i class="ki-outline ki-sms fs-5 me-1"></i> Send Email
                </button>
            </div>
        </div>
        <div class="card-body pt-8 pb-8">

            <div class="row g-5">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-6">
                        <div class="symbol symbol-50px me-4">
                            <span class="symbol-label bg-light border border-gray-300 text-gray-800 fw-bold fs-4">
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold fs-5">{{ $user->name }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">ID: #{{ $user->id }}</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex justify-content-between border-bottom border-gray-200 pb-3">
                            <span class="text-gray-500 fw-semibold fs-7">Email</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $user->email }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom border-gray-200 pb-3">
                            <span class="text-gray-500 fw-semibold fs-7">Phone</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $user->phone }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 border-start border-gray-200 ps-md-8">
                    <div class="d-flex flex-column justify-content-center h-100">
                        <span class="text-gray-800 fw-bold fs-6 mb-3">Rejection Details</span>
                        
                        <div class="d-flex bg-light-danger border border-danger border-dashed rounded p-5">
                            <i class="ki-outline ki-information-5 fs-2x text-danger me-4 mt-1"></i>
                            <div class="d-flex flex-column">
                                <span class="text-danger fw-bold fs-5 mb-2">Reason for rejection:</span>
                                <span class="text-gray-800 fw-semibold fs-7">
                                    {{ $profile->rejection_reason ?: 'No specific reason provided by the administrator.' }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-comments type="{{ \App\Models\Comment::TYPE_NURSE }}" :model-id="$user->id" />

@endsection
