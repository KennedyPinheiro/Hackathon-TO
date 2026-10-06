<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {

        $permissions = [];

        foreach (PermissionEnum::cases() as $permission) {
            $permissions[] = Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => 'api',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => RoleEnum::ADMIN->value,
            'guard_name' => 'api',
        ]);

        $gestor = Role::firstOrCreate([
            'name' => RoleEnum::GESTOR->value,
            'guard_name' => 'api',
        ]);

        $usuario = Role::firstOrCreate([
            'name' => RoleEnum::USUARIO->value,
            'guard_name' => 'api',
        ]);


        $admin->syncPermissions($permissions);


        $gestor->syncPermissions([
            PermissionEnum::USUARIOS_VISUALIZAR->value,
            PermissionEnum::USUARIOS_CRIAR->value,
            PermissionEnum::USUARIOS_EDITAR->value,

            PermissionEnum::PERFIS_VISUALIZAR->value,
            PermissionEnum::PERFIS_CRIAR->value,
            PermissionEnum::PERFIS_EDITAR->value,

            PermissionEnum::PERMISSOES_VISUALIZAR->value,

            PermissionEnum::CONFIGURACOES_VISUALIZAR->value,
            PermissionEnum::CONFIGURACOES_EDITAR->value,
        ]);


        $usuario->syncPermissions([
            PermissionEnum::USUARIOS_VISUALIZAR->value,
            PermissionEnum::PERFIL_PROPRIO_EDITAR->value,
        ]);
    }
}
