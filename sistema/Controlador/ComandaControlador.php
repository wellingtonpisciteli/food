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

            $id_mesa = $dados['id_mesa'];

            if (!empty($dados['id_cardapio'])) {
                $dadosPedido = [
                    'id_lanche' => $dados['id_lanche'],
                    'detalhes_lanche' => $dados['detalhes_lanche'],
                    'status' => $dados['status']
                ];

                $idCardapio = $dados['id_cardapio'];
    
                (new ComandaModelo())->atualizarLanche($dadosPedido, $id, $idCardapio, $id_mesa);
            }
            
            if (!empty($dados['idCardapio_bebida'])) {

                $idCardapio_bebida = $dados['idCardapio_bebida'];
                $idTamanhoValor = $dados['idTamanhoValor'];

                (new ComandaModelo())->atualizarBebida($id, $idCardapio_bebida, $idTamanhoValor, $id_mesa);
            }

            if (!empty($dados['idCardapio_adicional'])) {

                $idCardapio_adicional = $dados['idCardapio_adicional'];
                $tipo = $dados['tipo'];

                (new ComandaModelo())->atualizarAdicional($id, $idCardapio_adicional, $tipo, $id_mesa);
            }

            if (($dados['apagar']) == 'preenchido'){
                $idApagarAdicional = $dados['idApagarAdicional'];

                (new ComandaControlador())->excluir($id, $idApagarAdicional, $id_mesa);
            }elseif ($dados['apagar'] == 'preenchido999'){
                $idApagarAdicional = $dados['idApagarAdicional'];

                (new ComandaControlador())->excluir999($idApagarAdicional, $id_mesa);
            }
        }

        Helpers::redirecionar('pedidosAbertos');
    }
    

    public function excluir(int $id, int $idApagarAdicional, int $id_mesa)
    {   
        (new ComandaModelo())->apagarLanche($id, $idApagarAdicional, $id_mesa);

        (new ComandaModelo())->apagarAdicional($id, $id_mesa);

        (new ComandaModelo())->apagarBebida($id, $id_mesa);

        Helpers::redirecionar('pedidosAbertos');
    }

    public function excluir999(int $idApagarAdicional, int $id_mesa)
    {   
        (new ComandaModelo())->apagar999($idApagarAdicional, $id_mesa);

        Helpers::redirecionar('pedidosAbertos');
    }

    public function excluirMesa(int $id_mesa)
    {
        (new ComandaModelo())->apagarMesa($id_mesa);

        Helpers::redirecionar('pedidosAbertos');
    }

    public function editarMesa(int $id_mesa, int $novaMesa)
    {
        if ($novaMesa) {
            (new ComandaModelo())->atualizarMesa($id_mesa, $novaMesa);
        }

        Helpers::redirecionar('pedidosAbertos');
    }

    public function abrirMesa(int $id_mesa): void
    {
        if ($id_mesa) {
            (new ComandaModelo())->abrirMesa($id_mesa);
        }

       Helpers::redirecionar('pedidosAbertos');
    }

    public function fecharMesa(int $id_mesa): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);            

            if($dados['subtotal']){
                (new ComandaModelo())->caixaSubTotal($dados, $id_mesa);
            }else{
                (new ComandaModelo())->caixaTotal($id_mesa, $dados);
            }
        }

        Helpers::redirecionar('pedidosAbertos');
    }
}
