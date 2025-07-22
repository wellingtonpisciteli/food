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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if(isset($dados)){
                if($this->checarDados($dados)){
                    $this->mensagem->sucesso("Dados válidos")->flash();
                }
            }
        }

        echo ($this->template->renderizar('login/login.html', [
            'titulo' => 'Login'
        ]));
    }

    public function checarDados(array $dados):bool
    {
        if(empty($dados['email'])){
            $this->mensagem->erro("Campo E-MAIL é obrigatório!")->flash();
            return false;
        }
        if(empty($dados['senha'])){
            $this->mensagem->erro("Campo SENHA é obrigatório!")->flash();
            return false;
        }
        return true;
    }
}