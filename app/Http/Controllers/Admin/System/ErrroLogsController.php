<?php

namespace App\Http\Controllers\Admin\System;

use App\DataTables\System\ErrorLogsDataTable;
use App\Http\Controllers\Controller;
use App\Models\ApplicationError;

class ErrroLogsController extends Controller
{
    public function index(ErrorLogsDataTable $dataTable)
    {
        return $dataTable->render('admin.systems.error-logs.index');
    }

    public function data(ErrorLogsDataTable $dataTable)
    {
        return $dataTable->ajax();
    }

    public function show($id)
    {
        $error = ApplicationError::with('user')->findOrFail($id);

        return view('admin.systems.error-logs.show', compact('error'));
    }
}