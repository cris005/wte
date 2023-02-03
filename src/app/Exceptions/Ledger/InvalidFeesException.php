<?php

namespace App\Exceptions\Ledger;

use App\Exceptions\AbstractWalletException;
use Throwable;

class InvalidFeesException extends AbstractWalletException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'The reference number provided is invalid',
            config('codes.error.invalid_fees'),
            $previous
        );
    }
}
