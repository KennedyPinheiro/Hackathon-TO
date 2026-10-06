<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\AdminChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => UserResource::collection(
                $this->service->listar()
            ),
        ]);
    }

    public function store(
        StoreUserRequest $request
    ): JsonResponse {
        $user = $this->service->criar(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Usuário criado com sucesso.',
            'data' => new UserResource($user),
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource(
                $this->service->buscar($user)
            ),
        ]);
    }

    public function update(
        UpdateUserRequest $request,
        User $user
    ): JsonResponse {
        $user = $this->service->atualizar(
            $user,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Usuário atualizado com sucesso.',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->excluir($user);
        return response()->json([
            'success' => true,
            'message' => 'Usuário excluído com sucesso.',
        ]);
    }

    public function alterarSenha(
        AdminChangePasswordRequest $request,
        User $user
    ): JsonResponse {
        $this->service->alterarSenha(
            $user,
            $request->validated('password')
        );

        return response()->json([
            'success' => true,
            'message' => 'Senha do usuário alterada com sucesso.',
        ]);
    }
}
