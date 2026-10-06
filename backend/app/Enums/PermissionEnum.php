<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case USUARIOS_VISUALIZAR = 'usuarios.visualizar';
    case USUARIOS_CRIAR = 'usuarios.criar';
    case USUARIOS_EDITAR = 'usuarios.editar';
    case USUARIOS_EXCLUIR = 'usuarios.excluir';

    case PERFIS_VISUALIZAR = 'perfis.visualizar';
    case PERFIS_CRIAR = 'perfis.criar';
    case PERFIS_EDITAR = 'perfis.editar';
    case PERFIS_EXCLUIR = 'perfis.excluir';

    case PERMISSOES_VISUALIZAR = 'permissoes.visualizar';
    case PERMISSOES_CRIAR = 'permissoes.criar';
    case PERMISSOES_EDITAR = 'permissoes.editar';
    case PERMISSOES_EXCLUIR = 'permissoes.excluir';


    case CONFIGURACOES_VISUALIZAR = 'configuracoes.visualizar';
    case CONFIGURACOES_EDITAR = 'configuracoes.editar';

    case AUDITORIA_VISUALIZAR = 'auditoria.visualizar';
}
