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

                $viewUrl = route('admin.system.errors.show', $error->id);

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
                    1 => ['Low', 'info', 'ki-information-5'],
                    2 => ['Medium', 'warning', 'ki-warning'],
                    3 => ['High', 'danger', 'ki-shield-cross'],
                    4 => ['Critical', 'dark', 'ki-cross-circle'],
                ];

                $item = $map[$error->severity] ?? ['Unknown', 'secondary', 'ki-question'];

                return '
                    <span class="badge badge-light-' . $item[1] . ' border border-' . $item[1] . ' fw-bold px-3 py-2">
                        <i class="ki-outline ' . $item[2] . ' fs-6 text-' . $item[1] . ' me-1"></i>
                        ' . e($item[0]) . '
                    </span>
                ';
            })

            // ── Status ──────────────────────────────────────────────
            ->addColumn('status', function (ApplicationError $error) {

                $map = [
                    0 => ['Pending', 'danger', 'ki-time'],
                    1 => ['Opened', 'warning', 'ki-eye'],
                    2 => ['Resolved', 'success', 'ki-check-circle'],
                ];

                $item = $map[$error->status] ?? ['Unknown', 'secondary', 'ki-question'];

                return '
                    <span class="badge badge-light-' . $item[1] . ' border border-' . $item[1] . ' fw-bold px-3 py-2">
                        <i class="ki-outline ' . $item[2] . ' fs-6 text-' . $item[1] . ' me-1"></i>
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

                $viewUrl = route('admin.system.errors.show', $error->id);
                $statusBtn = '';
                if ($error->status == ApplicationError::STATUS_RESOLVED) {
                    $statusBtn = '
                        <button type="button" class="btn btn-sm btn-icon btn-light-warning border border-warning w-30px h-30px btn-status me-1"
                            data-id="' . $error->id . '" data-status="1" title="Re-open">
                            <i class="ki-outline ki-arrows-circle fs-5"></i>
                        </button>';
                } else {
                    $statusBtn = '
                        <button type="button" class="btn btn-sm btn-icon btn-light-success border border-success w-30px h-30px btn-status me-1"
                            data-id="' . $error->id . '" data-status="2" title="Mark Resolved">
                            <i class="ki-outline ki-check fs-5"></i>
                        </button>';
                }

                return '
                    <div class="d-flex gap-1 justify-content-end">
                        <a href="' . $viewUrl . '"
                            class="btn btn-sm btn-icon btn-light-primary border border-primary w-30px h-30px"
                            title="View">
                            <i class="ki-outline ki-eye fs-5"></i>
                        </a>
                        ' . $statusBtn . '
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