<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\LoginHistoryDataTable;
use App\Models\LoginHistory;

class LoginHistoryController extends Controller
{
    public function index(LoginHistoryDataTable $dataTable)
    {
        return $dataTable->render('admin.login-history.index');
    }

    public function data(LoginHistoryDataTable $dataTable)
    {
        return $dataTable->ajax();
    }

    public function empty()
    {
        LoginHistory::query()->truncate();
        return response()->json(['status' => 'success']);
    }
}