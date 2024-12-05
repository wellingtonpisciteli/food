<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\ComandaModelo;
use sistema\Nucleo\Conexao;

/**
 * Controlador para a seção pública do site.
 * Responsável por manipular as requisições relacionadas às páginas do site acessíveis ao público.
 * 
 * @author Wellington Borges
 */
class SiteControlador extends Controlador{
    /**
     * Construtor da classe.
     * Define o diretório base para os arquivos de visualização do site.
     *
     * @param string $diretorio_visualizacoes O caminho para o diretório contendo os arquivos de visualização.
     */
    public function __construct()
    {
        parent::__construct('templates\comanda\views');
    }

    public function comanda():void
    {
        $cardapioBebida=(new ComandaModelo())->ler("marcas_bebida", "marca");
        $cardapioLanche=(new ComandaModelo())->ler("cardapio_lanche", "lanche");

        echo($this->template->renderizar('comanda.html', [
            'titulo'=>'Sistema_Food',
            'cardapioBebida'=>$cardapioBebida,
            'cardapioLanche'=>$cardapioLanche
        ]));
    }

    public function adicionar():void
    {
        $cardapio=(new ComandaModelo())->ler("cardapio_lanche", "lanche");
        $ingred=(new ComandaModelo())->ler("lanche_ingredientes", "lanche_id");
        $cardapio_bebida=(new ComandaModelo())->ler("marcas_bebida", "marca");
        $tamanho_bebida=(new ComandaModelo())->ler("tamanho_bebida", "tamanho");
        $pedido=(new ComandaModelo())->ler("pedidos", "nome_lanche");

        echo($this->template->renderizar('adicionar.html', [
            'titulo'=>'Adicionar',
            'cardapio'=>$cardapio,
            'cardapio_bebida'=>$cardapio_bebida,
            'tamanhoBebida'=>$tamanho_bebida,
            'ingred'=>$ingred,
            'pedido'=>$pedido
        ]));
    }

    public function busca(int $id)
    {
        $buscaId=(new ComandaModelo())->buscaPorId("cardapio_lanche", $id);
        $cardapio=(new ComandaModelo())->ler("cardapio_lanche", "lanche");
        $ingred=(new ComandaModelo())->ler("ingredientes", "ingrediente");
        $lanche_ingred=(new ComandaModelo())->lerRelacao("lanche_ingredientes", "lanche_id", $id);

        echo($this->template->renderizar('modal.html', [
            'busca'=>$buscaId,
            'cardapio'=>$cardapio,
            'ingredientes'=>$ingred,
            'lanche_ingredi'=>$lanche_ingred
        ]));

    } 

    public function cadastrar():void{
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $dados=filter_input_array(INPUT_POST, FILTER_DEFAULT);
            (new ComandaModelo())->armazenarPedido($dados);
        }
        
        Helpers::redirecionar('adicionar');
    }
    
}