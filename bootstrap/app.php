<?php

use App\Http\Middleware\EnsureUserRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::middleware('web')
                ->prefix('user')
                ->name('user.')
                ->group(base_path('routes/user.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'user' => EnsureUserRole::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request): string {
            return $request->is('user', 'user/*')
                ? route('user.login')
                : '/admin/login';
        });

        $middleware->redirectUsersTo(function (Request $request): string {
            $user = $request->user();

            return $user?->isSuperAdmin() ? '/admin' : route('user.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $message = 'The upload is too large. Use up to 5 files, 10MB each, and avoid pasting images into the description.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            $redirect = $request->headers->get('referer') ?: (
                $request->is('user', 'user/*') ? route('user.tasks.index') : url('/')
            );

            return redirect($redirect)->with('error', $message);
        });
    })->create();
