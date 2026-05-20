<?php

namespace App\Exceptions;

class InvalidOtpException extends ApiException
{
    protected int $defaultStatus = 422;
}