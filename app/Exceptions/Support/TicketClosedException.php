<?php

namespace App\Exceptions\Support;

use Exception;

class TicketClosedException extends Exception
{
    public function __construct($message = "This support ticket is closed and cannot be modified.")
    {
        parent::__construct($message);
    }
}
