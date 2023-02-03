<?php

namespace App\Http\Controllers;

use App\Http\Factory\LoggerTrait;
use Exception;
use Laminas\Json\Json;
use App\Http\Factory\JsonResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class AbstractRestController extends Controller implements RestControllerInterface
{
    use LoggerTrait;

    /** @inheritDoc */
    public function __construct(protected Request $request) {}

    /** @inheritDoc */
    public function success(string|null|iterable $payload = null): JsonResponse
    {
        return JsonResponseFactory::success($this->request->getMethod(), $payload);
    }

    /** @inheritDoc */
    public function error(int $statusCode, string|null|iterable $responseBody = null): JsonResponse
    {
        if (! empty($responseBody) && is_string($responseBody)) {
            try {
                $details = Json::decode($responseBody, Json::TYPE_ARRAY);
            } catch (Exception) {
                $details = $responseBody;
            }
        } else {
            $details = $responseBody;
        }

        if (is_string($details)) {
            $this->log()->error($details);
        } else {
            $this->log()->error('Runtime error', (array) $details);
        }

        return JsonResponseFactory::fromCode($statusCode, null, $details);
    }
}
