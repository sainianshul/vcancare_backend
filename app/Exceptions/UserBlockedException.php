<?php

namespace App\Exceptions;

class UserBlockedException extends ApiException
{
    protected int $defaultStatus = 403;
}