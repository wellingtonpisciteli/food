<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Modelo\LoginModelo;
use sistema\Modelo\HelpersModelo;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;

class UsuarioControlador extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates\comanda\views');
    }

    public static function usuario()
    {
        $sessao = new Sessao();
        if (!$sessao->checar('usuarioId')){
            return null;
        }

        $id = $sessao->usuarioId;

        return (new HelpersModelo())->buscaFetch('usuarios', 'id', $id);
    }
}