<?php

use App\Helpers\ResponseHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Middleware\DataValidation;
use App\Http\Middleware\UserAccessibility;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(
            fn (Request $request): ?string => $request->is('api/*') ? null : route('login')
        );

        $middleware->api(append: [

            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,

            DataValidation::class,

        ]);

        $middleware->prependToPriorityList(
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            UserAccessibility::class
        );

        $middleware->appendToPriorityList(
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            DataValidation::class
        );

        $middleware->alias([

            'admin.access' => UserAccessibility::class,

        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, \Throwable $e): bool => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {

            if ($request->is('api/*')) {
                return ResponseHelper::error(
                    null,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return ResponseHelper::error(
                    null,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }
        });

        $exceptions->render(function (
            AuthenticationException $e,
            $request
        ) {
            if ($request->is('api/*')) {
                return ResponseHelper::error(
                    [
                        'en' => 'Authentication required.',
                        'ar' => 'المصادقة مطلوبة.',
                    ],
                    (object) ['code' => 'UNAUTHENTICATED'],
                    Response::HTTP_UNAUTHORIZED
                );
            }
        });

    })->create();
