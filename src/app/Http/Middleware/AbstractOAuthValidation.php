<?php

namespace App\Http\Middleware;

use App\Http\Factory\JsonResponseFactory;
use App\Http\Factory\LoggerTrait;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

abstract class AbstractOAuthValidation
{
    use LoggerTrait;

    protected Request $request;

    /**
     * Authenticate a User using their Access Token
     *
     * @param Request $request
     * @param Closure $next
     * @return Response|RedirectResponse|JsonResponse|BinaryFileResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse|BinaryFileResponse
    {
        $this->request = $request;
        if (empty($this->request->bearerToken())) {
            return $this->unauthenticated('You must provide an access token to access this content.');
        }

        $this->process();
        $this->log()->info('Client Authenticated', $this->getClientInfo());

        return $next($this->request);
    }

    /**
     * Handle the business rules of this Middleware
     *
     * @return void
     */
    abstract protected function process(): void;

    /**
     * Log a successful authentication
     *
     * @return array
     */
    protected function getClientInfo(): array
    {
        return [
            'request_id'   => $this->request->attributes->get('request_id'),
            'user_id'      => Auth::id(),
            'client_id'    => $this->request->attributes->get('client_id'),
            'partner_id'   => $this->request->attributes->get('partner_id'),
            'ip_address'   => $this->request->ip(),
            'user_agent'   => $this->request->userAgent()
        ];
    }

    /**
     * Authenticate the User associated with the provided Access Token
     *
     * @param bool $isUser Should authenticate a User (as opposed to a Client Credentials Grant authentication)
     * @return void
     */
    protected function authenticate(bool $isUser = true): void
    {
        $metadata = $this->getMetadata();
        $this->validateClient($metadata->client_id);
        $this->request->attributes->add([
            'client_id' => $metadata->client_id,
            'partner_id' => $metadata->partner_id
        ]);

        if (! $isUser && $metadata->user_id !== null) {
            abort($this->forbidden());
        }

        if ($isUser && ! Auth::onceUsingId($metadata->user_id)) {
            abort($this->forbidden());
        }
    }

    /**
     * Fetch the OAuth 2.0 Client's metadata
     *
     * @return object
     */
    protected function getMetadata(): object
    {
        $response = Http::withToken($this->request->bearerToken())
            ->acceptJson()
            ->withoutVerifying()
            ->get(config('services.oauth.host') . config('services.oauth.endpoint.client_credentials'));

        if ($response->failed()) {
            abort($this->unauthenticated('The access token provided is either expired or invalid.'));
        }

        return $response->object()->metadata;
    }

    /**
     * Validate whether this Client is allowed to authenticate or not
     *
     * @param string $clientId
     * @return void
     */
    protected function validateClient(string $clientId): void
    {
        if ($clientId === config('services.oauth.public_client_id')) {
            $this->log()->alert('Unauthorized authentication attempt', $this->getClientInfo());
            abort($this->forbidden('Insufficient permissions to access this resource'));
        }
    }

    /**
     * Handle a forbidden result
     *
     * @param string|null $message
     * @return JsonResponse
     */
    protected function forbidden(?string $message = null): JsonResponse
    {
        return JsonResponseFactory::accessForbidden($message ?? 'Access to this resource is forbidden for this account.');
    }

    /**
     * Handle an unauthenticated result
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function unauthenticated(string $message): JsonResponse
    {
        return JsonResponseFactory::unauthenticated($message);
    }
}
