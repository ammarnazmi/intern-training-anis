<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Onpay\Core\Http\Middleware\ConfigureSession;
use Onpay\Core\Http\Middleware\CustomHeaders;
use Onpay\Core\Http\Middleware\TrackLastOnline;
use Onpay\Core\Support\MiddlewarePriorityGenerator;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::name('api.')
                ->prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::name('admin.')
                ->prefix('admin')
                ->middleware([
                    'web',
                    ConfigureSession::using(['path' => '/admin', 'cookie' => config('session.cookie') . '_admin']),
                    TrackLastOnline::class,
                ])
                ->group(base_path('routes/admin.php'));

            Route::name('user.')
                ->prefix('user')
                ->middleware([
                    'web',
                    ConfigureSession::using(['path' => '/user', 'cookie' => config('session.cookie') . '_user']),
                    TrackLastOnline::class,
                ])
                ->group(base_path('routes/user.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware
            ->prepend(CustomHeaders::class)
            ->throttleApi()
            ->prependToGroup('web', HandlePrecognitiveRequests::class)
            ->priority((new MiddlewarePriorityGenerator(app(Kernel::class)->getMiddlewarePriority()))
                ->prepend(ConfigureSession::class)
                ->append(TrackLastOnline::class)
                ->get())
            ->redirectTo(
                static fn (Request $request) => Route::has($name = $request->segment(1) . '.login') ? route($name) : abort(401),
                static fn (Request $request) => Route::has($name = $request->segment(1) . '.main') ? route($name) : abort(404),
            )
            ->encryptCookies([
                'sidebar_collapsed',
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(
            static fn (Request $request) => $request->expectsJson() || $request->is('api/*')
        );
    })->create();
