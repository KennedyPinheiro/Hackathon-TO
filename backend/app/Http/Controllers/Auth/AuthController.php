<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Services\ResponseService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $service,
        private readonly UserService $userService
    ) {}

    public function login(LoginRequest $request)
    {
        $resultado = $this->service->login(
            $request->validated()
        );


        return ResponseService::success(
            data: [
                'user' => new UserResource($resultado['user']),
                'token' => $resultado['token'],
            ],
            message: 'Login realizado com sucesso.',
            status: 201
        );
    }

    public function logout(Request $request)
    {
        $this->service->logout(
            $request->user()
        );

        return ResponseService::success(
            data: null,
            message: 'Logout realizado com sucesso.',
            status: 200
        );
    }

    public function me(Request $request)
    {
        $user = $request->user()->load(
            'roles',
            'permissions'
        );

        return ResponseService::success(

            data: new UserResource($user),
            message: null,
            status: 200
        );
    }
    public function alterarSenha(ChangePasswordRequest $request)
    {
        $this->userService->alterarSenha(
            $request->user(),
            $request->validated('password')
        );

        return ResponseService::success(
            data: null,
            message: 'Senha do usuário alterada com sucesso.',
            status: 200
        );
    }
}
