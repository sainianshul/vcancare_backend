<?php

namespace App\DataTables\Request;

use App\Models\CareRequest;
use Yajra\DataTables\Services\DataTable;

class RequestDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            
            // ── Request ID ─────────────────────────────────────────────
            ->editColumn('reference_id', function (CareRequest $request) {
                return '<span class="fw-bold text-gray-800 text-nowrap">' . e($request->reference_id) . '</span>';
            })
            
            // ── User Info ─────────────────────────────────────────────
            ->filterColumn('user', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%")
                      ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('user', function (CareRequest $request) {
                $user = $request->user;
                if (!$user) return '<span class="text-muted">Unknown</span>';
                
                $avatar = '<div class="symbol symbol-38px symbol-circle">' . $user->avatar_html . '</div>';

                return '
                    <div class="d-flex align-items-center gap-3">
                        ' . $avatar . '
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-semibold fs-6 lh-1 mb-1">
                                ' . e($user->name) . '
                            </span>
                            <span class="text-muted fw-normal fs-7">
                                ID: ' . e($user->id) . '
                            </span>
                        </div>
                    </div>
                ';
            })

            // ── Status ───────────────────────────────────────────────
            ->addColumn('status', function (CareRequest $request) {
                $color = $request->status_color;
                
                return '
                    <span class="badge badge-light-' . $color . ' border border-' . $color . ' fw-bold px-3 py-2">
                        ' . e($request->status_text) . '
                    </span>
                ';
            })
            
            // ── Date & Time ───────────────────────────────────────────
            ->addColumn('date_time', function (CareRequest $request) {
                $startDate = $request->start_date ? $request->start_date->format('d M Y') : '—';
                $startTime = $request->start_time ?? '—';
                return '
                    <div class="fw-semibold text-gray-800">' . $startDate . '</div>
                    <div class="text-muted fs-7">' . $startTime . '</div>
                ';
            })
            
            // ── Location ───────────────────────────────────────────────
            ->addColumn('location', function (CareRequest $request) {
                return '
                    <div class="text-gray-800 text-truncate" style="max-width: 200px;" title="' . e($request->address) . '">
                        ' . e($request->address ?? '—') . '
                    </div>
                    <div class="text-muted fs-7">
                        ' . e($request->city . ', ' . $request->state) . '
                    </div>
                ';
            })


            // ── Created At ──────────────────────────────────────────
            ->editColumn('created_at', function (CareRequest $request) {
                return '
                    <div class="fw-semibold text-gray-800">
                        ' . $request->created_at->format('d M Y') . '
                    </div>
                    <div class="text-muted fs-7">
                        ' . $request->created_at->diffForHumans() . '
                    </div>
                ';
            })
            
            // ── Actions ──────────────────────────────────────────────
            ->addColumn('actions', function (CareRequest $request) {
                $viewUrl = route('admin.requests.show', $request->id); 
                
                return '
                    <div class="d-flex gap-1 justify-content-end">
                        <a href="' . $viewUrl . '"
                            class="btn btn-sm btn-icon btn-light-primary border border-primary w-30px h-30px me-1"
                            title="View">
                            <i class="ki-outline ki-eye fs-5"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-sm btn-icon btn-light-danger border border-danger w-30px h-30px btn-delete"
                            data-id="' . $request->id . '"
                            title="Delete">
                            <i class="ki-outline ki-trash fs-5"></i>
                        </button>
                    </div>
                ';
            })

            ->rawColumns([
                'reference_id',
                'user',
                'status',
                'date_time',
                'location',
                'created_at',
                'actions',
            ]);
    }

    public function query(CareRequest $model)
    {
        $query = $model->newQuery()->with('user')->select('care_requests.*');
        
        // Filter by user_id
        if (request()->filled('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // Filter by status
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Filter by Today Requests
        if (request('is_today') === '1') {
            $query->whereDate('care_requests.created_at', \Carbon\Carbon::today());
        }

        // Filter by selected date
        if (request()->filled('date')) {
            $query->whereDate('care_requests.created_at', request('date'));
        }

        return $query;
    }

    public function filename(): string
    {
        return 'CareRequests_' . date('Y_m_d_His');
    }
}

