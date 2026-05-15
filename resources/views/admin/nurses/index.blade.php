@extends('admin.layouts.app')

@section('title', 'Nurses')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-4 mb-8">

        {{-- Title + Breadcrumb --}}
        <div>
            <h1 class="text-gray-900 fw-bold fs-2 mb-1">@yield('title')</h1>
            <x-breadcrumb :items="[
            ['label' => 'Nurses', 'url' => route('admin.nurses.index')],
            ['label' => 'All Nurses'],
        ]" />
        </div>
    </div>

    {{-- Main Content --}}
    @yield('page-content')

@endsection