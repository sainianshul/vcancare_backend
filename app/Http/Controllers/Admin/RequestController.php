<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Request\RequestDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RequestDataTable $dataTable)
    {
        return $dataTable->render('admin.request.index');
    }

    /**
     * Get data for datatable (AJAX)
     */
    public function data(RequestDataTable $dataTable)
    {
        return $dataTable->ajax();
    }

    /**
     * Display the specified care request.
     */
    public function show($id)
    {
        $careRequest = \App\Models\CareRequest::with([
            'user', 
            'careType', 
            'bids.nurse.user'
        ])->findOrFail($id);

        return view('admin.request.show', compact('careRequest'));
    }

    /**
     * Get bids data for the specific request (AJAX).
     */
    public function bidsData($id, \App\DataTables\Request\RequestBidsDataTable $dataTable)
    {
        return $dataTable->withRequestId($id)->ajax();
    }

    /**
     * Get notified nurses data for the specific request (AJAX).
     */
    public function notifiedNursesData($id, \App\DataTables\Request\RequestNotifiedNursesDataTable $dataTable)
    {
        return $dataTable->withRequestId($id)->ajax();
    }

    /**
     * Remove the specified care request from storage.
     */
    public function destroy($id)
    {
        $careRequest = \App\Models\CareRequest::findOrFail($id);
        $careRequest->delete(); // Uses SoftDeletes

        return response()->json(['success' => true]);
    }
}
