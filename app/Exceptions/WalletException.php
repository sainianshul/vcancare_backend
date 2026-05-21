<?php

namespace App\Exceptions;

class WalletException extends ApiException
{
    protected int $defaultStatus = 400;
}
