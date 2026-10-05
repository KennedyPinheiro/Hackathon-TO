<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case ORGANIZADOR = 'organizador';
    case PARTICIPANTE = 'participante';
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::ORGANIZADOR => 'organizador',
            self::PARTICIPANTE => 'participante',
        };
    }
}
