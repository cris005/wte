<?php

namespace App\Exceptions\Journal;

use App\Exceptions\AbstractWalletException;
use Throwable;

class InvalidFeesException extends AbstractWalletException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'The fee structure provided is invalid',
            config('codes.error.invalid_fees'),
            $previous
        );
    }
}
