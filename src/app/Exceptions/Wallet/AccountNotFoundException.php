<?php

namespace App\Exceptions\Wallet;

use App\Exceptions\AbstractWalletException;
use Throwable;

class AccountNotFoundException extends AbstractWalletException
{
    public int $httpStatus = 404;

    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'The account number provided could not be found',
            config('codes.error.account_not_found'),
            $previous
        );
    }
}
