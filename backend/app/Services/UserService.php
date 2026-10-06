<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function listar()
    {
        return User::query()
            ->with([
                'roles',
                'permissions',
            ])
            ->latest()
            ->get();
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
}
