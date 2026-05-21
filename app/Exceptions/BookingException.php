<?php

namespace App\Exceptions;

class BookingException extends ApiException
{
    protected int $defaultStatus = 400;
}
