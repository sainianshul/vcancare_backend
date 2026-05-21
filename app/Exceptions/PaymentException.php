<?php

namespace App\Exceptions;

class PaymentException extends ApiException
{
    protected int $defaultStatus = 402;
}
