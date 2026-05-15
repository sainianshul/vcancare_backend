{{--
    Lightweight DataTables + utility CDN scripts.
    Replaces the 2.4MB datatables.bundle.js with individual CDN modules (~200KB total).
    Also includes SweetAlert2, Toastr, and Select2 used on table pages.

    Usage: Replace the datatables_css/datatables_js push blocks in each page with:
        @push('datatables_css')
            @include('admin.layouts.partials._datatable-cdn')
        @endpush
    Or use directly in both stacks.
--}}

{{-- DataTables Core CSS --}}
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
{{-- DataTables Buttons CSS --}}
<link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css" rel="stylesheet" />
{{-- SweetAlert2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
{{-- Toastr CSS --}}
<link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet" />
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
