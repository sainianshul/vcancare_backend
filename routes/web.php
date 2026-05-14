<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\NurseController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

});

Route::get('/', function () {
    return auth()->guard('web')->check() ? redirect('/admin') : redirect('/login');
});

Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'admin'])->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // =====================
    // PEOPLE — Nurses
    // =====================
    Route::prefix('nurse')->name('nurses.')->group(function () {
        Route::get('/', [NurseController::class, 'index'])->name('index');
        Route::get('pending', function () {
            echo "Pending Approval";
        })->name('pending');
        Route::get('review', function () {
            echo "Under Review";
        })->name('review');
        Route::get('approved', function () {
            echo "Approved Nurses";
        })->name('approved');
        Route::get('rejected', function () {
            echo "Rejected Nurses";
        })->name('rejected');
        Route::get('suspended', function () {
            echo "Suspended Nurses";
        })->name('suspended');
    });

    // PEOPLE — Patients
    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', function () {
            echo "All Patients";
        })->name('index');
        Route::get('blocked', function () {
            echo "Blocked Patients";
        })->name('blocked');
    });

    // PEOPLE — Login History
    Route::get('login-history', function () {
        echo "Login History";
    })->name('login-history');

    // =====================
    // OPERATIONS — Care Requests
    // =====================
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', function () {
            echo "All Requests";
        })->name('index');
        Route::get('new', function () {
            echo "New Requests";
        })->name('new');
        Route::get('active', function () {
            echo "Active Requests";
        })->name('active');
        Route::get('completed', function () {
            echo "Completed Requests";
        })->name('completed');
        Route::get('cancelled', function () {
            echo "Cancelled Requests";
        })->name('cancelled');
    });

    // OPERATIONS — Bids
    Route::prefix('bids')->name('bids.')->group(function () {
        Route::get('active', function () {
            echo "Active Bids";
        })->name('active');
        Route::get('accepted', function () {
            echo "Accepted Bids";
        })->name('accepted');
        Route::get('rejected', function () {
            echo "Rejected Bids";
        })->name('rejected');
    });

    // OPERATIONS — Services
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('care-types', function () {
            echo "Care Types";
        })->name('care-types');
    });

    // =====================
    // FINANCE — Payments
    // =====================
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('transactions', function () {
            echo "Transactions";
        })->name('transactions');
        Route::get('payouts', function () {
            echo "Nurse Payouts";
        })->name('payouts');
        Route::get('refunds', function () {
            echo "Refunds";
        })->name('refunds');
    });

    // =====================
    // INSIGHTS — Reports
    // =====================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('revenue', function () {
            echo "Revenue Reports";
        })->name('revenue');
        Route::get('nurse-activity', function () {
            echo "Nurse Activity";
        })->name('nurse-activity');
        Route::get('requests', function () {
            echo "Request Reports";
        })->name('requests');
    });

    // =====================
    // SYSTEM
    // =====================
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('error-logs', function () {
            echo "Error Logs";
        })->name('error-logs');
        Route::get('failed-jobs', function () {
            echo "Failed Jobs";
        })->name('failed-jobs');
        Route::get('queue', function () {
            echo "Queue Monitor";
        })->name('queue');
        Route::get('backups', function () {
            echo "Backups";
        })->name('backups');
    });

    // SYSTEM — Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('general', function () {
            echo "General Settings";
        })->name('general');
        Route::get('app', function () {
            echo "App Config";
        })->name('app');
        Route::get('roles', function () {
            echo "Roles & Permissions";
        })->name('roles');
    });

});