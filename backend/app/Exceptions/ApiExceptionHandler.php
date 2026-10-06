<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\ResponseService;

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
        return ResponseService::error(
            message: 'Os dados informados são inválidos.',
            status: 422,
            errors: $exception->errors()
        );
    }

    private static function authentication(
        AuthenticationException $exception
    ) {
        return ResponseService::error(
            message: 'Não autenticado.',
            status: 401
        );
    }

    private static function authorization(
        AuthorizationException $exception
    ) {

        return ResponseService::error(
            message: 'Você não possui permissão para realizar esta ação.',
            status: 403
        );
    }

    private static function notFound()
    {
        return ResponseService::error(
            message: 'Registro não encontrado.',
            status: 404
        );
    }

    private static function endpointNotFound()
    {
        return ResponseService::error(
            message: 'Endpoint não encontrado.',
            status: 404
        );
    }
}
