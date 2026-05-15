<?php

namespace App\Http\Controllers\Admin\System;

use App\DataTables\System\ErrorLogsDataTable;
use App\Http\Controllers\Controller;

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
}