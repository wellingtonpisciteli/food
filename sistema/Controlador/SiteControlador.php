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
        $dataAtual = (new Helpers())->dataAtual('Y-m-d');
        $cardapioBebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $cardapioLanche = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $pedidos = (new ComandaModelo())->ler("lanches", "data_hora", "DESC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $bebidas = (new ComandaModelo())->ler("bebidas", "nome_bebida", "DESC");
        $total = (new ComandaModelo())->ler("total", "total", "DESC");
        $ingredientes = (new ComandaModelo())->ler("lanche_ingredientes", "ingredientes", "ASC");
        $lancheMaisVendido = (new ComandaModelo())->maisVendido('lanches', 'nome_lanche', 'DESC', $dataAtual);
        

        echo ($this->template->renderizar('comanda.html', [
            'titulo' => 'Home',
            'cardapioBebida' => $cardapioBebida,
            'cardapioLanche' => $cardapioLanche,
            'pedidos' => $pedidos,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'adicional' => $adicional,
            'bebidas' => $bebidas,
            'ingredientes' => $ingredientes,
            'maisVendido' => $lancheMaisVendido,
            'total' => $total,
        ]));
    }


    public function adicionar(): void
    {
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");    
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
        $mesa = (new ComandaModelo())->ler("lanches", "mesa", "DESC");
        $cardapioLanche = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $ingredientes = (new ComandaModelo())->ler("lanche_ingredientes", "ingredientes", "ASC");
        $cardapioBebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");

        echo ($this->template->renderizar('pedidosAbertos.html', [
            'titulo' => 'Pedidos_abertos',
            'adicional' => $adicional,
            'pedidos' => $pedidos,
            'bebidas' => $bebidas,
            'mesa' => $mesa,
            'cardapioLanche' => $cardapioLanche,
            'ingredientes' => $ingredientes,
            'cardapioBebida' => $cardapioBebida,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'total' => $total
        ]));
    }

    public function pedidosFechados(): void
    {
        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $pedidos = (new ComandaModelo())->ler("lanches", "data_hora", "DESC");
        $bebidas = (new ComandaModelo())->ler("bebidas", "nome_bebida", "DESC");
        $total = (new ComandaModelo())->ler("total", "total", "DESC");
        $mesa = (new ComandaModelo())->ler("lanches", "mesa", "DESC");
        $cardapioLanche = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $ingredientes = (new ComandaModelo())->ler("lanche_ingredientes", "ingredientes", "ASC");
        $cardapioBebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");

        echo ($this->template->renderizar('pedidosFechados.html', [
            'titulo' => 'Pedidos_fechados',
            'adicional' => $adicional,
            'pedidos' => $pedidos,
            'bebidas' => $bebidas,
            'mesa' => $mesa,
            'cardapioLanche' => $cardapioLanche,
            'ingredientes' => $ingredientes,
            'cardapioBebida' => $cardapioBebida,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'total' => $total
        ]));
    }

    public function caixa(int $id_mesa): void
    {
        $lanchesMesa = (new ComandaModelo())->buscaIds_mesa("lanches", $id_mesa);
        $bebidasMesa = (new ComandaModelo())->buscaIds_mesa("bebidas", $id_mesa);
        $totalMesa = (new ComandaModelo())->buscaIds_mesa("total", $id_mesa);
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $pedido = (new ComandaModelo())->ler("lanches", "id_lanche", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");

        echo ($this->template->renderizar('caixa.html', [
            'titulo' => 'Adicionar_na_mesa',
            'cardapio' => $cardapio,
            'cardapio_bebida' => $bebidasMesa,
            'tamanhoBebida' => $tamanho_bebida,
            'pedido' => $pedido,
            'ingredientes' => $ingredi,
            'lanchesMesa' => $lanchesMesa,
            'adicional' => $adicional,
            'totalMesa' => $totalMesa
        ]));
    }

    public function adicionarNaMesa(int $mesa): void
    {
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $pedido = (new ComandaModelo())->ler("lanches", "id_lanche", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $mesa = (new ComandaModelo())->buscaPorMesa("total", $mesa);

        echo ($this->template->renderizar('adicionarNaMesa.html', [
            'titulo' => 'Adicionar_na_mesa',
            'cardapio' => $cardapio,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida,
            'pedido' => $pedido,
            'ingredientes' => $ingredi,
            'mesa' => $mesa
        ]));
    }

    public function adicionarAdicional(int $mesa, int  $id_lanche): void
    {
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $pedido = (new ComandaModelo())->ler("lanches", "id_lanche", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $mesa = (new ComandaModelo())->buscaPorMesa("total", $mesa);
        $idLanche = (new ComandaModelo())->buscaPorId_lanche("lanches", $id_lanche);

        echo ($this->template->renderizar('adicionarAdicional.html', [
            'titulo' => 'Adicionar_adicional',
            'cardapio' => $cardapio,
            'pedido' => $pedido,
            'ingredientes' => $ingredi,
            'mesa' => $mesa,
            'idLanche' => $idLanche
        ]));
    }


    public function editarLanche(int $id): void
    {
        $pedidoMesa = (new ComandaModelo())->buscaPorId("lanches", $id);
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $lanches = (new ComandaModelo())->ler("lanches", "nome_lanche", "ASC");

        echo ($this->template->renderizar('editarLanche.html', [
            'titulo' => 'Editar_lanche',
            'editar' => $pedidoMesa,
            'cardapio' => $cardapio,
            'ingredientes' => $ingredi,
            'lanches' => $lanches
        ]));
    }


    public function editarAdicional(int $chave): void
    {
        $adicional = (new ComandaModelo())->buscaPorChave("adicionais", $chave );
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");

        echo ($this->template->renderizar('editarAdicional.html', [
            'titulo' => 'Editar_adicional',
            'editar' => $adicional,
            'ingredientes' => $ingredi
        ]));
    }


    public function editarBebida(int $chave): void
    {
        $bebida = (new ComandaModelo())->buscaPorChave("bebidas", $chave );
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");

        echo ($this->template->renderizar('editarBebida.html', [
            'titulo' => 'Editar_bebida',
            'editar' => $bebida,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida
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
