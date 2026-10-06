<?php

use App\Http\Middleware\PreventSelfDeletion;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'prevent.self.deletion' => PreventSelfDeletion::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (
            ValidationException $e
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Os dados informados são inválidos.',
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (
            AuthenticationException $e
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Não autenticado.',
            ], 401);
        });
        $exceptions->render(function (
            AuthorizationException $e
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Você não possui permissão para realizar esta ação.',
            ], 403);
        });

        $exceptions->render(function (
            ModelNotFoundException $e
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Registro não encontrado.',
            ], 404);
        });

        $exceptions->render(function (
            NotFoundHttpException $e
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint não encontrado.',
            ], 404);
        });
    })
    ->create();
