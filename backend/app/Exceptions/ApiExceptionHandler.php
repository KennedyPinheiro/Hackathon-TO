<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptionHandler
{
    public static function render(\Throwable $exception)
    {
        return match (true) {
            $exception instanceof ValidationException =>
            self::validation($exception),

            $exception instanceof AuthenticationException =>
            self::authentication($exception),

            $exception instanceof AuthorizationException =>
            self::authorization($exception),

            $exception instanceof ModelNotFoundException =>
            self::notFound(),

            $exception instanceof NotFoundHttpException =>
            self::endpointNotFound(),

            default => null,
        };
    }

    private static function validation(
        ValidationException $exception
    ) {
        return response()->json([
            'success' => false,
            'message' => 'Os dados informados são inválidos.',
            'errors' => $exception->errors(),
        ], 422);
    }

    private static function authentication(
        AuthenticationException $exception
    ) {
        return response()->json([
            'success' => false,
            'message' => 'Não autenticado.',
        ], 401);
    }

    private static function authorization(
        AuthorizationException $exception
    ) {
        return response()->json([
            'success' => false,
            'message' => 'Você não possui permissão para realizar esta ação.',
        ], 403);
    }

    private static function notFound()
    {
        return response()->json([
            'success' => false,
            'message' => 'Registro não encontrado.',
        ], 404);
    }

    private static function endpointNotFound()
    {
        return response()->json([
            'success' => false,
            'message' => 'Endpoint não encontrado.',
        ], 404);
    }
}
