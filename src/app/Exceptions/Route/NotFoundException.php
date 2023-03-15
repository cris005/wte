<?php

namespace App\Exceptions\Route;

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
            'The route provided did not match a resource in this server',
            config('codes.error.invalid_ref_no'),
            $previous
        );
    }
}
