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

class VerifyAccessToken extends AbstractOAuthValidation
{
    /** @inheritDoc */
    protected function process(): void
    {
        $this->authenticate();
    }
}
