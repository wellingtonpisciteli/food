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
class SiteControlador extends Controlador
{
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

    public function comanda(): void
    {
        $cardapioBebida = (new ComandaModelo())->ler("marcas_bebida", "marca");
        $cardapioLanche = (new ComandaModelo())->ler("cardapio_lanche", "lanche");
        $pedidos = (new ComandaModelo())->ler("pedidos", "data_hora");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente");
        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $bebidas = (new ComandaModelo())->ler("bebidas", "nome_bebida");

        echo ($this->template->renderizar('comanda.html', [
            'titulo' => 'Sistema_Food',
            'cardapioBebida' => $cardapioBebida,
            'cardapioLanche' => $cardapioLanche,
            'pedidos' => $pedidos,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'adicional' => $adicional,
            'bebidas' => $bebidas
        ]));
    }

    public function adicionar(): void
    {
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche");
        $ingred = (new ComandaModelo())->ler("lanche_ingredientes", "lanche_id");
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho");
        $pedido = (new ComandaModelo())->ler("pedidos", "nome_lanche");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente");
        $mesa = (new ComandaModelo())->ler("pedidos", "mesa");

        echo ($this->template->renderizar('adicionar.html', [
            'titulo' => 'Adicionar',
            'cardapio' => $cardapio,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingred,
            'pedido' => $pedido,
            'ingredientes' => $ingredi,
            'mesa' => $mesa
        ]));
    }

    public function pedidosAbertos(): void
    {
        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $pedidos = (new ComandaModelo())->ler("pedidos", "data_hora");
        $bebidas = (new ComandaModelo())->ler("bebidas", "nome_bebida");

        echo ($this->template->renderizar('pedidosAbertos.html', [
            'titulo' => 'pedidos_abertos',
            'adicional' => $adicional,
            'pedidos' => $pedidos,
            'bebidas' => $bebidas
        ]));
    }

    public function editarPedido(int $id): void
    {
        $pedidoMesa = (new ComandaModelo())->buscaPorId("pedidos", $id);
        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche");
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente");
        $mesa = (new ComandaModelo())->ler("pedidos", "mesa");

        echo ($this->template->renderizar('editar.html', [
            'titulo' => 'editar_pedido',
            'editar' => $pedidoMesa,
            'adicional' => $adicional,
            'cardapio' => $cardapio,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida,
            'ingredientes' => $ingredi,
            'mesa' => $mesa
        ]));
    }

    public function editarAdicionais(int $chave): void
    {
        $adicional = (new ComandaModelo())->buscaPorChave("adicionais", $chave );
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente");

        echo ($this->template->renderizar('editarAdicional.html', [
            'titulo' => 'editar_adicional',
            'editar' => $adicional,
            'ingredientes' => $ingredi

        ]));
    }

    public function editarBebidas(int $chave): void
    {
        $bebida = (new ComandaModelo())->buscaPorChave("bebidas", $chave );
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho");

        echo ($this->template->renderizar('editarBebida.html', [
            'titulo' => 'editar_bebida',
            'editar' => $bebida,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida
        ]));
    }

    public function busca(int $id)
    {
        $buscaId = (new ComandaModelo())->buscaPorId("cardapio_lanche", $id);
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche");
        $ingred = (new ComandaModelo())->ler("ingredientes", "ingrediente");
        $lanche_ingred = (new ComandaModelo())->lerRelacao("lanche_ingredientes", "lanche_id", $id);

        [
            'busca' => $buscaId,
            'cardapio' => $cardapio,
            'ingredientes' => $ingred,
            'lanche_ingredi' => $lanche_ingred
        ];
    }
}
