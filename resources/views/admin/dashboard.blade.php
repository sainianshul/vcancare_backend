@extends('admin.layouts.app')

@section('content')

    <div id="pending-errors-container"></div>
    <div id="pending-nurses-container"></div>

    <div class="card">
        <div class="card-body">

            <h1 class="mb-5">
                Dashboard
            </h1>

            <p>
                Welcome to admin dashboard.
            </p>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(function() {
        $.get('{{ route("admin.system.errors.pending-count") }}').done(function(res) {
            if (res.count > 0) {
                $('#pending-errors-container').html(`
                    <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex align-items-center flex-sm-row flex-column w-100 p-4 mb-7 shadow-sm">
                        <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4 mb-sm-0 mb-4"></i>
                        <div class="d-flex flex-column pe-0 pe-sm-10 text-center text-sm-start">
                            <h6 class="mb-1 text-danger fw-bold">Application Errors Detected</h6>
                            <span class="text-gray-800 fw-medium fs-7">Application has ${res.count} pending error${res.count > 1 ? 's' : ''} that require developer attention.</span>
                        </div>
                        <div class="ms-sm-auto mt-sm-0 mt-4">
                            <a href="{{ route('admin.system.error-logs') }}" class="btn btn-sm btn-danger fw-bold px-4 py-2">View Errors</a>
                        </div>
                    </div>
                `);
            }
        });

        $.get('{{ route("admin.nurses.pending-count") }}').done(function(res) {
            if (res.count > 0) {
                $('#pending-nurses-container').html(`
                    <div class="alert alert-dismissible bg-light-info border border-info border-dashed d-flex align-items-center flex-sm-row flex-column w-100 p-4 mb-7 shadow-sm">
                        <i class="ki-outline ki-profile-user fs-2hx text-info me-4 mb-sm-0 mb-4"></i>
                        <div class="d-flex flex-column pe-0 pe-sm-10 text-center text-sm-start">
                            <h6 class="mb-1 text-info fw-bold">Nurse Approvals Required</h6>
                            <span class="text-gray-800 fw-medium fs-7">${res.count} nurse profile${res.count > 1 ? 's' : ''} need${res.count === 1 ? 's' : ''} your approval to proceed.</span>
                        </div>
                        <div class="ms-sm-auto mt-sm-0 mt-4">
                            <a href="{{ route('admin.nurses.pending_approval') }}" class="btn btn-sm btn-info fw-bold px-4 py-2">View Nurses</a>
                        </div>
                    </div>
                `);
            }
        });
    });
</script>
@endpush