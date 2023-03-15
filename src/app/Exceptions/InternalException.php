<?php

namespace App\Exceptions;

use Throwable;

class InternalException extends AbstractWalletException
{
    public int $httpStatus = 500;

    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'An unknown error has occurred',
            config('codes.error.invalid_fees'),
            $previous
        );
    }
}
