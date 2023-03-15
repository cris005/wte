<?php

namespace App\Http\Factory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\MessageBag;
use Throwable;
use Traversable;
use function response;

/**
 * @author Cristhian Hernandez (cristhian.hernandez@pepltd.com.au)
 * @version 1.0.0
 * @package Factory/Response
 * @subpackage Json
 */
class JsonResponseFactory
{
    public const REQUEST_SUCCESS_OK = 200;
    public const REQUEST_SUCCESS_CREATED = 201;
    public const REQUEST_SUCCESS_QUEUED = 202;
    public const REQUEST_SUCCESS_NO_CONTENT = 204;
    public const CLIENT_ERROR_BAD_REQUEST = 400;
    public const CLIENT_ERROR_UNAUTHORIZED = 401;
    public const CLIENT_ERROR_FORBIDDEN = 403;
    public const CLIENT_ERROR_NOT_FOUND = 404;
    public const CLIENT_ERROR_METHOD_NOT_ALLOWED = 405;
    public const CLIENT_ERROR_NOT_ACCEPTABLE = 406;
    public const CLIENT_ERROR_FAILED_VALIDATION = 422;
    public const SERVER_ERROR_INTERNAL = 500;
    private static array $headers;

    /**
     * Return a JSON response outlining a successful request.
     *
     * @param string|null $method Leave null if you need a response code 200
     * @param string|iterable|null $message A message string or payload
     * @param string|iterable|null $details Additional details
     * @return JsonResponse
     */
    public static function success(?string $method, string|null|iterable $message, string|null|iterable $details = null): JsonResponse
    {
        $statusCode = match (true) {
            $method === 'POST' => self::REQUEST_SUCCESS_CREATED,
            $method === 'DELETE', empty($message) => self::REQUEST_SUCCESS_NO_CONTENT,
            default => self::REQUEST_SUCCESS_OK,
        };

        if ($statusCode === self::REQUEST_SUCCESS_NO_CONTENT) {
            return self::create($statusCode, null, null, [], true);
        }
        return self::create($statusCode, $message, $details);
    }

    /**
     * Return a JSON response outlining a process has been successfully queued.
     *
     * @return JsonResponse
     */
    public static function queued(): JsonResponse
    {
        return self::create(202, null, null, [], true);
    }

    /**
     * Return a JSON response outlining that the method used to access a resource is not allowed.
     *
     * @return JsonResponse
     */
    public static function methodForbidden(): JsonResponse
    {
        return self::create(self::CLIENT_ERROR_METHOD_NOT_ALLOWED, 'The HTTP Request method used is not allowed.');
    }

    /**
     * Return a JSON response outlining that the resource requested was not found.
     *
     * @param string|null $entityName The name of the entity or resource that was not found (this will be used to generate the default message)
     * @param string|iterable|null $message Override the default response message with your own content
     * @param string|iterable|null $details
     * @return JsonResponse
     */
    public static function notFound(?string $entityName = null, string|null|iterable $message = null, string|null|iterable $details = null): JsonResponse
    {
        if (! empty($message)) {
            return self::create(self::CLIENT_ERROR_NOT_FOUND, $message);
        }

        if (! empty($entityName)) {
            $defaultMessage = sprintf('The resource requested "%s" was not found.', $entityName);
        } else {
            $defaultMessage = 'The resource requested was not found.';
        }
        return self::create(self::CLIENT_ERROR_NOT_FOUND, $defaultMessage, $details);
    }


    /**
     * Return a JSON response outlining that the User has not authenticated and therefore has no access.
     *
     * @param string|iterable|null $details
     * @return JsonResponse
     */
    public static function unauthenticated(string|null|iterable $details = null): JsonResponse
    {
        return self::create(
            self::CLIENT_ERROR_UNAUTHORIZED,
            'You are unauthenticated and therefore unauthorized to access this resource.',
            $details
        );
    }

    /**
     * Return a JSON response outlining that the resource requested was not found.
     *
     * @param string|iterable|null $details
     * @return JsonResponse
     */
    public static function notAcceptable(string|null|iterable $details = null): JsonResponse
    {
        return self::create(
            self::CLIENT_ERROR_NOT_ACCEPTABLE,
            'Request not acceptable. Please check your inputs.',
            $details
        );
    }

    /**
     * Return a JSON response outlining that access to a resource is not allowed.
     *
     * @param string|iterable|null $details
     * @return JsonResponse
     */
    public static function accessForbidden(string|null|iterable $details = null): JsonResponse
    {
        return self::create(
            self::CLIENT_ERROR_FORBIDDEN,
            'You do not have the sufficient permissions to access this resource.',
            $details
        );
    }

