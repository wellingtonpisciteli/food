<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\ComandaModelo;
use sistema\Nucleo\Conexao;

class ComandaControlador extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates\comanda\views');
    }

    public function cadastrar(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            (new ComandaModelo())->armazenarLanche($dados);

            (new ComandaModelo())->armazenarAdicional($dados);

            (new ComandaModelo())->armazenarBebida($dados);

            (new ComandaModelo())->armazenarEatualizarTotal($dados);
            
            (new ComandaModelo())->armazenarHora($dados);

        }

        Helpers::redirecionar('pedidosAbertos');
    }


    public function atualizar(int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (!empty($dados['id_cardapio'])) {
                $dadosPedido = [
                    'id_lanche' => $dados['id_lanche'],
                    'detalhes_lanche' => $dados['detalhes_lanche'],
                    'status' => $dados['status']
                ];

                $mesa = $dados['mesa'];

                $idCardapio = $dados['id_cardapio'];
    
                (new ComandaModelo())->atualizarLanche($dadosPedido, $id, $idCardapio, $mesa);
            }
            
            if (!empty($dados['idCardapio_bebida'])) {

                $idCardapio_bebida = $dados['idCardapio_bebida'];
                $idTamanhoValor = $dados['idTamanhoValor'];

                (new ComandaModelo())->atualizarBebida($id, $idCardapio_bebida, $idTamanhoValor);
            }

            if (!empty($dados['idCardapio_adicional'])) {

                $idCardapio_adicional = $dados['idCardapio_adicional'];
                $tipo = $dados['tipo'];

                (new ComandaModelo())->atualizarAdicional($id, $idCardapio_adicional, $tipo);
            }

            if (($dados['apagar'])=='preenchido'){
                $idApagarAdicional = $dados['idApagarAdicional'];

                (new ComandaControlador())->excluir($id, $idApagarAdicional);
            }
        }

        Helpers::redirecionar('pedidosAbertos');
    }
    

    public function excluir(int $id, int $idApagarAdicional)
    {   
        (new ComandaModelo())->apagarLanche($id, $idApagarAdicional);

        (new ComandaModelo())->apagarAdicional($id);

        (new ComandaModelo())->apagarBebida($id);

        Helpers::redirecionar('pedidosAbertos');
    }

    public function excluirMesa(int $mesa)
    {
        (new ComandaModelo())->apagarMesa($mesa);

        Helpers::redirecionar('pedidosAbertos');
    }

    public function editarMesa(int $mesaAtual, int $novaMesa)
    {
        if ($novaMesa) {
            (new ComandaModelo())->atualizarMesa($mesaAtual, $novaMesa);
        }

        Helpers::redirecionar('pedidosAbertos');
    }
}
