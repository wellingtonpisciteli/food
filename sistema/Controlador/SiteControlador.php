<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\ComandaModelo;
use sistema\Nucleo\Conexao;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

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
        $bebidas = (new ComandaModelo())->ler("bebidas", "id", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $mesa = (new ComandaModelo())->ler("lanches", "mesa", "DESC");

        echo ($this->template->renderizar('adicionar.html', [
            'titulo' => 'Adicionar',
            'cardapio' => $cardapio,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida,
            'pedido' => $pedido,
            'bebidas' => $bebidas,
            'ingredientes' => $ingredi,
            'mesa' => $mesa
        ]));
    }


    public function pedidosAbertos(): void
    {

        $obj = new ComandaModelo();

        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $pedidos = $obj->ler("lanches", "data_hora", "DESC");
        $bebidas = $obj->ler("bebidas", "nome_bebida", "DESC");
        $total = $obj->ler("total", "total", "DESC");
        $mesa = $obj->ler("lanches", "mesa", "DESC");
        $cardapioLanche = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $ingredientes = $obj->ler("lanche_ingredientes", "ingredientes", "ASC");
        $cardapioBebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");

        echo ($this->template->renderizar('pedidosAbertos.html', [
            'titulo' => 'Mesas Abertas',
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
            'titulo' => 'Mesas Fechadas',
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

    public function entregasAbertas(): void
    {
        $obj = new ComandaModelo();

        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $pedidos = $obj->ler("lanches", "data_hora", "DESC");
        $bebidas = $obj->ler("bebidas", "nome_bebida", "DESC");
        $total = $obj->ler("total", "total", "DESC");
        $mesa = $obj->ler("lanches", "mesa", "DESC");
        $cardapioLanche = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $ingredientes = $obj->ler("lanche_ingredientes", "ingredientes", "ASC");
        $cardapioBebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('entregasAbertas.html', [
            'titulo' => 'Entregas Abertas',
            'adicional' => $adicional,
            'pedidos' => $pedidos,
            'bebidas' => $bebidas,
            'mesa' => $mesa,
            'cardapioLanche' => $cardapioLanche,
            'ingredientes' => $ingredientes,
            'cardapioBebida' => $cardapioBebida,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'entrega_retirada' => $entrega_retirada,
            'total' => $total
        ]));
    }

    public function entregasFechadas(): void
    {
        $obj = new ComandaModelo();

        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $pedidos = $obj->ler("lanches", "data_hora", "DESC");
        $bebidas = $obj->ler("bebidas", "nome_bebida", "DESC");
        $total = $obj->ler("total", "total", "DESC");
        $mesa = $obj->ler("lanches", "mesa", "DESC");
        $cardapioLanche = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $ingredientes = $obj->ler("lanche_ingredientes", "ingredientes", "ASC");
        $cardapioBebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('entregasFechadas.html', [
            'titulo' => 'Entregas Fechadas',
            'adicional' => $adicional,
            'pedidos' => $pedidos,
            'bebidas' => $bebidas,
            'mesa' => $mesa,
            'cardapioLanche' => $cardapioLanche,
            'ingredientes' => $ingredientes,
            'cardapioBebida' => $cardapioBebida,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'entrega_retirada' => $entrega_retirada,
            'total' => $total
        ]));
    }

    public function retiradasAbertas(): void
    {
        $obj = new ComandaModelo();

        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $pedidos = $obj->ler("lanches", "data_hora", "DESC");
        $bebidas = $obj->ler("bebidas", "nome_bebida", "DESC");
        $total = $obj->ler("total", "total", "DESC");
        $mesa = $obj->ler("lanches", "mesa", "DESC");
        $cardapioLanche = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $ingredientes = $obj->ler("lanche_ingredientes", "ingredientes", "ASC");
        $cardapioBebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('retiradasAbertas.html', [
            'titulo' => 'Retiradas Abertas',
            'adicional' => $adicional,
            'pedidos' => $pedidos,
            'bebidas' => $bebidas,
            'mesa' => $mesa,
            'cardapioLanche' => $cardapioLanche,
            'ingredientes' => $ingredientes,
            'cardapioBebida' => $cardapioBebida,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'entrega_retirada' => $entrega_retirada,
            'total' => $total
        ]));
    }

    public function retiradasFechadas(): void
    {
        $obj = new ComandaModelo();

        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $pedidos = $obj->ler("lanches", "data_hora", "DESC");
        $bebidas = $obj->ler("bebidas", "nome_bebida", "DESC");
        $total = $obj->ler("total", "total", "DESC");
        $mesa = $obj->ler("lanches", "mesa", "DESC");
        $cardapioLanche = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $ingredientes = $obj->ler("lanche_ingredientes", "ingredientes", "ASC");
        $cardapioBebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('retiradasFechadas.html', [
            'titulo' => 'Retiradas Fechadas',
            'adicional' => $adicional,
            'pedidos' => $pedidos,
            'bebidas' => $bebidas,
            'mesa' => $mesa,
            'cardapioLanche' => $cardapioLanche,
            'ingredientes' => $ingredientes,
            'cardapioBebida' => $cardapioBebida,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'entrega_retirada' => $entrega_retirada,
            'total' => $total
        ]));
    }

    public function caixa(int $id_mesa): void
    {
        $idMesa = (new ComandaModelo())->buscaId_mesa("total", $id_mesa);
        $lanchesMesa = (new ComandaModelo())->buscaIds_mesa("lanches", $id_mesa);
        $bebidasMesa = (new ComandaModelo())->buscaIds_mesa("bebidas", $id_mesa);
        $totalMesa = (new ComandaModelo())->buscaIds_mesa("total", $id_mesa);
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $pedido = (new ComandaModelo())->ler("lanches", "id_lanche", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $adicional = (new ComandaModelo())->lerAdicional("adicionais", "nome_adicional");
        $entrega_retirada = (new ComandaModelo())->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('caixa.html', [
            'titulo' => 'Caixa',
            'cardapio' => $cardapio,
            'cardapio_bebida' => $bebidasMesa,
            'tamanhoBebida' => $tamanho_bebida,
            'pedido' => $pedido,
            'ingredientes' => $ingredi,
            'lanchesMesa' => $lanchesMesa,
            'adicional' => $adicional,
            'totalMesa' => $totalMesa,
            'id_mesa' => $idMesa,
            'entregas' => $entrega_retirada
        ]));
    }

    public function adicionarNaMesa(int $id_mesa): void
    {
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $pedido = (new ComandaModelo())->ler("lanches", "id_lanche", "ASC");
        $bebidas = (new ComandaModelo())->ler("bebidas", "id", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $mesa = (new ComandaModelo())->buscaIds_mesa("total", $id_mesa);
        $entrega_retirada = (new ComandaModelo())->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('adicionarNaMesa.html', [
            'titulo' => 'Adicionar Novo',
            'cardapio' => $cardapio,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida,
            'pedido' => $pedido,
            'bebidas' => $bebidas,
            'ingredientes' => $ingredi,
            'mesa' => $mesa,
            'entregas' => $entrega_retirada
        ]));
    }

    public function adicionarAdicional(int $id_mesa, int  $id_lanche): void
    {
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $pedido = (new ComandaModelo())->ler("lanches", "id_lanche", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $mesa = (new ComandaModelo())->buscaIds_mesa("total", $id_mesa);
        $idLanche = (new ComandaModelo())->buscaPorId_lanche("lanches", $id_lanche);
        $entrega_retirada = (new ComandaModelo())->ler("entrega_retirada", "id_mesa", "DESC");


        echo ($this->template->renderizar('adicionarAdicional.html', [
            'titulo' => 'Adicionar Adicional',
            'cardapio' => $cardapio,
            'pedido' => $pedido,
            'ingredientes' => $ingredi,
            'mesa' => $mesa,
            'idLanche' => $idLanche,
            'entregas' => $entrega_retirada

        ]));
    }


    public function editarLanche(int $id): void
    {
        $pedidoMesa = (new ComandaModelo())->buscaPorId("lanches", $id);
        $cardapio = (new ComandaModelo())->ler("cardapio_lanche", "lanche", "ASC");
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $lanches = (new ComandaModelo())->ler("lanches", "nome_lanche", "ASC");
        $entrega_retirada = (new ComandaModelo())->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('editarLanche.html', [
            'titulo' => 'Editar Lanche',
            'editar' => $pedidoMesa,
            'cardapio' => $cardapio,
            'ingredientes' => $ingredi,
            'lanches' => $lanches,
            'entregas' => $entrega_retirada
        ]));
    }


    public function editarAdicional(int $chave): void
    {
        $adicional = (new ComandaModelo())->buscaPorChave("adicionais", $chave );
        $ingredi = (new ComandaModelo())->ler("ingredientes", "ingrediente", "ASC");
        $entrega_retirada = (new ComandaModelo())->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('editarAdicional.html', [
            'titulo' => 'Editar Adicional',
            'editar' => $adicional,
            'ingredientes' => $ingredi,
            'entregas' => $entrega_retirada
        ]));
    }


    public function editarBebida(int $chave): void
    {
        $bebida = (new ComandaModelo())->buscaPorChave("bebidas", $chave );
        $cardapio_bebida = (new ComandaModelo())->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = (new ComandaModelo())->ler("tamanho_bebida", "tamanho", "DESC");
        $entrega_retirada = (new ComandaModelo())->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('editarBebida.html', [
            'titulo' => 'Editar Bebida',
            'editar' => $bebida,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida,
            'entregas' => $entrega_retirada
        ]));
    }

    public function imprimir($id_mesa)
    {
        $comandaModelo = new ComandaModelo();

        $buscaMesa = $comandaModelo->buscaId_mesa("total", $id_mesa);
        $mesa = $buscaMesa->mesa ?? 0;

        $lanches = $comandaModelo->buscaIds_mesa("lanches", $id_mesa);
        $adicionais = $comandaModelo->buscaIds_mesa("adicionais", $id_mesa);
        $bebidas = $comandaModelo->buscaIds_mesa("bebidas", $id_mesa);

        $connector = new FilePrintConnector("php://output");
        $printer = new Printer($connector);

        $printer->text("LANCHONETE DO ZÉ\r\n");
        $printer->text("Mesa: $mesa\r\n");
        $printer->text("----------------------\r\n");

        // 🥪 Lanches + Adicionais
        foreach ($lanches as $lanche) {
            $printer->text($lanche->nome_lanche . "\r\n");

            // Encontra adicionais relacionados a esse lanche
            foreach ($adicionais as $adicional) {
                if ($adicional->id == $lanche->id_lanche) {
                    $printer->text("  " . $adicional->nome_adicional . "\r\n");
                }
            }
        }

        // 🥤 Bebidas
        if (!empty($bebidas)) {
            $printer->text("Bebidas:\r\n");
            foreach ($bebidas as $bebida) {
                $printer->text($bebida->nome_bebida . " - " . $bebida->tamanho_bebida . "\r\n");
            }
        }

        $printer->text("----------------------\r\n");
        $printer->text("Obrigado pela preferência!\r\n");
        $printer->cut();
        $printer->close();

        // Envia os dados para o HTML também
        echo $this->template->renderizar('imprimir.html', [
            'titulo' => 'Imprimir',
            'mesa' => $mesa,
            'lanches' => $lanches,
            'adicionais' => $adicionais,
            'bebidas' => $bebidas
        ]);
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
