<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestBid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function index(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        return $dataTable->render('admin.bids.index', [
            'title' => 'All Bids',
            'dataUrl' => route('admin.bids.data')
        ]);
    }

    public function data(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        return $dataTable->ajax();
    }

    public function todayIndex(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        return $dataTable->render('admin.bids.index', [
            'title' => 'Today\'s Bids',
            'dataUrl' => route('admin.bids.today.data')
        ]);
    }

    public function todayData(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        request()->merge(['today' => true]);
        return $dataTable->ajax();
    }

    public function active(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        return $dataTable->render('admin.bids.index', [
            'title' => 'Active Bids',
            'dataUrl' => route('admin.bids.active.data'),
            'hideStatusFilter' => true
        ]);
    }

    public function activeData(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        request()->merge(['status' => RequestBid::STATUS_PENDING]);
        return $dataTable->ajax();
    }

    public function accepted(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        return $dataTable->render('admin.bids.index', [
            'title' => 'Accepted Bids',
            'dataUrl' => route('admin.bids.accepted.data'),
            'hideStatusFilter' => true
        ]);
    }

    public function acceptedData(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        request()->merge(['status' => RequestBid::STATUS_SELECTED]);
        return $dataTable->ajax();
    }

    public function rejected(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        return $dataTable->render('admin.bids.index', [
            'title' => 'Rejected Bids',
            'dataUrl' => route('admin.bids.rejected.data'),
            'hideStatusFilter' => true
        ]);
    }

    public function rejectedData(\App\DataTables\Bid\BidDataTable $dataTable)
    {
        request()->merge(['status' => RequestBid::STATUS_REJECTED]);
        return $dataTable->ajax();
    }

    /**
     * Display the specified bid.
     */
    public function show($id)
    {
        $bid = RequestBid::with([
            'careRequest.user', 
            'careRequest.careType', 
            'nurse.user',
            'nurse.careTypes'
        ])->findOrFail($id);

        return view('admin.bids.show', compact('bid'));
    }
}
