<?php

namespace App\Exceptions;

use App\Http\Factory\JsonResponseFactory;
use Exception;
use Illuminate\Http\JsonResponse;

abstract class AbstractWalletException extends Exception implements JsonResponseException
{
    /** @var int HTTP Status Code */
    public int $httpStatus = 403;

    /** @inheritDoc */
    public function toJsonResponse(): JsonResponse
    {
        $details = [
            'error' => $this->getMessage(),
            'code'  => $this->getCode(),
        ];
        return JsonResponseFactory::fromCode($this->httpStatus, null, $details);
    }
}
