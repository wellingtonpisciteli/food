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
        $adicional = (new ComandaModelo())->lerAdcional("adicionais", "nome_adicional");

        echo ($this->template->renderizar('comanda.html', [
            'titulo' => 'Sistema_Food',
            'cardapioBebida' => $cardapioBebida,
            'cardapioLanche' => $cardapioLanche,
            'pedidos' => $pedidos,
            'tamanhoBebida' => $tamanho_bebida,
            'ingred' => $ingredi,
            'adicional' => $adicional,
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
        $adicional = (new ComandaModelo())->lerAdcional("adicionais", "nome_adicional");
        $pedidos = (new ComandaModelo())->ler("pedidos", "data_hora");

        echo ($this->template->renderizar('pedidosAbertos.html', [
            'titulo' => 'pedidos_abertos',
            'adicional' => $adicional,
            'pedidos' => $pedidos

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

    public function cadastrar(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            (new ComandaModelo())->armazenarPedido($dados);

            (new ComandaModelo())->armazenarAdicional($dados);
        }

        Helpers::redirecionar('adicionar');
    }

    public function atualizar(int $id): void
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Filtrando os dados recebidos via POST
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Filtrando apenas os dados necessários para o pedido
        $dadosPedido = [
            'mesa' => $dados['mesa'],
            'id_lanche' => $dados['id_lanche'],
            'nome_lanche' => $dados['nome_lanche'],
            'valor_lanche' => $dados['valor_lanche'],
            'detalhes_lanche' => $dados['detalhes_lanche'],
            'id_ingredi' => $dados['id_ingredi'],
            'add_ingredi' => $dados['add_ingredi'],
            'valor_ingredi' => $dados['valor_ingredi'],
            'nome_bebida' => $dados['nome_bebida'],
            'tamanho_bebida' => $dados['tamanho_bebida'],
            'valor_bebida' => $dados['valor_bebida'],
            'detalhes_bebida' => $dados['detalhes_bebida'],
            'total' => $dados['total'],
            'status' => $dados['status']
        ];

        // Atualizando a tabela de pedidos
        (new ComandaModelo())->atualizarPedido($dadosPedido, $id);

        if (!empty($dados['nome_adicional']) && !empty($dados['valor_adicional'])) {
            $dadosAdicional = [
                'nome_adicional' => $dados['nome_adicional'],
                'valor_adicional' => $dados['valor_adicional']
            ];

            (new ComandaModelo())->atualizarAdicional($dadosAdicional, $id);
        }
    }

    // Após a atualização, redireciona para a página de pedidos abertos
    Helpers::redirecionar('pedidosAbertos');
}

public function atualizarAdicional(int $chave): void
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Filtrando os dados recebidos via POST
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
        (new ComandaModelo())->atualizarAdicional($dados, $chave);
    }

    // Após a atualização, redireciona para a página de pedidos abertos
    Helpers::redirecionar('pedidosAbertos');
}


    public function editarPedido(int $id): void
    {
        $pedidoMesa = (new ComandaModelo())->buscaPorId("pedidos", $id);
        $adicional = (new ComandaModelo())->lerAdcional("adicionais", "nome_adicional");

        echo ($this->template->renderizar('editar.html', [
            'titulo' => 'editar_pedido',
            'editar' => $pedidoMesa,
            'adicional' => $adicional
        ]));
    }

    public function editarAdicionais(int $chave): void
    {
        $adicional = (new ComandaModelo())->buscaPorChave("adicionais", $chave );

        echo ($this->template->renderizar('editarAdicional.html', [
            'titulo' => 'editar_adicional',
            'adicional' => $adicional
        ]));
    }
}
