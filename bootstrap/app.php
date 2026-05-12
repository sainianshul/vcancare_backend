<?php

use App\Models\ApplicationError;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (ValidationException $e, $request) {
            if (!$request->is('api/*')) {
                return null;
            }
            return ApiResponse::error('Validation failed', 422, $e->errors());
        });

        $exceptions->render(function (AuthenticationException $e, $request) {
            if (!$request->is('api/*')) {
                return null;
            }
            return ApiResponse::error('Unauthenticated', 401);
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if (!$request->is('api/*')) {
                return null;
            }
            return ApiResponse::error('Resource not found', 404);
        });

        if (false) {
            // Save errors in db and return error_id to user
            $exceptions->render(function (Throwable $e, $request) {

                if (!$request->is('api/*')) {
                    return null;
                }

                $errorId = 'ERR-' . strtoupper(str()->random(10));
                try {

                    ApplicationError::create([
                        'error_id' => $errorId,
                        'user_id' => auth()->id() ?? null,
                        'message' => $e->getMessage(),
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'url' => $request->fullUrl(),
                        'method' => $request->method(),
                        'ip_address' => $request->ip(),
                        'request_data' => $request->all(),
                        'trace' => $e->getTraceAsString(),

                        'status' => ApplicationError::STATUS_PENDING,
                    ]);

                } catch (Throwable $logException) {

                    report($logException);
                }

                report($e);

                return ApiResponse::error(
                    'Something went wrong',
                    500,
                    [
                        'error_id' => $errorId,
                    ]
                );
            });

        }

    })->create();
