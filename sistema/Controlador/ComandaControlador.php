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

            (new ComandaModelo())->armazenarTotal($dados);
            
            (new ComandaModelo())->armazenarNovoTotal($dados);

            (new ComandaModelo())->armazenarHora($dados);

        }

        Helpers::redirecionar('pedidosAbertos');
    }


    public function atualizar(int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if ($dados['atualizarMesa'] == "preenchido") {
                $dadosMesa = ['mesa' => $dados['mesa']];
                $mesa = $dados['nummesa'];

                (new ComandaModelo())->atualizarMesa($dadosMesa, $mesa);
            }

            if (!empty($dados['novoTotal'])){
                $novoTotal = (int)$dados['novoTotal'];
                $mesa = (int)$dados['mesa'];

                (new ComandaModelo())->atualizarTotal($novoTotal, $mesa);
            }

            if (!empty($dados['id_cardapio'])) {
                $dadosPedido = [
                    'id_lanche' => $dados['id_lanche'],
                    'detalhes_lanche' => $dados['detalhes_lanche'],
                    'status' => $dados['status']
                ];

                $idCardapio = $dados['id_cardapio'];
    
                (new ComandaModelo())->atualizarLanche($dadosPedido, $id, $idCardapio);
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

            if (($dados['apagar'])=='preenchido'){
                $idApagarLanche = $dados['idApagar'];
                $idApagarAdicional = $dados['idApagarAdicional'];

                (new ComandaControlador())->excluir($id, $idApagarLanche, $idApagarAdicional);
            }
        }

        Helpers::redirecionar('pedidosAbertos');
    }
    

    public function excluir(int $id, int $idApagarLanche, int $idApagarAdicional)
    {   
        (new ComandaModelo())->apagarLanche($id, $idApagarAdicional);

        (new ComandaModelo())->apagarAdicional($id);

        (new ComandaModelo())->apagarBebida($id, $idApagarLanche);

        Helpers::redirecionar('pedidosAbertos');
    }

    public function excluirMesa(int $mesa)
    {
        (new ComandaModelo())->apagarMesa($mesa);

        Helpers::redirecionar('pedidosAbertos');
    }
}
