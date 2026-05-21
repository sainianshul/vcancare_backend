@extends('admin.layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="card shadow-sm border border-gray-300">
    <div class="card-header border-0 pt-6 bg-light-primary">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" data-kt-support-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search tickets..." />
            </div>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <div class="w-100 mw-150px">
                <select class="form-select form-select-solid form-select-sm" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-support-table-filter="status">
                    <option value="all">All</option>
                    <option value="0">Open</option>
                    <option value="1">In Progress</option>
                    <option value="2">Resolved</option>
                    <option value="3">Closed</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_support_table">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Reference ID</th>
                    <th class="min-w-200px">User</th>
                    <th class="min-w-150px">Subject</th>
                    <th class="min-w-100px">Priority</th>
                    <th class="min-w-100px">Status</th>
                    <th class="min-w-125px">Date</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    "use strict";

    var KTSupportList = function () {
        var table;
        var datatable;

        var initDatatable = function () {
            datatable = $(table).DataTable({
                searchDelay: 500,
                processing: true,
                serverSide: true,
                order: [[5, 'desc']],
                ajax: {
                    url: "{{ route('admin.support.data') }}",
                    data: function(d) {
                        d.status = $('[data-kt-support-table-filter="status"]').val();
                        if (d.status === 'all') {
                            d.status = '';
                        }
                    }
                },
                columns: [
                    { data: 'reference_id', name: 'reference_id' },
                    { data: 'user', name: 'user.name' },
                    { data: 'subject', name: 'subject' },
                    { data: 'priority', name: 'priority' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                ]
            });

            datatable.on('draw', function () {
                KTMenu.createInstances();
            });
        }

        var handleSearchDatatable = function () {
            const filterSearch = document.querySelector('[data-kt-support-table-filter="search"]');
            filterSearch.addEventListener('keyup', function (e) {
                datatable.search(e.target.value).draw();
            });
        }

        var handleStatusFilter = () => {
            const filterStatus = document.querySelector('[data-kt-support-table-filter="status"]');
            $(filterStatus).on('change', e => {
                datatable.draw();
            });
        }

        return {
            init: function () {
                table = document.querySelector('#kt_support_table');

                if (!table) {
                    return;
                }

                initDatatable();
                handleSearchDatatable();
                handleStatusFilter();
            }
        };
    }();

    KTUtil.onDOMContentLoaded(function () {
        KTSupportList.init();
    });
</script>
@endpush
