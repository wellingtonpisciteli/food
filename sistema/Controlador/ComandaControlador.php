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
            (new ComandaModelo())->armazenarPedido($dados);

            (new ComandaModelo())->armazenarAdicional($dados);

            (new ComandaModelo())->armazenarBebida($dados);
        }

        Helpers::redirecionar('adicionar');
    }

    public function atualizar(int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (!empty($dados['mesa'])) {
                $dadosMesa = ['mesa' => $dados['mesa']];
                $mesa = $dados['nummesa'];

                (new ComandaModelo())->atualizarMesa($dadosMesa, $mesa);
            }

            if (!empty($dados['nome_lanche'])) {
                $dadosPedido = [
                    'id_lanche' => $dados['id_lanche'],
                    'nome_lanche' => $dados['nome_lanche'],
                    'valor_lanche' => $dados['valor_lanche'],
                    'detalhes_lanche' => $dados['detalhes_lanche'],
                    'total' => $dados['total'],
                    'status' => $dados['status']
                ];
    
                (new ComandaModelo())->atualizarPedido($dadosPedido, $id);
            }
            
            if (!empty($dados['nome_bebida'])) {
                $dadosBebida = [
                    'nome_bebida' => $dados['nome_bebida'],
                    'tamanho_bebida' => $dados['tamanho_bebida'],
                    'valor_bebida' => $dados['valor_bebida']
                ];
    
                (new ComandaModelo())->atualizarBebida($dadosBebida, $id);
            }

            if (!empty($dados['nome_adicional'])) {
                $dadosAdicional = [
                    'nome_adicional' => $dados['nome_adicional'],
                    'valor_adicional' => $dados['valor_adicional']
                ];
    
                (new ComandaModelo())->atualizarAdicional($dadosAdicional, $id);
            }
        }

        Helpers::redirecionar('pedidosAbertos');
    }

    public function excluirPedido(int $id)
    {   
        (new ComandaModelo())->apagarPedido($id);

        (new ComandaModelo())->apagarAdicional($id);

        (new ComandaModelo())->apagarBebida($id);

        Helpers::redirecionar('pedidosAbertos');
    }
}
