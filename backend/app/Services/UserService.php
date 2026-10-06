<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly AuditService $auditService
    ) {}

    public function listar(array $filtros = [])
    {
        return User::query()
            ->with(['roles', 'permissions'])
            ->when(
                $filtros['search'] ?? null,
                function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            )
            ->latest()
            ->paginate(
                $filtros['per_page'] ?? 20
            );
    }

    public function buscar(User $user): User
    {
        return $user->load([
            'roles',
            'permissions',
        ]);
    }

    public function criar(array $dados): User
    {
        return DB::transaction(function () use ($dados) {
            $role = $dados['role'] ?? null;

            unset($dados['role']);

            $user = User::create([
                'name' => $dados['name'],
                'email' => $dados['email'],
                'password' => Hash::make($dados['password']),
            ]);

            if ($role) {
                $user->assignRole($role);
            }

            $user->load([
                'roles',
                'permissions',
            ]);

            $this->auditService->registrar(
                action: 'created',
                model: $user,
                newValues: [
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles
                        ->pluck('name')
                        ->values()
                        ->all(),
                ],
            );

            return $user;
        });
    }

    public function atualizar(
        User $user,
        array $dados
    ): User {
        return DB::transaction(function () use ($user, $dados) {
            $oldValues = [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles
                    ->pluck('name')
                    ->values()
                    ->all(),
            ];

            $role = $dados['role'] ?? null;

            unset($dados['role']);

            if (isset($dados['password'])) {
                $dados['password'] = Hash::make(
                    $dados['password']
                );
            } else {
                unset($dados['password']);
            }

            $user->update($dados);

            if ($role !== null) {
                $user->syncRoles([$role]);
            }

            $user->load(['roles', 'permissions',]);

            $newValues = [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles
                    ->pluck('name')
                    ->values()
                    ->all(),
            ];

            $this->auditService->registrar(
                action: 'updated',
                model: $user,
                oldValues: $oldValues,
                newValues: $newValues,
            );

            return $user;
        });
    }

    public function excluir(User $user): void
    {
        DB::transaction(function () use ($user) {
            $oldValues = [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles
                    ->pluck('name')
                    ->values()
                    ->all(),
            ];

            $this->auditService->registrar(
                action: 'deleted',
                model: $user,
                oldValues: $oldValues,
            );

            $user->delete();
        });
    }

    public function alterarSenha(
        User $user,
        string $password
    ): void {
        DB::transaction(function () use ($user, $password) {
            $user->update([
                'password' => Hash::make($password),
            ]);

            $this->auditService->registrar(
                action: 'password_changed',
                model: $user,
            );
        });
    }
}
