<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $service
    ) {}

    public function login(LoginRequest $request)
    {
        $resultado = $this->service->login(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso.',
            'data' => [
                'user' => new UserResource($resultado['user']),
                'token' => $resultado['token'],
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $this->service->logout(
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso.',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load(
            'roles',
            'permissions'
        );

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }
}
