<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\AdminModelo;
use sistema\Controlador\UsuarioControlador;
use sistema\Modelo\HelpersModelo;
use sistema\Nucleo\Sessao;

/**
 * Controlador para a seção pública do site.
 * Responsável por manipular as requisições relacionadas às páginas do site acessíveis ao público.
 * 
 * @author Wellington Borges
 */
class AdminControlador extends Controlador
{
    protected $usuario;

    public function __construct()
    {
        parent::__construct('templates\comanda\views');

        $this->usuario = UsuarioControlador::usuario();

        if (!$this->usuario){
            $this->mensagem->erro("Faça login para ter acesso ao sistema!")->flash();

            $sessao = new Sessao();
            $sessao->limpar('usuarioId');

            Helpers::redirecionar('login');
        }
    }

    public function novoItem(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(!empty($dados['lanche']) && $this->usuario->level == 3){
                (new AdminModelo())->cadastrarLanche($dados);
            }

            if(!empty($dados['adicional']) && $this->usuario->level == 3){
                (new AdminModelo())->cadastrarAdicional($dados);
            }

            if(!empty($dados['bebida']) && $this->usuario->level == 3){
                (new AdminModelo())->cadastrarBebida($dados);
            }
        
        }

        if ($this->usuario->level == 3){
            $this->mensagem->sucesso('NOVO ITEM CADASTRADO COM SUCESSO!')->flash();
        }else{
            $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
        }
        Helpers::redirecionar('cadastrarItem');
    }

    public function novoUsuario(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $email = $dados['email'];
            $verificaEmail = (new HelpersModelo())->buscaPorEmail($email);

            if(!empty($dados['usuario']) && $this->usuario->level == 3){
                if (!$verificaEmail){
                    (new AdminModelo())->cadastrarUsuario($dados);
                }
            }
        }

        if ($this->usuario->level == 3 && !$verificaEmail){
            $this->mensagem->sucesso('NOVO USUÁRIO CADASTRADO COM SUCESSO!')->flash();
        }else if($this->usuario->level == 3 && $verificaEmail){
            $this->mensagem->erro('E-MAIL JÁ EXISTENTE!')->flash();
        }else{
            $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
        }
        Helpers::redirecionar('cadastrarUsuario');
    }
    

    public function editarItem(int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if ((!empty($dados['lanche']) || !empty($dados['valor'])) && $this->usuario->level == 3 ) {
                $valorNumerico = floatval(
                    str_replace(',', '.', preg_replace('/[^\d,]/', '', $dados['valor']))
                );

                $dadosLanche = [
                    'lanche' => $dados['lanche'],
                    'valor' => $valorNumerico
                ];
    
                (new AdminModelo())->editarLanche($dadosLanche, $id);            
            }

            if (!empty($dados['ingredientes']) && $this->usuario->level == 3) {

                $ingredientes = $dados['ingredientes'];
    
                (new AdminModelo())->editarIngredientes($ingredientes, $id);
            }

            if (!empty($dados['bebida']) && $this->usuario->level == 3) {

                $bebida = $dados['bebida'];
    
                (new AdminModelo())->editarBebida($bebida, $id);
            }

            if ((!empty($dados['tamanhoBebida']) || !empty($dados['valorBebida'])) && $this->usuario->level == 3) {

                $tamanho = $dados['tamanhoBebida'];
                $valor = floatval(
                    str_replace(',', '.', preg_replace('/[^\d,]/', '', $dados['valorBebida']))
                );
                $controle = $dados['controleBebida'];
    
                (new AdminModelo())->editarTamanhoBebida($tamanho,  $valor, $controle);
            }

            if ((!empty($dados['adicional']) || !empty($dados['valorAdicional'])) && $this->usuario->level == 3) {

                $adicional = $dados['adicional'];
                $valorAdicional = floatval(
                    str_replace(',', '.', preg_replace('/[^\d,]/', '', $dados['valorAdicional']))
                );
    
                (new AdminModelo())->editarAdicional($adicional,  $valorAdicional, $id);
            }
                                
        }
        
        if ($this->usuario->level == 3){
            if($dados['lanche']){
                $this->mensagem->informa('LANCHE EDITADO COM SUCESSO!')->flash();
                Helpers::redirecionar('editarLanches');
            }else if($dados['bebida']){
                $this->mensagem->informa('BEBIDA EDITADA COM SUCESSO!')->flash();
                Helpers::redirecionar('editarBebidas');
            }else{
                $this->mensagem->informa('ADICIONAL EDITADO COM SUCESSO!')->flash();
                Helpers::redirecionar('editarAdicionais');
            }
        }else{
            if($dados['lanche']){
                $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
                Helpers::redirecionar('editarLanches');
            }else if($dados['bebida']){
                $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
                Helpers::redirecionar('editarBebidas');
            }else{
                $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
                Helpers::redirecionar('editarAdicionais');
            }
        }
        
    }

    public function editarUsuario(int $id): void
    {
        $emailDuplicado = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $data_atual = Helpers::dataAtual('Y-m-d H:i:s');
            $email = $dados['email'];

            $usuarioComMesmoEmail = (new HelpersModelo())->buscaPorEmail($email);

            // Verifica se o e-mail é duplicado (pertence a outro usuário)
            $emailDuplicado = $usuarioComMesmoEmail && $usuarioComMesmoEmail->id != $id;

            if (!empty($dados['nome']) && $this->usuario->level == 3) {
                $dadosUsuario = [
                    'level' => $dados['level'],
                    'nome' => $dados['nome'],
                    'email' => $dados['email'],
                    'senha' => $dados['senha'],
                    'status' => $dados['status'],
                    'atualizado_em' => $data_atual
                ];

                if (!$emailDuplicado) {
                    (new AdminModelo())->editarUsuario($dadosUsuario, $id);
                }
            }
        }

        // Mensagens
        if ($this->usuario->level == 3 && !$emailDuplicado) {
            $this->mensagem->sucesso('USUÁRIO EDITADO COM SUCESSO!')->flash();
        } else if ($this->usuario->level == 3 && $emailDuplicado) {
            $this->mensagem->erro('E-MAIL JÁ EXISTENTE!')->flash();
        } else {
            $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
        }

        Helpers::redirecionar('editarUsuarios');
    }

    public function excluirItem(int $id, int $controle)
    {
        if ($this->usuario->level == 3){
            if($controle == 1){
                (new AdminModelo())->excluirAdicional($id);
            }else if($controle == 2){
                (new AdminModelo())->excluirBebida($id);
            }else{
                (new AdminModelo())->excluirLanche($id);
            }
        }

        if ($this->usuario->level == 3){
            if($controle == 1){
                $this->mensagem->alerta('ADICIONAL EXCLUÍDO COM SUCESSO!')->flash();
                Helpers::redirecionar('editarAdicionais');
            }else if($controle == 2){
                $this->mensagem->alerta('BEBIDA EXCLUÍDA COM SUCESSO!')->flash();
                Helpers::redirecionar('editarBebidas');
            }else{
                $this->mensagem->alerta('LANCHE EXCLUÍDO COM SUCESSO!')->flash();
                Helpers::redirecionar('editarLanches');
            }
        }else{
            if($controle == 1){
                $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
                Helpers::redirecionar('editarAdicionais');
            }else if($controle == 2){
                $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
                Helpers::redirecionar('editarBebidas');
            }else{
                $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
                Helpers::redirecionar('editarLanches');
            }
        }
        
    }

    public function excluirUsuario(int $id)
    {
        if ($this->usuario->level == 3){
            (new AdminModelo())->excluirUsuarios($id);
        }
        
        if ($this->usuario->level == 3){
            $this->mensagem->alerta('USUÁRIO EXCLUÍDO COM SUCESSO!')->flash();
        }else{
            $this->mensagem->erro('APENAS ADMINISTRADORES PODEM FAZER ALTERAÇÕES!')->flash();
        }
        Helpers::redirecionar('editarUsuarios');
    }


}
