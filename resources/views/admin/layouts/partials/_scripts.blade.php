<!--begin::Javascript-->
<script>var hostUrl = "{{ asset('theme/dist/assets') }}/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('theme/dist/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('theme/dist/assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->
{{-- Page-specific scripts --}}
@stack('scripts')
<!--end::Javascript-->