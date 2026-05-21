<?php

namespace App\Exceptions;

class TooManyOtpRequestsException extends ApiException
{
    protected int $defaultStatus = 429;
}