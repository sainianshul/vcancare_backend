<?php

namespace App\DataTables\Request;

use App\Models\RequestBid;
use Yajra\DataTables\Services\DataTable;

class RequestBidsDataTable extends DataTable
{
    protected $requestId;

    public function withRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('nurse', function (RequestBid $bid) {
                $user = $bid->nurse->user;
                $name = $user->name ?? 'Unknown';
                $initial = mb_strtoupper(mb_substr($name, 0, 2));

                $avatar = '';
                if ($user && $user->profile_photo) {
                    $avatar = '<div class="symbol symbol-30px symbol-circle me-3"><img src="' . \Illuminate\Support\Facades\Storage::url($user->profile_photo) . '" class="object-fit-cover" alt="Pic"></div>';
                } else {
                    $avatar = '<div class="symbol symbol-30px symbol-circle me-3"><span class="symbol-label bg-light-dark text-dark fw-bold fs-7">' . $initial . '</span></div>';
                }

                return '
                    <div class="d-flex align-items-center">
                        ' . $avatar . '
                        <div class="d-flex flex-column">
                            <span class="text-gray-900 fw-bold fs-7">' . e($name) . '</span>
                            <span class="text-gray-900 fs-8">ID: ' . $bid->nurse_id . '</span>
                        </div>
                    </div>
                ';
            })
            ->editColumn('nurse_amount', function (RequestBid $bid) {
                return '<span class="text-gray-900 fw-bold fs-7">₹' . number_format($bid->nurse_amount, 2) . '</span>';
            })
            ->editColumn('commission_amount', function (RequestBid $bid) {
                return '<span class="text-gray-900 fw-semibold fs-7">₹' . number_format($bid->commission_amount, 2) . '</span>';
            })
            ->editColumn('total_amount', function (RequestBid $bid) {
                return '<span class="text-gray-900 fw-bold fs-7">₹' . number_format($bid->total_amount, 2) . '</span>';
            })
            ->addColumn('status', function (RequestBid $bid) {
                $colors = [
                    RequestBid::STATUS_PENDING => 'warning',
                    RequestBid::STATUS_SELECTED => 'success',
                    RequestBid::STATUS_REJECTED => 'danger',
                    RequestBid::STATUS_CANCELLED => 'dark',
                ];
                $color = $colors[$bid->status] ?? 'dark';
                return '<span class="badge badge-light-' . $color . ' text-' . $color . ' fw-bold fs-8 px-2 py-1 border border-' . $color . '">' . ($bid->status_text ?? 'Unknown') . '</span>';
            })
            ->addColumn('actions', function (RequestBid $bid) {
                $viewUrl = route('admin.bids.show', $bid->id);
                return '
                    <div class="d-flex justify-content-end">
                        <a href="' . $viewUrl . '" class="btn btn-sm btn-icon btn-light-primary border border-primary w-25px h-25px me-1" title="View Bid">
                            <i class="ki-outline ki-eye fs-7 text-primary"></i>
                        </a>
                    </div>
                ';
            })
            ->rawColumns(['nurse', 'nurse_amount', 'commission_amount', 'total_amount', 'status', 'actions']);
    }

    public function query(RequestBid $model)
    {
        return $model->newQuery()
            ->with(['nurse.user'])
            ->where('care_request_id', $this->requestId);
    }
}
