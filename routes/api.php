<?php

use App\Http\Controllers\Api\Nurse\OnboardingController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\AuthController;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {

        Route::post('send-otp', [AuthController::class, 'sendOtp']);

        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    });


    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('auth')->group(function () {

            Route::get('me', [AuthController::class, 'me']);

            Route::post('logout', [AuthController::class, 'logout']);
        });

        // User Routes

        // Nurse Routes
        Route::prefix('nurse')->group(function () {

            //  Nurse Onboarding Routes
            Route::post('onboarding/basic-profile', [OnboardingController::class, 'saveBasicProfile']);
            Route::post('onboarding/care-type', [OnboardingController::class, 'saveCareTypes']);
            Route::post('onboarding/education', [OnboardingController::class, 'saveEducation']);
            Route::post('onboarding/work-history', [OnboardingController::class, 'saveWorkHistory']);
            Route::post('onboarding/documents', [OnboardingController::class, 'saveDocuments']);
            Route::post('onboarding/availability', [OnboardingController::class, 'saveAvailability']);
            Route::post('onboarding/submit', [OnboardingController::class, 'submitForReview']);
            Route::get('onboarding/step-data/{step}', [OnboardingController::class, 'getStepData']);
            Route::post('onboarding/reapply', [OnboardingController::class, 'reapply']);
        });
    });
});