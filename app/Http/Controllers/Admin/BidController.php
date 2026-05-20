<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestBid;
use Illuminate\Http\Request;

class BidController extends Controller
{
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
