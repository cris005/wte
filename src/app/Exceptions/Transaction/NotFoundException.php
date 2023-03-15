<?php

namespace App\Exceptions\Transaction;

use App\Exceptions\AbstractWalletException;
use App\Http\Factory\JsonResponseFactory;
use Illuminate\Http\JsonResponse;
use Throwable;

class NotFoundException extends AbstractWalletException
{
    public int $httpStatus = 404;

    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not find the transaction identified with that UUID',
            config('codes.error.invalid_ref_no'),
            $previous
        );
    }
}
