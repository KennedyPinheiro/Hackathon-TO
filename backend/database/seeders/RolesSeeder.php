<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

        $organizador = Role::firstOrCreate([
            'name' => RoleEnum::ORGANIZADOR->value,
            'guard_name' => 'api',
        ]);

        $participante = Role::firstOrCreate([
            'name' => RoleEnum::PARTICIPANTE->value,
            'guard_name' => 'api',
        ]);

        /*
         * ADMIN
         * Recebe todas as permissões.
         */
        $admin->syncPermissions($permissions);

        /*
         * ORGANIZADOR
         */
        $organizador->syncPermissions([
            PermissionEnum::EVENTOS_VISUALIZAR->value,
            PermissionEnum::EVENTOS_CRIAR->value,
            PermissionEnum::EVENTOS_EDITAR->value,

            PermissionEnum::INSCRICOES_VISUALIZAR->value,
        ]);

        /*
         * PARTICIPANTE
         */
        $participante->syncPermissions([
            PermissionEnum::EVENTOS_VISUALIZAR->value,

            PermissionEnum::INSCRICOES_VISUALIZAR->value,
            PermissionEnum::INSCRICOES_CRIAR->value,
        ]);
    }
}
