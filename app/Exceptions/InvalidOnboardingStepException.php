<?php

namespace App\Exceptions;

class InvalidOnboardingStepException extends ApiException
{
    protected int $defaultStatus = 422;
}