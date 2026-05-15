<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PatientDataTable;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    public function index(PatientDataTable $dataTable)
    {
        return $dataTable->render('admin.patients.index');
    }

    public function data(PatientDataTable $dataTable)
    {
        return $dataTable->ajax();
    }
}