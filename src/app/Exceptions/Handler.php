<?php

namespace App\Exceptions;

use App\Exceptions\Route\NotFoundException;
use App\Http\Factory\JsonResponseFactory;
use App\Http\Factory\Logger;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /** @inheritDoc */
    public function report(Throwable $e): void
    {
        if (config('app.env') === 'local') {
            parent::report($e);
            return;
        }

        if ($e instanceof HttpResponseException) {
            parent::report($e);
            return;
        }

        if ($e instanceof NotFoundHttpException) {
            $response = (new NotFoundException())->toJsonResponse();
            throw new HttpResponseException($response);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $response = JsonResponseFactory::methodForbidden();
            throw new HttpResponseException($response);
        }

        // Notify transactional Exceptions through a Slack Channel
        if ($e instanceof JsonResponseException) {
            /*Log::channel('slack')->error(get_class($e) . ' Thrown', [
                'message'   => $e->getMessage(),
                'thrown_at' => $e->getFile() . ':' . $e->getLine(),
                'trace'     => $e->getTrace()
            ]);*/
            $response = $e->toJsonResponse();
        } else {
            Logger::exception('Internal Exception', $e);
            $response = (new InternalException($e))->toJsonResponse();
        }

        throw new HttpResponseException($response);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
