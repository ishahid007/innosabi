<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->prependToGroup('api', \App\Http\Middleware\AlwaysAcceptJson::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json(['message' => 'Object not found'], 404);
        });

        // Added for MethodNotAllowedHttpException exception
        $exceptions->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json(['message' => 'Method not allowed'], 405);
        });

        // Added for all other exceptions
        $exceptions->renderable(function (Throwable $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        });

    })->create();
