<?php

namespace App\Exceptions;

class CareRequestException extends ApiException
{
    protected int $defaultStatus = 400;
}
