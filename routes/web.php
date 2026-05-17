<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\LoginHistoryController;
use App\Http\Controllers\Admin\NurseController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\System\ErrroLogsController;
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

    Route::prefix('nurses')->name('nurses.')->group(function () {

        Route::get('/', [NurseController::class, 'index'])->name('index');
        Route::get('/data', [NurseController::class, 'indexData'])->name('data');

        Route::get('/pending', [NurseController::class, 'pending'])->name('pending');
        Route::get('/pending/data', [NurseController::class, 'pendingData'])->name('pending.data');

        Route::get('/approved', [NurseController::class, 'approved'])->name('approved');
        Route::get('/approved/data', [NurseController::class, 'approvedData'])->name('approved.data');

        Route::get('/rejected', [NurseController::class, 'rejected'])->name('rejected');
        Route::get('/rejected/data', [NurseController::class, 'rejectedData'])->name('rejected.data');
        Route::get('/{user}', [NurseController::class, 'show'])->name('show');
        Route::get('/{user}/application', [NurseController::class, 'showApplication'])->name('show-application');
        Route::get('/{user}/review-step-view/{step}', [NurseController::class, 'getReviewStepView'])->name('review-step-view');
        Route::post('/{user}/review-step', [NurseController::class, 'reviewStep'])->name('review-step');
        Route::post('/{user}/finalize-review', [NurseController::class, 'finalizeReview'])->name('finalize-review');
    });

    // PEOPLE — Patients
    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('data', [PatientController::class, 'data'])->name('data');
        Route::get('blocked', [PatientController::class, 'blocked'])->name('blocked');
        Route::get('blocked/data', [PatientController::class, 'blockedData'])->name('blocked.data');
        Route::post('{patient}/unblock', [PatientController::class, 'unblock'])->name('unblock');
        
        Route::get('{patient}', [PatientController::class, 'show'])->name('show');
        Route::get('{patient}/edit', [PatientController::class, 'edit'])->name('edit');
        Route::post('{patient}', [PatientController::class, 'update'])->name('update');
        Route::delete('{patient}', [PatientController::class, 'destroy'])->name('destroy');
    });

    // PEOPLE — Login History
    Route::prefix('login-history')->name('login-history.')->group(function () {
        Route::get('/', [LoginHistoryController::class, 'index'])->name('index');
        Route::get('data', [LoginHistoryController::class, 'data'])->name('data');
        Route::post('empty', [LoginHistoryController::class, 'empty'])->name('empty');
        Route::get('{id}', [LoginHistoryController::class, 'show'])->name('show');
    });

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
        Route::resource('care-types', \App\Http\Controllers\Admin\CareTypeController::class);
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
    // COMMENTS
    // =====================
    Route::post('comments', [\App\Http\Controllers\Admin\CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');

    // =====================
    // SYSTEM
    // =====================
    Route::prefix('system')->name('system.')->group(function () {

        Route::get('error-logs', [ErrroLogsController::class, 'index'])->name('error-logs');
        Route::get('error-logs/data', [ErrroLogsController::class, 'data'])->name('errors.data');
        Route::post('error-logs/empty', [ErrroLogsController::class, 'empty'])->name('errors.empty');
        Route::get('error-logs/{id}', [ErrroLogsController::class, 'show'])->name('errors.show');


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



    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

});