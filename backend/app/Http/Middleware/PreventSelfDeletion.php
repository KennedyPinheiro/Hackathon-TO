<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventSelfDeletion
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();
        $userToDelete = $request->route('user');

        if (
            $user &&
            $userToDelete &&
            $user->id === $userToDelete->id &&
            $user->hasRole('admin')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'O administrador não pode excluir a própria conta.',
            ], 403);
        }

        return $next($request);
    }
}
