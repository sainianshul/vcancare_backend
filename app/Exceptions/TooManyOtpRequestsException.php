<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Exception;

class TooManyOtpRequestsException extends Exception
{
    public function render()
    {
        return ApiResponse::error(
            $this->getMessage(),
            429
        );
    }
}