<?php

namespace App\Exceptions\Transaction;

use App\Exceptions\AbstractWalletException;
use App\Http\Factory\JsonResponseFactory;
use Illuminate\Http\JsonResponse;
use Throwable;

class InvalidTransferException extends AbstractWalletException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'This transaction has already been processed',
            config('codes.error.unauthorized'),
            $previous
        );
    }
}
