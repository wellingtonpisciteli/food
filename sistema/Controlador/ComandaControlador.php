<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\ComandaModelo;
use sistema\Nucleo\Conexao;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

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

            if ($dados["tipo_retirada"] != "mesa"){
                (new ComandaModelo())->armazenarEntrega($dados);
            }

            (new ComandaModelo())->armazenarEatualizarTotal($dados);
            
            (new ComandaModelo())->armazenarHora($dados);

        }

        if (!empty($dados['controleDestino'])){
            Helpers::redirecionar('entregasAbertas');
        }else{
            Helpers::redirecionar('pedidosAbertos');
        }
    }


    public function atualizar(int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(!empty($dados['controleDestino'])){
                $controleDestino = $dados['controleDestino'];
            }

            if(!empty($dados['controleAdicional'])){
                $controleAdicional = $dados['controleAdicional'];
            }

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

                (new ComandaControlador())->excluir($id, $idApagarAdicional, $id_mesa, $controleDestino, $controleAdicional);
                
            }elseif ($dados['apagar'] == 'preenchido999'){
                $idApagarAdicional = $dados['idApagarAdicional'];

                (new ComandaControlador())->excluir999($idApagarAdicional, $id_mesa, $controleDestino);
            }
        }

        if (!empty($dados['controleDestino'])){
            Helpers::redirecionar('entregasAbertas');
        }else{
            Helpers::redirecionar('pedidosAbertos');
        }    
    }
    

    public function excluir(int $id, int $idApagarAdicional, int $id_mesa, ?string $controleDestino = null, string $controleAdicional)
    {   
        (new ComandaModelo())->apagarLanche($id, $idApagarAdicional, $id_mesa);

        if ($controleAdicional === 'controlBebida') {
            (new ComandaModelo())->apagarBebida($id, $id_mesa);
        } elseif ($controleAdicional === 'controlAdicional') {
            (new ComandaModelo())->apagarAdicional($id, $id_mesa);
        }

        if (!empty($controleDestino)){
            Helpers::redirecionar('entregasAbertas');
        }else{
            Helpers::redirecionar('pedidosAbertos');
        }  
    }

    public function excluir999(int $idApagarAdicional, int $id_mesa, ?string $controleDestino = null)
    {   
        (new ComandaModelo())->apagar999($idApagarAdicional, $id_mesa);

        if (!empty($controleDestino)){
            Helpers::redirecionar('entregasAbertas');
        }else{
            Helpers::redirecionar('pedidosAbertos');
        }  
    }

    public function excluirMesa(int $id_mesa, int $status)
    {
        (new ComandaModelo())->apagarMesa($id_mesa);

        if($status == 1 ){
            Helpers::redirecionar('pedidosAbertos');
        }else{
            Helpers::redirecionar('pedidosFechados');
        }
    }

    public function excluirEntrega(int $id_pedido, int $status)
    {
        (new ComandaModelo())->apagarEntrega($id_pedido);

        if($status == 1 ){
            Helpers::redirecionar('entregasAbertas');
        }else{
            Helpers::redirecionar('pedidosFechados');
        }
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

    public function despachar(int $id_mesa): void
    {
        if ($id_mesa) {
            (new ComandaModelo())->despachar($id_mesa);
        }

       Helpers::redirecionar('entregasAbertas');
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
    }
}
