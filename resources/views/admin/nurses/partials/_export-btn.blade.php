{{-- resources/views/admin/nurses/partials/_export-btn.blade.php --}}
<div class="dropdown">
    <button class="btn btn-sm btn-light fw-semibold d-flex align-items-center border border-gray-300 shadow-sm px-4"
        type="button" data-bs-toggle="dropdown">
        <i class="ki-duotone ki-exit-up fs-5 text-gray-700 me-2">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <span class="text-gray-800 fw-semibold fs-7">Export</span>
        <i class="ki-duotone ki-down fs-6 text-gray-500 ms-3"><span class="path1"></span></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded-3
               menu-gray-700 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-3
               border border-gray-200 shadow-sm">
        <li class="menu-item px-3">
            <a class="menu-link px-3 py-2 rounded-3 d-flex align-items-center gap-3" href="#" id="export-excel">
                <i class="ki-duotone ki-file-sheet fs-4 text-success"><span class="path1"></span><span
                        class="path2"></span></i>
                <span class="fw-semibold">Export Excel</span>
            </a>
        </li>
        <li class="menu-item px-3">
            <a class="menu-link px-3 py-2 rounded-3 d-flex align-items-center gap-3" href="#" id="export-csv">
                <i class="ki-duotone ki-file-down fs-4 text-primary"><span class="path1"></span><span
                        class="path2"></span></i>
                <span class="fw-semibold">Export CSV</span>
            </a>
        </li>
        <li class="menu-item px-3">
            <a class="menu-link px-3 py-2 rounded-3 d-flex align-items-center gap-3" href="#" id="export-pdf">
                <i class="ki-duotone ki-file-pdf fs-4 text-danger"><span class="path1"></span><span
                        class="path2"></span></i>
                <span class="fw-semibold">Export PDF</span>
            </a>
        </li>
    </ul>
</div>