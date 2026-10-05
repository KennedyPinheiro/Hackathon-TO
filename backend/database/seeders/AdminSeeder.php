<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@hackathon.local',
            ],
            [
                'name' => 'Administrador',
                'password' => 'password',
            ]
        );

        $admin->syncRoles([
            RoleEnum::ADMIN->value,
        ]);
    }
}
