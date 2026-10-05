<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case USUARIOS_VISUALIZAR = 'usuarios.visualizar';
    case USUARIOS_CRIAR = 'usuarios.criar';
    case USUARIOS_EDITAR = 'usuarios.editar';
    case USUARIOS_EXCLUIR = 'usuarios.excluir';

    case EVENTOS_VISUALIZAR = 'eventos.visualizar';
    case EVENTOS_CRIAR = 'eventos.criar';
    case EVENTOS_EDITAR = 'eventos.editar';
    case EVENTOS_EXCLUIR = 'eventos.excluir';

    case INSCRICOES_VISUALIZAR = 'inscricoes.visualizar';
    case INSCRICOES_CRIAR = 'inscricoes.criar';
    case INSCRICOES_EDITAR = 'inscricoes.editar';
    case INSCRICOES_EXCLUIR = 'inscricoes.excluir';
}
