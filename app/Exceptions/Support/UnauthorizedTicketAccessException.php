<?php

namespace App\Exceptions\Support;

use Exception;

class UnauthorizedTicketAccessException extends Exception
{
    public function __construct($message = "You are not authorized to access this support ticket.")
    {
        parent::__construct($message);
    }
}
