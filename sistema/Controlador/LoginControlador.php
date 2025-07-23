<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Modelo\LoginModelo;
use sistema\Modelo\HelpersMode;
use sistema\Modelo\HelpersModelo;
use sistema\Nucleo\Helpers;

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
                $email = $dados['email'];
                $senha = $dados['senha'];
                
                if($this->checarDados($dados)){
                    $obj = (new HelpersModelo())->buscaPorEmail($email);
                    
                    if($obj->email == $email && $obj->senha == $senha){
                        $this->mensagem->sucesso("Seja bem vindo(a) ao PedeFácil, {$obj->nome}")->flash();
                        Helpers::redirecionar('comanda');
                    }else{
                        $this->mensagem->erro("Dados inválidos!")->flash();
                    }
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