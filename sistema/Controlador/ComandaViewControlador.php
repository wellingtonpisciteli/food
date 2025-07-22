<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\ComandaModelo;
use sistema\Modelo\HelpersModelo;
use sistema\Nucleo\Conexao;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

/**
 * Controlador para a seção pública do site.
 * Responsável por manipular as requisições relacionadas às páginas do site acessíveis ao público.
 * 
 * @author Wellington Borges
 */
class ComandaViewControlador extends Controlador
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

        // $usuario = false;

        // if (!$usuario){
        //     $this->mensagem->erro("Faça login para ter acesso ao sistema!")->flash();

        //     Helpers::redirecionar('login');
        // }
    }

    public function comanda(): void
    {
        $obj = (new HelpersModelo());

        $dataAtual = (new Helpers())->dataAtual('Y-m-d');

        $cardapioBebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $cardapioLanche = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $pedidos = $obj->ler("lanches", "data_hora", "DESC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $adicional = $obj->ler("adicionais", "nome_adicional", "ASC");
        $bebidas = $obj->ler("bebidas", "nome_bebida", "DESC");
        $total = $obj->ler("total", "total", "DESC");
        $ingredientes = $obj->ler("lanche_ingredientes", "ingredientes", "ASC");
        $lancheMaisVendido = $obj->maisVendido('lanches', 'nome_lanche', 'DESC', $dataAtual);
        

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

    public function cardapio(): void
    {
        $obj = (new HelpersModelo());

        $cardapioBebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $cardapioLanche = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $adicional = $obj->ler("adicionais", "nome_adicional", "ASC");
        $bebidas = $obj->ler("bebidas", "nome_bebida", "DESC");
        $ingredientes = $obj->ler("lanche_ingredientes", "ingredientes", "ASC");
        
        echo ($this->template->renderizar('cardapio.html', [
            'titulo' => 'Cardapio',
            'cardapioBebida' => $cardapioBebida,
            'cardapioLanche' => $cardapioLanche,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'adicional' => $adicional,
            'bebidas' => $bebidas,
            'ingredientes' => $ingredientes,
        ]));
    }


    public function adicionar(): void
    {
        $obj = (new HelpersModelo());

        $cardapio = $obj->ler("cardapio_lanche", "lanche", "ASC");    
        $cardapio_bebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $pedido = $obj->ler("lanches", "id_lanche", "ASC");
        $bebidas = $obj->ler("bebidas", "id", "ASC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $mesa = $obj->ler("lanches", "mesa", "DESC");

        echo ($this->template->renderizar('adicionar/adicionar.html', [
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

        $obj = (new HelpersModelo());

        $adicional = $obj->ler("adicionais", "nome_adicional", "ASC");
        $pedidos = $obj->ler("lanches", "data_hora", "DESC");
        $bebidas = $obj->ler("bebidas", "nome_bebida", "DESC");
        $total = $obj->ler("total", "total", "DESC");
        $mesa = $obj->ler("lanches", "mesa", "DESC");
        $cardapioLanche = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $ingredientes = $obj->ler("lanche_ingredientes", "ingredientes", "ASC");
        $cardapioBebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");

        echo ($this->template->renderizar('visualizar/pedidosAbertos.html', [
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
        $obj = (new HelpersModelo());

        $adicional = $obj->ler("adicionais", "nome_adicional", "ASC");
        $pedidos = $obj->ler("lanches", "data_hora", "DESC");
        $bebidas = $obj->ler("bebidas", "nome_bebida", "DESC");
        $total = $obj->ler("total", "total", "DESC");
        $mesa = $obj->ler("lanches", "mesa", "DESC");
        $cardapioLanche = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $ingredientes = $obj->ler("lanche_ingredientes", "ingredientes", "ASC");
        $cardapioBebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");

        echo ($this->template->renderizar('visualizar/pedidosFechados.html', [
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
        $obj = (new HelpersModelo());

        $adicional = $obj->ler("adicionais", "nome_adicional", "ASC");
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

        echo ($this->template->renderizar('visualizar/entregasAbertas.html', [
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
        $obj = (new HelpersModelo());

        $adicional = $obj->ler("adicionais", "nome_adicional", "ASC");
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

        echo ($this->template->renderizar('visualizar/entregasFechadas.html', [
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
        $obj = (new HelpersModelo());

        $adicional = $obj->ler("adicionais", "nome_adicional", "ASC");
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

        echo ($this->template->renderizar('visualizar/retiradasAbertas.html', [
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
        $obj = (new HelpersModelo());

        $adicional = $obj->ler("adicionais", "nome_adicional", "ASC");
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

        echo ($this->template->renderizar('visualizar/retiradasFechadas.html', [
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
        $obj = (new HelpersModelo());

        $idMesa = $obj->buscaId_mesa("total", $id_mesa);
        $lanchesMesa = $obj->buscaIds_mesa("lanches", $id_mesa);
        $bebidasMesa = $obj->buscaIds_mesa("bebidas", $id_mesa);
        $totalMesa = $obj->buscaIds_mesa("total", $id_mesa);
        $cardapio = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $pedido = $obj->ler("lanches", "id_lanche", "ASC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $adicional = $obj->ler("adicionais", "nome_adicional", "ASC");
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");

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
        $obj = (new HelpersModelo());

        $cardapio = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $cardapio_bebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $pedido = $obj->ler("lanches", "id_lanche", "ASC");
        $bebidas = $obj->ler("bebidas", "id", "ASC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $mesa = $obj->buscaIds_mesa("total", $id_mesa);
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('adicionar/adicionarNaMesa.html', [
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
        $obj = (new HelpersModelo());

        $cardapio = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $pedido = $obj->ler("lanches", "id_lanche", "ASC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $mesa = $obj->buscaIds_mesa("total", $id_mesa);
        $idLanche = $obj->buscaPorId_lanche("lanches", $id_lanche);
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");


        echo ($this->template->renderizar('adicionar/adicionarAdicional.html', [
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
        $obj = (new HelpersModelo());

        $pedidoMesa = $obj->buscaPorId("lanches", $id);
        $cardapio = $obj->ler("cardapio_lanche", "lanche", "ASC");
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $lanches = $obj->ler("lanches", "nome_lanche", "ASC");
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('editar/editarLanche.html', [
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
        $obj = (new HelpersModelo());

        $adicional = $obj->buscaPorChave("adicionais", $chave );
        $ingredi = $obj->ler("ingredientes", "ingrediente", "ASC");
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('editar/editarAdicional.html', [
            'titulo' => 'Editar Adicional',
            'editar' => $adicional,
            'ingredientes' => $ingredi,
            'entregas' => $entrega_retirada
        ]));
    }


    public function editarBebida(int $chave): void
    {
        $obj = (new HelpersModelo());

        $bebida = $obj->buscaPorChave("bebidas", $chave );
        $cardapio_bebida = $obj->ler("marcas_bebida", "marca", "ASC");
        $tamanho_bebida = $obj->ler("tamanho_bebida", "tamanho", "DESC");
        $entrega_retirada = $obj->ler("entrega_retirada", "id_mesa", "DESC");

        echo ($this->template->renderizar('editar/editarBebida.html', [
            'titulo' => 'Editar Bebida',
            'editar' => $bebida,
            'cardapio_bebida' => $cardapio_bebida,
            'tamanhoBebida' => $tamanho_bebida,
            'entregas' => $entrega_retirada
        ]));
    }

    public function imprimir($id_mesa)
    {
        $obj = (new HelpersModelo());

        $buscaMesa = $obj->buscaId_mesa("total", $id_mesa);
        $mesa = $buscaMesa->mesa ?? 0;

        $lanches = $obj->buscaIds_mesa("lanches", $id_mesa);
        $adicionais = $obj->buscaIds_mesa("adicionais", $id_mesa);
        $bebidas = $obj->buscaIds_mesa("bebidas", $id_mesa);

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
}