    /**
     * Return a JSON response outlining a Client error (bad request).
     *
     * @param string|iterable|null $details
     * @return JsonResponse
     */
    public static function badRequest(string|null|iterable $details = null): JsonResponse
    {
        return self::create(
            self::CLIENT_ERROR_BAD_REQUEST,
            'Invalid request. Please check your inputs.',
            $details
        );
    }

    /**
     * Return a JSON response outlining a failed validation.
     *
     * @param Validator|MessageBag $validation
     * @return JsonResponse
     */
    public static function failedValidation(Validator|MessageBag $validation): JsonResponse
    {
        $errors = $validation instanceof Validator ? $validation->errors() : $validation;

        return self::create(
            self::CLIENT_ERROR_FAILED_VALIDATION,
            [
                'message' => 'Validation failed. Please check your inputs.',
                'errors' => $errors
            ]
        );
    }

    /**
     * Return a JSON response outlining an internal Server error.
     *
     * @param string|array|Throwable $details Additional details for this error; if an Exception is provided, the response will be formatted automatically
     * @param string|array|null $context Additional context for the exception; will be ignored if no exception provided
     * @return JsonResponse
     */
    public static function serverError(string|array|Throwable $details, string|array|null $context = null): JsonResponse
    {
        if ($details instanceof Throwable) {
            $details = [
                'error' => [
                    'code' => $details->getCode(),
                    'message' => $details->getMessage(),
                    'line' => $details->getLine(),
                    'stackTrace' => $details->getTrace()
                ]
            ];
            if (! empty($context)) {
                $details['context'] = $context;
            }
        }

        return self::create(
            self::SERVER_ERROR_INTERNAL,
            'There has been an internal server error and this request cannot be processed.',
            $details
        );
    }

    /**
     * Generate a JSON response based on the status code provided.
     *
     * @param int $statusCode The HTTP response status code
     * @param string|iterable|null $message The response body/payload (keep in mind most errors will ignore this parameter)
     * @param string|iterable|null $details Any additional details you want to include
     * @param array $headers The HTTP response headers
     * @return JsonResponse
     */
    public static function fromCode(
        int                  $statusCode,
        string|null|iterable $message = null,
        string|null|iterable $details = null,
        array                $headers = []
    ): JsonResponse
    {
        self::$headers = $headers;
        return match ($statusCode) {
            self::CLIENT_ERROR_BAD_REQUEST        => self::badRequest($details),
            self::CLIENT_ERROR_UNAUTHORIZED       => self::unauthenticated($details),
            self::CLIENT_ERROR_FORBIDDEN          => self::accessForbidden($details),
            self::CLIENT_ERROR_NOT_FOUND          => self::notFound(null, null, $details),
            self::CLIENT_ERROR_METHOD_NOT_ALLOWED => self::methodForbidden(),
            self::CLIENT_ERROR_NOT_ACCEPTABLE     => self::notAcceptable($details),
            self::REQUEST_SUCCESS_OK              => self::success(null, $message, $details),
            self::REQUEST_SUCCESS_CREATED         => self::success('POST', $message, $details),
            self::REQUEST_SUCCESS_QUEUED          => self::queued(),
            self::REQUEST_SUCCESS_NO_CONTENT      => self::success(null, null),
            self::SERVER_ERROR_INTERNAL           => self::serverError($details),
            default => self::create($statusCode, $message, $details, self::$headers),
        };
    }

    /**
     * Create a JSON Response from a Paginator
     *
     * @param LengthAwarePaginator $paginator The Paginator object
     * @param string $resourceName The name of the resource embedded
     * @param array $headers The HTTP response headers
     * @return JsonResponse
     */
    public static function fromPagination(LengthAwarePaginator $paginator, string $resourceName, array $headers = []): JsonResponse
    {
        $data = [
            'items_from'     => $paginator->firstItem(),
            'items_to'       => $paginator->lastItem(),
            'items_total'    => $paginator->total(),
            'items_per_page' => $paginator->perPage(),
            'current_page'   => $paginator->currentPage(),
            'last_page'      => $paginator->lastPage(),
            '_embedded'      => [
                $resourceName => $paginator->items()
            ],
            '_links'         => [
                'self' => [
                    'href' => $paginator->url($paginator->currentPage()),
                ],
                'first' => [
                    'href' => $paginator->url(1),
                ],
                'prev' => [
                    'href' => $paginator->previousPageUrl()
                ],
                'next' => [
                    'href' => $paginator->nextPageUrl()
                ],
                'last' => [
                    'href' => $paginator->url($paginator->lastPage()),
                ],
            ]
        ];

        return response()->json($data, 200, $headers);
    }

