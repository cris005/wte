<?php

namespace App\Http\Controllers;

use Traversable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @author Cristhian Hernandez (cristhian.hernandez@pepltd.com.au)
 * @version 1.0.0
 * @package Controller/Interface
 * @subpackage Rest
 */
interface RestControllerInterface
{
    /**
     * @param Request $request The Client's request data
     */
    public function __construct(Request $request);

    /**
     * Return a successful response
     *
     * @param string|iterable|null $payload The body of the response. If a string is provided, it will be treated as the
     * value of the key "message" in a JSON response.
     * @return JsonResponse|Response
     */
    public function success(string|null|iterable $payload): JsonResponse|Response;

    /**
     * Return an error response
     *
     * @param int $statusCode
     * @param string|iterable|null $responseBody
     * @return JsonResponse|Response
     */
    public function error(int $statusCode, string|null|iterable $responseBody): JsonResponse|Response;
}
