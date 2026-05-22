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

    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'stats'])->name('dashboard.stats');

    // =====================
    // PEOPLE — Nurses
    // =====================

    Route::prefix('nurses')->name('nurses.')->group(function () {

        Route::get('/', [NurseController::class, 'index'])->name('index');
        Route::get('/data', [NurseController::class, 'indexData'])->name('data');

        Route::get('/pending_approval', [NurseController::class, 'pending'])->name('pending_approval');
        Route::get('/pending_approval/data', [NurseController::class, 'pendingData'])->name('pending_approval.data');

        Route::get('/approved', [NurseController::class, 'approved'])->name('approved');
        Route::get('/approved/data', [NurseController::class, 'approvedData'])->name('approved.data');

        Route::get('/rejected', [NurseController::class, 'rejected'])->name('rejected');
        Route::get('/rejected/data', [NurseController::class, 'rejectedData'])->name('rejected.data');
        Route::get('/pending-count', [NurseController::class, 'pendingCount'])->name('pending-count');
        Route::get('/{user}/edit', [NurseController::class, 'edit'])->name('edit');
        Route::post('/{user}/update', [NurseController::class, 'update'])->name('update');
        Route::get('/{user}', [NurseController::class, 'show'])->name('show');
        Route::get('/{user}/stats', [NurseController::class, 'stats'])->name('stats');
        Route::get('/{user}/application', [NurseController::class, 'showApplication'])->name('show-application');
        Route::get('/{user}/reviews', [NurseController::class, 'reviews'])->name('reviews');
        Route::get('/{user}/reviews/data', [NurseController::class, 'reviewsData'])->name('reviews.data');
        Route::get('/{user}/bids', [NurseController::class, 'bids'])->name('bids');
        Route::get('/{user}/bids/data', [NurseController::class, 'bidsData'])->name('bids.data');
        Route::get('/{user}/bookings', [NurseController::class, 'bookings'])->name('bookings');
        Route::get('/{user}/bookings/data', [NurseController::class, 'bookingsData'])->name('bookings.data');
        Route::get('/{user}/login-history', [NurseController::class, 'loginHistory'])->name('login-history');
        Route::get('/{user}/login-history/data', [NurseController::class, 'loginHistoryData'])->name('login-history.data');
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
        Route::get('{patient}/stats', [PatientController::class, 'stats'])->name('stats');
        Route::get('{patient}/requests', [PatientController::class, 'requests'])->name('requests');
        Route::get('{patient}/requests/data', [PatientController::class, 'requestsData'])->name('requests.data');
        Route::get('{patient}/bookings', [PatientController::class, 'bookings'])->name('bookings');
        Route::get('{patient}/bookings/data', [PatientController::class, 'bookingsData'])->name('bookings.data');
        Route::get('{patient}/login-history', [PatientController::class, 'loginHistory'])->name('login-history');
        Route::get('{patient}/login-history/data', [PatientController::class, 'loginHistoryData'])->name('login-history.data');
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
        Route::get('/', [\App\Http\Controllers\Admin\RequestController::class, 'index'])->name('index');
        Route::get('/data', [\App\Http\Controllers\Admin\RequestController::class, 'data'])->name('data');
        Route::get('today', [\App\Http\Controllers\Admin\RequestController::class, 'todayIndex'])->name('today');
        Route::get('{request}/bids-data', [\App\Http\Controllers\Admin\RequestController::class, 'bidsData'])->name('bids-data');
        Route::get('{request}/notified-nurses-data', [\App\Http\Controllers\Admin\RequestController::class, 'notifiedNursesData'])->name('notified-nurses-data');
        Route::get('{request}', [\App\Http\Controllers\Admin\RequestController::class, 'show'])->name('show');
        Route::delete('{request}', [\App\Http\Controllers\Admin\RequestController::class, 'destroy'])->name('destroy');
        
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

    // =====================
    // OPERATIONS — Bookings
    // =====================
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('index');
        Route::get('/data', [\App\Http\Controllers\Admin\BookingController::class, 'data'])->name('data');
        Route::get('active', [\App\Http\Controllers\Admin\BookingController::class, 'active'])->name('active');
        Route::get('active/data', [\App\Http\Controllers\Admin\BookingController::class, 'activeData'])->name('active-data');
        Route::get('cancelled', [\App\Http\Controllers\Admin\BookingController::class, 'cancelled'])->name('cancelled');
        Route::get('cancelled/data', [\App\Http\Controllers\Admin\BookingController::class, 'cancelledData'])->name('cancelled-data');
        Route::get('{booking}/sessions-data', [\App\Http\Controllers\Admin\BookingController::class, 'sessionsData'])->name('sessions-data');
        Route::get('{booking}/payment-logs-data', [\App\Http\Controllers\Admin\BookingController::class, 'paymentLogsData'])->name('payment-logs-data');
        Route::get('{booking}/reviews-data', [\App\Http\Controllers\Admin\BookingController::class, 'reviewsData'])->name('reviews-data');
        Route::get('{booking}/bids-data', [\App\Http\Controllers\Admin\BookingController::class, 'bidsData'])->name('bids-data');
        Route::get('{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('show');
    });

    // OPERATIONS — Bids
    Route::prefix('bids')->name('bids.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BidController::class, 'index'])->name('index');
        Route::get('/data', [\App\Http\Controllers\Admin\BidController::class, 'data'])->name('data');
        Route::get('today', [\App\Http\Controllers\Admin\BidController::class, 'todayIndex'])->name('today');
        Route::get('today/data', [\App\Http\Controllers\Admin\BidController::class, 'todayData'])->name('today.data');
        Route::get('active', [\App\Http\Controllers\Admin\BidController::class, 'active'])->name('active');
        Route::get('active/data', [\App\Http\Controllers\Admin\BidController::class, 'activeData'])->name('active.data');
        Route::get('accepted', [\App\Http\Controllers\Admin\BidController::class, 'accepted'])->name('accepted');
        Route::get('accepted/data', [\App\Http\Controllers\Admin\BidController::class, 'acceptedData'])->name('accepted.data');
        Route::get('rejected', [\App\Http\Controllers\Admin\BidController::class, 'rejected'])->name('rejected');
        Route::get('rejected/data', [\App\Http\Controllers\Admin\BidController::class, 'rejectedData'])->name('rejected.data');
        
        Route::get('{bid}', [\App\Http\Controllers\Admin\BidController::class, 'show'])->name('show');
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

    // API Token management (for patient/nurse profile pages)
    Route::delete('api-tokens/{tokenId}/revoke', function ($tokenId) {
        $token = \Laravel\Sanctum\PersonalAccessToken::findOrFail($tokenId);
        $token->delete();
        return response()->json(['success' => true]);
    })->name('api-tokens.revoke');

    Route::post('api-tokens/{userId}/issue', function ($userId) {
        $user = \App\Models\User::findOrFail($userId);
        // Issue an additional token for admin testing without revoking user's active sessions
        $plainTextToken = $user->createToken('admin-swagger-testing')->plainTextToken;
        return response()->json(['success' => true, 'token' => $plainTextToken]);
    })->name('api-tokens.issue');

    // =====================
    // SYSTEM
    // =====================
    Route::prefix('system')->name('system.')->group(function () {

        Route::get('error-logs', [ErrroLogsController::class, 'index'])->name('error-logs');
        Route::get('error-logs/data', [ErrroLogsController::class, 'data'])->name('errors.data');
        Route::post('error-logs/empty', [ErrroLogsController::class, 'empty'])->name('errors.empty');
        Route::get('error-logs/pending-count', [ErrroLogsController::class, 'pendingCount'])->name('errors.pending-count');
        Route::post('error-logs/{id}/status', [ErrroLogsController::class, 'status'])->name('errors.status');
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

    // SUPPORT TICKETS
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SupportController::class, 'index'])->name('index');
        Route::get('/data', [\App\Http\Controllers\Admin\SupportController::class, 'data'])->name('data');
        Route::get('/{id}', [\App\Http\Controllers\Admin\SupportController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [\App\Http\Controllers\Admin\SupportController::class, 'reply'])->name('reply');
        Route::post('/{id}/status', [\App\Http\Controllers\Admin\SupportController::class, 'updateStatus'])->name('update-status');
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

    // Admin Profile
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

});