    /**
     * Create a HAL+JSON Response from a Collection
     *
     * @param Collection $collection The Collection holding the data
     * @param string $resourceName The name of the resource embedded
     * @param array $headers The HTTP response headers
     * @return JsonResponse
     */
    public static function fromCollection(Collection $collection, string $resourceName, array $headers = []): JsonResponse
    {
        $data = [
            'items_total'     => $collection->count(),
            '_embedded' => [
                $resourceName => $collection
            ],
            '_links'    => [
                'self' => [
                    'href' => url()->current(),
                ],
            ]
        ];

        return response()->json($data, 200, $headers);
    }

    /**
     * Create a HAL+JSON Response from a Model
     *
     * @param Model $model The Model holding the data
     * @param array $headers The HTTP response headers
     * @return JsonResponse
     */
    public static function fromModel(Model $model, array $headers = []): JsonResponse
    {
        $model->setRelation('_links', collect([
            'self' => [
                'href' => url()->current(),
            ],
        ]));

        return response()->json($model, 200, $headers);
    }

    /**
     * Create a JSON Response object
     *
     * @param int $statusCode The HTTP response status code
     * @param string|iterable|null $message The response body/payload
     * @param string|iterable|null $details Any additional details
     * @param array $headers The HTTP response headers
     * @param bool $noContent Will the response have any content?
     * @param bool $noResponseProperties Should include basic HAL+JSON response properties?
     * @return JsonResponse
     */
    public static function create(
        int                  $statusCode,
        string|null|iterable $message,
        string|null|iterable $details = null,
        array                $headers = [],
        bool                 $noContent = false,
        bool                 $noResponseProperties = false
    ): JsonResponse
    {
        // If the property is set, we'll override this method's default header value
        if (isset(self::$headers)) {
            $headers = self::$headers;
        }

        if ($noContent) {
            return response()->json([], $statusCode, $headers);
        }

        if (is_string($message)) {
            $body['message'] = $message; // Default and generally expected route to take
        } else if ($message instanceof Traversable) {
            $body = iterator_to_array($message); // Replace the default 'message' key with your custom solution
        } else {
            $body = $message; // Either null, empty or a custom solution
        }

        if (! empty($details)) {
            if ($details instanceof Traversable) {
                $details = iterator_to_array($details);
            }
            $body['details'] = $details;
        }

        // If at this point $body is null, we will set it as an array to attach HAL+JSON properties
        if (empty($body)) {
            $body = [];
        }

        // Basic HAL+JSON response properties
        if (!$noResponseProperties) {
            self::setLinksHal($body);
            //self::setPropertiesHal($body, $statusCode); TODO: Deprecate this
        }

        return response()->json($body, $statusCode, $headers);
    }

    /**
     * Fetch the title associated to a status code
     *
     * @param int $statusCode
     * @return string
     */
    public static function getStatusTitle(int $statusCode): string
    {
        return match ($statusCode) {
            self::CLIENT_ERROR_BAD_REQUEST => 'Bad Request',
            self::CLIENT_ERROR_UNAUTHORIZED => 'Unauthorized',
            self::CLIENT_ERROR_FORBIDDEN => 'Forbidden',
            self::CLIENT_ERROR_NOT_FOUND => 'Not Found',
            self::CLIENT_ERROR_METHOD_NOT_ALLOWED => 'Method Not Allowed',
            self::CLIENT_ERROR_NOT_ACCEPTABLE => 'Not Acceptable',
            self::CLIENT_ERROR_FAILED_VALIDATION => 'Unprocessable Entity',
            self::REQUEST_SUCCESS_OK => 'Success',
            self::REQUEST_SUCCESS_CREATED => 'Created',
            self::REQUEST_SUCCESS_QUEUED  => 'Queued',
            self::REQUEST_SUCCESS_NO_CONTENT => 'No Content',
            self::SERVER_ERROR_INTERNAL => 'Internal Server Error',
            default => 'HTTP Response Title Unavailable'
        };
    }

    /**
     * Set _links of a HAL+JSON body
     *
     * @param array $body
     * @param string|null $url
     * @return void
     */
    public static function setLinksHal(array &$body, ?string $url = null): void
    {
        $url = ! empty($url) ? $url : url()->current();
        $body['_links'] = [
            'self' => [
                'href' => $url
            ]
        ];
    }

    /**
     * Set basic properties to a HAL+JSON body
     *
     * @param array $body The response body
     * @param int $statusCode Response status code
     * @return void
     */
    public static function setPropertiesHal(array &$body, int $statusCode): void
    {
        $properties = [
            'status' => $statusCode,
            'title' => self::getStatusTitle($statusCode),
            'type' => 'https://www.rfc-editor.org/rfc/rfc9110.html',
        ];
        $body = array_merge($properties, $body);
    }
}
