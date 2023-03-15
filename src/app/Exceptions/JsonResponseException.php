<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

interface JsonResponseException
{
    /**
     * Produce a JSON Response object from this exception
     *
     * @return JsonResponse
     */
    public function toJsonResponse(): JsonResponse;
}
