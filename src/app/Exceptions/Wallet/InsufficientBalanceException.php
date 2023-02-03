<?php

namespace App\Exceptions\Wallet;

use App\Exceptions\AbstractWalletException;
use Throwable;

class InsufficientBalanceException extends AbstractWalletException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Insufficient balance for this transaction',
            config('codes.error.insufficient_balance'),
            $previous
        );
    }
}
