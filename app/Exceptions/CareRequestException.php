<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class CareRequestException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            return ApiResponse::error($this->getMessage(), 400);
        }

        return false; // let default handler manage it for non-JSON requests
    }
}
