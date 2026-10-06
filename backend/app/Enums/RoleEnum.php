<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case GESTOR = 'gestor';
    case USUARIO = 'usuario';
}
