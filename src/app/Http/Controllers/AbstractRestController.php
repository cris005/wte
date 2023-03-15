<?php

namespace App\Http\Controllers;

use App\Http\Factory\LoggerTrait;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Laminas\Json\Json;
use App\Http\Factory\JsonResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\Uid\Ulid;

abstract class AbstractRestController extends Controller implements RestControllerInterface
{
    use LoggerTrait;

    /** @var string ULID of the current request */
    protected string $ulid;

    /** @inheritDoc */
    public function __construct(protected Request $request)
    {
        // Assign this request a unique ID to chain logs together
        $this->ulid = Ulid::generate();
        $this->request->attributes->add(['request_id' => $this->ulid]);
    }

    /**
     * Return a response for a process successfully queued.
     *
     * @return JsonResponse
     */
    public function queued(): JsonResponse
    {
        $this->log()->info('Process queued', ['request_id' => $this->ulid]);
        return JsonResponseFactory::queued();
    }

    /**
     * Return a response for a process successfully completed
     * but returns no content
     *
     * @return JsonResponse
     */
    public function successNoContent(): JsonResponse
    {
        $this->log()->info('Request fulfilled', ['request_id' => $this->ulid]);
        return JsonResponseFactory::fromCode(204);
    }

    /** @inheritDoc */
    public function success(null|iterable|Model $payload = null, ?string $resourceName = null): JsonResponse
    {
        $this->log()->info('Request fulfilled', ['request_id' => $this->ulid]);

        if ($payload instanceof LengthAwarePaginator) {
            return JsonResponseFactory::fromPagination($payload, $resourceName);
        }

        if ($payload instanceof Collection) {
            return JsonResponseFactory::fromCollection($payload, $resourceName);
        }

        if ($payload instanceof Model) {
            return JsonResponseFactory::fromModel($payload);
        }

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

        $this->log()->error('Request failed', ['request_id' => $this->ulid]);
        return JsonResponseFactory::fromCode($statusCode, null, $details);
    }
}
