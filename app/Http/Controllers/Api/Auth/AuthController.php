<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\SendOtpRequest;
use App\Http\Requests\Api\Auth\VerifyOtpRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * Send OTP
     *
     * Send OTP to mobile number.
     *
     * @group Authentication
     * @unauthenticated
     */
    public function sendOtp(SendOtpRequest $request)
    {

        $result = $this->authService->sendOtp(
            $request->string('phone')->value()
        );

        $message = 'OTP sent successfully';

        if (config('auth.show_test_otp')) {

            $message .= " ({$result['otp']})";
        }

        return ApiResponse::success(
            $message,
            [
                'is_registered' => $result['is_registered'],
            ]
        );
    }

    /**
     * Verify OTP
     *
     * Verify OTP and authenticate user.
     *
     * @group Authentication
     * @unauthenticated
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $result = $this->authService->verifyOtp(
            $request->validated(),
            $request->ip(),
            $request->userAgent()
        );

        return ApiResponse::success(
            'Authentication successful',
            [
                'token' => $result['token'],

                'user' => $result['user']->toUserResponse(),
            ]
        );
    }

    /**
     * Logout
     *
     * Logout current user.
     *
     * @group Authentication
     */
    public function logout(Request $request)
    {
        $this->authService->logout(
            $request->user()
        );

        return ApiResponse::success(
            'Logged out successfully'
        );
    }

    /**
     * Me
     *
     * Get authenticated user details.
     *
     * Nurse onboarding steps:
     *
     * 1 = Basic Profile,
     * 2 = Care Types,
     * 3 = Education,
     * 4 = Work History,
     * 5 = Documents,
     * 6 = Availability,
     * 7 = Submit For Review,
     * 8 = Completed,
     *
     * Nurse profile statuses:
     *
     * 0 = Pending,
     * 1 = Under Review,
     * 2 = Approved,
     * 3 = Rejected,
     * 4 = Suspended
     *
     * @group Authentication
     * @authenticated
     */
    public function me(Request $request)
    {
        return ApiResponse::success(
            'Profile fetched successfully',
            [
                'user' => $request->user()
                    ->toUserResponse(),
            ]
        );
    }

}