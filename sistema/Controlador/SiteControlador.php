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
        $cardapioBebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $cardapioLanche = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $pedidos = (new ComandaModelo())->ler("lanches", "mesa", "DESC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $bebidas = (new ComandaModelo())->ler("bebidas", "nome_bebida", "DESC");
        $total = (new ComandaModelo())->ler("total", "total", "DESC");

        echo ($this->template->renderizar('comanda.html', [
            'titulo' => 'Sistema_Food',
            'cardapioBebida' => $cardapioBebida,
            'cardapioLanche' => $cardapioLanche,
            'pedidos' => $pedidos,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'adicional' => $adicional,
            'bebidas' => $bebidas,
            'total' => $total
        ]));
    }


    public function adicionar(): void
    {
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $ingred = (new ComandaModelo())->ler("lanche_ingredientes", "lanche_id", "DESC" );
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $pedido = (new ComandaModelo())->ler("lanches", "id_lanche", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $mesa = (new ComandaModelo())->ler("lanches", "mesa", "DESC");

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
        $pedidos = (new ComandaModelo())->ler("lanches", "data_hora", "DESC");
        $bebidas = (new ComandaModelo())->ler("bebidas", "nome_bebida", "DESC");
        $total = (new ComandaModelo())->ler("total", "total", "DESC");

        echo ($this->template->renderizar('pedidosAbertos.html', [
            'titulo' => 'pedidos_abertos',
            'adicional' => $adicional,
            'pedidos' => $pedidos,
            'bebidas' => $bebidas,
            'total' => $total
        ]));
    }

    public function adicionarNaMesa(int $mesa): void
    {
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $ingred = (new ComandaModelo())->ler("lanche_ingredientes", "lanche_id", "DESC" );
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $pedido = (new ComandaModelo())->ler("lanches", "id_lanche", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $mesa = (new ComandaModelo())->buscaPorMesa("total", $mesa);

        echo ($this->template->renderizar('adicionarNaMesa.html', [
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


    public function editarLanche(int $id): void
    {
        $pedidoMesa = (new ComandaModelo())->buscaPorId("lanches", $id);
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $mesa = (new ComandaModelo())->ler("lanches", "mesa", "DESC");
        $total = (new ComandaModelo())->ler("total", "total", "DESC");

        echo ($this->template->renderizar('editarLanche.html', [
            'titulo' => 'editar_lanche',
            'editar' => $pedidoMesa,
            'cardapio' => $cardapio,
            'mesa' => $mesa,
            'total' => $total
        ]));
    }


    public function editarAdicional(int $chave): void
    {
        $adicional = (new ComandaModelo())->buscaPorChave("adicionais", $chave );
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $total = (new ComandaModelo())->ler("total", "total", "DESC");

        echo ($this->template->renderizar('editarAdicional.html', [
            'titulo' => 'editar_adicional',
            'editar' => $adicional,
            'ingredientes' => $ingredi,
            'total' => $total
        ]));
    }


    public function editarBebida(int $chave): void
    {
        $bebida = (new ComandaModelo())->buscaPorChave("bebidas", $chave );
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $mesa = (new ComandaModelo())->ler("lanches", "mesa", "DESC");
        $total = (new ComandaModelo())->ler("total", "total", "DESC");

        echo ($this->template->renderizar('editarBebida.html', [
            'titulo' => 'editar_bebida',
            'editar' => $bebida,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida,
            'mesa' => $mesa,
            'total' => $total
        ]));
    }
    

    public function busca(int $id)
    {
        $buscaId = (new ComandaModelo())->buscaPorId("cardapio_lanche", $id);
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "DESC");
        $ingred = (new ComandaModelo())->ler("ingredientes", "ingrediente", "DESC");
        $lanche_ingred = (new ComandaModelo())->lerRelacao("lanche_ingredientes", "lanche_id", $id);

        [
            'busca' => $buscaId,
            'cardapio' => $cardapio,
            'ingredientes' => $ingred,
            'lanche_ingredi' => $lanche_ingred
        ];
    }
}
