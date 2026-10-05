<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'usuarios.visualizar',
            'usuarios.criar',
            'usuarios.editar',
            'usuarios.excluir',

            'eventos.visualizar',
            'eventos.criar',
            'eventos.editar',
            'eventos.excluir',

            'inscricoes.visualizar',
            'inscricoes.criar',
            'inscricoes.editar',
            'inscricoes.excluir',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);

        $organizador = Role::firstOrCreate([
            'name' => 'organizador',
            'guard_name' => 'api',
        ]);

        $participante = Role::firstOrCreate([
            'name' => 'participante',
            'guard_name' => 'api',
        ]);

        $admin->givePermissionTo(
            Permission::where('guard_name', 'api')->get()
        );

        $organizador->givePermissionTo([
            'eventos.visualizar',
            'eventos.criar',
            'eventos.editar',
            'inscricoes.visualizar',
        ]);

        $participante->givePermissionTo([
            'eventos.visualizar',
            'inscricoes.visualizar',
            'inscricoes.criar',
        ]);
    }
}
