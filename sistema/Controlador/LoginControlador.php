<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Modelo\HelpersModelo;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;
use sistema\Controlador\UsuarioControlador;

class LoginControlador extends Controlador
{
    protected $user;

    public function __construct()
    {
        parent::__construct('templates\comanda\views');
    }

    public function login(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            if (isset($dados)) {
                if ($this->checarDados($dados, $usuario)) {

                    $this->user = UsuarioControlador::usuario();

                    if ($this->user){
                        $this->mensagem->sucesso("Seja bem vindo ao PedeFácil, {$usuario->nome}.")->flash();

                        Helpers::redirecionar('comanda');     
                        return;    
                    }
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
            $this->mensagem->erro("Dados inválidos!")->flash();
            return false;
        }
        if (!Helpers::checarSenha($senha, $usuario->senha)) {
            $this->mensagem->erro("Dados inválidos!")->flash();
            return false;
        }
        if($usuario->status !== 1){
            $this->mensagem->erro("Ative sua conta para fazer login!")->flash();
            return false;
        }

        (new Sessao())->criar('usuarioId', $usuario->id);
        
        return true;
    }

    public function logout(): void
    {
        $this->user = UsuarioControlador::usuario();
        $sessao = new Sessao();

        $sessao->limpar('usuarioId');

        $this->mensagem->sucesso("Você deslogou do PedeFácil!")->flash();
        Helpers::redirecionar('login');     

    }
    
}