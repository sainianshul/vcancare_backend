<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NurseController extends Controller
{

    public function index()
    {

        $data = [
            'pageTitle' => 'All Nurses',
            'nurses' => null
        ];

        return view("admin.nurses.index", $data);

    }



}
