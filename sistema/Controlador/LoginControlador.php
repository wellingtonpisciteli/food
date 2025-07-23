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
            $email = $dados['email'];
            
            if (isset($dados)) {
                if ($this->checarDados($dados, $usuario)) {

                    $this->mensagem->sucesso("Seja bem vindo ao PedeFácil, {$usuario->nome}.")->flash();
                    Helpers::redirecionar('comanda');     
                    return;    
                }
            }
        }

        echo ($this->template->renderizar('login/login.html', [
            'titulo' => 'Login'
        ]));
    }

    public function checarDados(array $dados, ?object &$usuario = null):bool
    {
        $email = $dados['email'];
        $senha = $dados['senha'];

        if(empty($dados['email'])){
            $this->mensagem->erro("Campo E-MAIL é obrigatório!")->flash();
            return false;
        }
        if(empty($dados['senha'])){
            $this->mensagem->erro("Campo SENHA é obrigatório!")->flash();
            return false;
        }

        $usuario = (new HelpersModelo())->buscaPorEmail($email);

        if ($usuario === null) {
            $this->mensagem->erro("E-mail não encontrado!")->flash();
            return false;
        }
        if($usuario->senha !== $senha){
            $this->mensagem->erro("Senha inválida!")->flash();
            return false;
        }
        if($usuario->status !== 1){
            $this->mensagem->erro("Ative sua conta para fazer login!")->flash();
            return false;
        }
        return true;
    }
}