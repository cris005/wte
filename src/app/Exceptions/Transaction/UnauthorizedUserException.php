<?php

namespace App\Exceptions\Transaction;

use App\Exceptions\AbstractWalletException;
use App\Http\Factory\JsonResponseFactory;
use Illuminate\Http\JsonResponse;
use Throwable;

class UnauthorizedUserException extends AbstractWalletException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'This User is not authorized to process this transaction',
            config('codes.error.unauthorized'),
            $previous
        );
    }
}
