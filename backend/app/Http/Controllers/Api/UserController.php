<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\AdminChangePasswordRequest;
use App\Http\Requests\IndexUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ResponseService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $service
    ) {}

    public function index(IndexUserRequest $request): JsonResponse
    {
        $usuarios = $this->service->listar($request->validated());

        return ResponseService::success(
            data: UserResource::collection($usuarios)
        );
    }

    public function store(
        StoreUserRequest $request
    ): JsonResponse {
        $user = $this->service->criar(
            $request->validated()
        );

        return ResponseService::success(
            data: new UserResource($user),
            message: 'Usuário criado com sucesso.',
            status: 201
        );
    }

    public function show(User $user): JsonResponse
    {


        return ResponseService::success(
            data: new UserResource($this->service->buscar($user)),
            message: '',
            status: 200
        );
    }

    public function update(
        UpdateUserRequest $request,
        User $user
    ): JsonResponse {
        $user = $this->service->atualizar($user, $request->validated());

        return ResponseService::success(
            data: new UserResource($user),
            message: 'Usuário atualizado com sucesso.',
            status: 200
        );
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->excluir($user);

        return ResponseService::success(
            data: null,
            message: 'Usuário excluído com sucesso.',
            status: 200
        );
    }

    public function alterarSenha(
        AdminChangePasswordRequest $request,
        User $user
    ): JsonResponse {
        $this->service->alterarSenha($user, $request->validated('password'));

        return ResponseService::success(
            data: null,
            message: 'Senha do usuário alterada com sucesso.',
            status: 200
        );
    }
}
