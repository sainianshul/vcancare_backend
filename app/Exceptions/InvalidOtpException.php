<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Exception;

class InvalidOtpException extends Exception
{
    public function render()
    {
        return ApiResponse::error(
            $this->getMessage(),
            422
        );
    }
}