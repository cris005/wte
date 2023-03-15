<?php

namespace App\Exceptions\Journal;

use App\Exceptions\AbstractWalletException;
use App\Http\Factory\JsonResponseFactory;
use Illuminate\Http\JsonResponse;
use Throwable;

class InvalidReversalException extends AbstractWalletException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'This transaction cannot be reversed more than once',
            config('codes.error.unauthorized'),
            $previous
        );
    }
}
