<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ChangePasswordRequest;

class UserService
{
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
                $filtros['per_page'] ?? 15
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

            return $user->load([
                'roles',
                'permissions',
            ]);
        });
    }

    public function atualizar(
        User $user,
        array $dados
    ): User {
        return DB::transaction(function () use ($user, $dados) {
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

            return $user->load([
                'roles',
                'permissions',
            ]);
        });
    }

    public function excluir(User $user): void
    {
        $user->delete();
    }
    public function alterarSenha(User $user, string $password): void
    {
        $user->update([
            'password' => Hash::make($password),
        ]);
    }
}
