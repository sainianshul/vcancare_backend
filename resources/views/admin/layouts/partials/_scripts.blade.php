<!--begin::Javascript-->
<script>var hostUrl = "{{ asset('theme/dist/assets') }}/";</script>

<!--
    PERFORMANCE: Replaced the 2.4MB plugins.bundle.js with CDN-hosted
    jQuery (87KB) + Bootstrap (72KB) = ~160KB total (vs 2,400KB).
    CDN versions are likely already cached in the user's browser.
    All  CSS classes still work — only the heavy KT* JS plugins
    and unused libraries (Flatpickr, FormValidation, es6-shim) are dropped.
-->

<!--begin::jQuery-->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<!--end::jQuery-->

<!--begin::Bootstrap 5.3 Bundle (includes Popper.js)-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!--end::Bootstrap 5.3 Bundle-->

<!--begin::SweetAlert2-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--end::SweetAlert2-->

<!--begin::Custom Sidebar (lightweight, zero-dependency ~3KB)-->
<script src="{{ asset('js/admin-sidebar.js') }}?v=8"></script>
<!--end::Custom Sidebar-->

{{-- DataTables bundle — only on table pages --}}
@stack('datatables_js')

{{-- Page-specific scripts --}}
@stack('scripts')
<!--end::Javascript-->