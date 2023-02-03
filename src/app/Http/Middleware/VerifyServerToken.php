<?php

namespace App\Http\Middleware;

use App\Http\Factory\JsonResponseFactory;
use App\Http\Factory\LoggerTrait;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VerifyServerToken
{
    use LoggerTrait;

    /**
     * Authenticate a User using their Access Token
     *
     * @param Request $request
     * @param Closure $next
     * @return Response|RedirectResponse|JsonResponse|BinaryFileResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse|BinaryFileResponse
    {
        $token = $request->bearerToken();
        if (empty($token)) {
            return JsonResponseFactory::unauthenticated('You must provide an access token to access this content.');
        }

        $response = Http::withToken($token)
            ->withoutVerifying()
            ->acceptJson()
            ->get(config('services.oauth.host') . config('services.oauth.endpoint.client_credentials'));

        if ($response->failed()) {
            return JsonResponseFactory::unauthenticated('The access token provided is either expired or invalid.');
        }

        $metadata = $response->object()->metadata;
        $this->log()->notice('Client authenticated', [
            'client_id'  => $metadata->client_id,
            'partner_id' => $metadata->partner_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return $next($request);
    }
}
