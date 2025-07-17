<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\ComandaModelo;
use sistema\Nucleo\Conexao;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class LoginControlador extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates\comanda\views');
    }

    public function login(): void
    {
        echo ($this->template->renderizar('login/login.html', [
            'titulo' => 'Login'
        ]));
    }
}