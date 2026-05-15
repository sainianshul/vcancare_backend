<?php

namespace App\DataTables\System;

use App\Models\ApplicationError;
use Illuminate\Support\Str;
use Yajra\DataTables\Services\DataTable;

class ErrorLogsDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)

            // ── Error ───────────────────────────────────────────────
            ->addColumn('error', function (ApplicationError $error) {

                $viewUrl = route('admin.system.error-logs');

                return '
                    <div class="d-flex flex-column">

                        <a href="' . $viewUrl . '"
                           class="text-primary fw-bold fs-6 mb-1 text-hover-primary">

                            #' . e($error->error_id) . '

                        </a>

                        <span class="text-muted fw-normal fs-7">
                            ' . e(Str::limit($error->message, 90)) . '
                        </span>

                    </div>
                ';
            })

            // ── Search Only By Error ID ────────────────────────────
            ->filterColumn('error', function ($query, $keyword) {

                $query->where('error_id', 'LIKE', "%{$keyword}%");
            })

            // ── Severity ────────────────────────────────────────────
            ->addColumn('severity', function (ApplicationError $error) {

                $map = [
                    1 => ['Low', 'primary'],
                    2 => ['Medium', 'warning'],
                    3 => ['High', 'danger'],
                    4 => ['Critical', 'dark'],
                ];

                $item = $map[$error->severity] ?? ['Unknown', 'secondary'];

                return '
                    <span class="badge badge-light-' . $item[1] . ' fw-bold px-3 py-2">
                        ' . e($item[0]) . '
                    </span>
                ';
            })

            // ── Status ──────────────────────────────────────────────
            ->addColumn('status', function (ApplicationError $error) {

                $map = [
                    0 => ['Open', 'danger'],
                    1 => ['Resolved', 'success'],
                    2 => ['Ignored', 'secondary'],
                ];

                $item = $map[$error->status] ?? ['Unknown', 'secondary'];

                return '
                    <span class="badge badge-light-' . $item[1] . ' fw-bold px-3 py-2">
                        ' . e($item[0]) . '
                    </span>
                ';
            })

            // ── Request ─────────────────────────────────────────────
            ->addColumn('request', function (ApplicationError $error) {

                return '
                    <div class="fw-semibold text-gray-800">
                        ' . e($error->method ?? 'GET') . '
                    </div>

                    <div class="text-muted fs-7">
                        ' . e(Str::limit($error->url, 45)) . '
                    </div>
                ';
            })

            // ── Created ─────────────────────────────────────────────
            ->editColumn('created_at', function (ApplicationError $error) {

                return '
                    <div class="fw-semibold text-gray-800">
                        ' . $error->created_at->format('d M Y') . '
                    </div>

                    <div class="text-muted fs-7">
                        ' . $error->created_at->diffForHumans() . '
                    </div>
                ';
            })

            // ── Actions ─────────────────────────────────────────────
            ->addColumn('actions', function (ApplicationError $error) {

                $viewUrl = route('admin.system.error-logs');

                return '
                    <div class="d-flex gap-1 justify-content-end">

                        <a href="' . $viewUrl . '"
                            class="btn btn-sm btn-icon btn-light-primary w-30px h-30px"
                            title="View">

                            <i class="ki-duotone ki-eye fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-sm btn-icon btn-light-success w-30px h-30px btn-resolve"
                            data-id="' . $error->id . '"
                            title="Resolve">

                            <i class="ki-duotone ki-check fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-icon btn-light-danger w-30px h-30px btn-delete"
                            data-id="' . $error->id . '"
                            title="Delete">

                            <i class="ki-duotone ki-trash fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </button>

                    </div>
                ';
            })

            ->rawColumns([
                'error',
                'severity',
                'status',
                'request',
                'created_at',
                'actions',
            ]);
    }

    public function query(ApplicationError $model)
    {
        $query = $model->newQuery()
            ->select('application_errors.*');

        // ── Status Filter ──────────────────────────────────────────
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // ── Severity Filter ────────────────────────────────────────
        if (request()->filled('severity')) {
            $query->where('severity', request('severity'));
        }

        return $query;
    }

    public function filename(): string
    {
        return 'Application_Errors_' . date('Y_m_d_His');
    }
}