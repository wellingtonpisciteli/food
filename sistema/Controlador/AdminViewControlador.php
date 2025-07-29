<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\HelpersModelo;
use sistema\Nucleo\Sessao;

/**
 * Controlador para a seção pública do site.
 * Responsável por manipular as requisições relacionadas às páginas do site acessíveis ao público.
 * 
 * @author Wellington Borges
 */
class AdminViewControlador extends Controlador
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

    public function cadastrarItem(){

        $obj = (new HelpersModelo());

        $cardapioLanche = $obj->ler("cardapio_lanche", "id_ingredi", "ASC");
        $cardapioBebida = $obj->ler("marcas_bebida", "bebida_id", "ASC");

        echo ($this->template->renderizar('admin/cadastrarItem.html', [
            'titulo' => 'Cadastrar',
            'cardapio' => $cardapioLanche,
            'bebidas' => $cardapioBebida
        ]));
    }

    public function cadastrarUsuario(){

        $obj = (new HelpersModelo());

        $cardapioLanche = $obj->ler("cardapio_lanche", "id_ingredi", "ASC");
        $cardapioBebida = $obj->ler("marcas_bebida", "bebida_id", "ASC");

        echo ($this->template->renderizar('admin/cadastrarUsuario.html', [
            'titulo' => 'Cadastrar Usuário',
            'cardapio' => $cardapioLanche,
            'bebidas' => $cardapioBebida
        ]));
    }

    public function editarLanches(){
        $obj = (new HelpersModelo());

        $cardapioLanche = $obj->ler("cardapio_lanche", "id_ingredi", "ASC");
        $ingredientes = $obj->ler("lanche_ingredientes", "lanche_id", "ASC");

        echo ($this->template->renderizar('admin/editarLanches.html', [
            'titulo' => 'Editar Lanches',
            'cardapio' => $cardapioLanche,
            'ingredientes' => $ingredientes
        ]));
    }

    public function editarAdicionais(){

        $adicionais = (new HelpersModelo())->ler("ingredientes", "ingrediente", "ASC");

        echo ($this->template->renderizar('admin/editarAdicionais.html', [
            'titulo' => 'Editar Adicionais',
            'cardapio' => $adicionais
        ]));
    }

    public function editarBebidas(){

        $obj = (new HelpersModelo());  

        $cardapioBebida = $obj->ler("marcas_bebida", "bebida_id", "ASC");
        $tamanhoBebida = $obj->ler("tamanho_bebida", "marca_bebida_id", "ASC");

        echo ($this->template->renderizar('admin/editarBebidas.html', [
            'titulo' => 'Editar Bebidas',
            'cardapio' => $cardapioBebida,
            'tamanhoBebida' => $tamanhoBebida
        ]));
    }

    public function editarUsuarios(){
        $obj = (new HelpersModelo());

        $usuarios = $obj->ler("usuarios", "id", "ASC");

        echo ($this->template->renderizar('admin/editarUsuarios.html', [
            'titulo' => 'Editar Usuarios',
            'usuarios' => $usuarios
        ]));
    }
}
