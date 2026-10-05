<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case ORGAMNIZADOR = 'organizador';
    case PARTICIPANTE = 'participante';
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::ORGAMNIZADOR => 'organizador',
            self::PARTICIPANTE => 'participante',
        };
    }
}
