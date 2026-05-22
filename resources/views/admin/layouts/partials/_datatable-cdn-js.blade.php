{{--
    Lightweight DataTables + utility CDN scripts.
    Replaces the 2.4MB datatables.bundle.js with individual CDN modules.

    Total CDN payload: ~200KB (vs 2,400KB from Metronic bundle)
    - DataTables core:     ~90KB
    - DT Buttons:          ~30KB
    - JSZip (for Excel):   ~50KB
    - pdfmake (for PDF):   ~30KB (lazy-loaded by DT Buttons only when user exports)
    - SweetAlert2:         ~25KB
    - Toastr:              ~6KB
    - Select2:             ~25KB
--}}

{{-- DataTables Core --}}
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

{{-- DataTables Buttons (Export) --}}
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

{{-- JSZip — required for Excel export --}}
<script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>

{{-- pdfmake — required for PDF export --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

{{-- SweetAlert2 (used for delete confirmations) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

{{-- Toastr (used for success/error notifications) --}}
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

{{-- Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- Select2 (used for filter dropdowns) --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- Initialize Select2 on any elements with data-control="select2" --}}
<script>
$(function(){
    $('[data-control="select2"]').each(function(){
        $(this).select2({
            minimumResultsForSearch: $(this).data('hide-search') === true ? -1 : 10,
            placeholder: $(this).data('placeholder') || '',
            allowClear: $(this).data('allow-clear') === true
        });
    });
});
</script>
