<!--begin::Javascript-->
<script>var hostUrl = "{{ asset('theme/dist/assets') }}/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('theme/dist/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('theme/dist/assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->

<!--begin::Custom Sidebar (lightweight, replaces KTMenu/KTDrawer)-->
<script src="{{ asset('js/admin-sidebar.js') }}?v=1"></script>
<!--end::Custom Sidebar-->

{{-- DataTables bundle — sirf table pages pe load hoga --}}
@stack('datatables_js')

{{-- Page-specific scripts --}}
@stack('scripts')
<!--end::Javascript-->