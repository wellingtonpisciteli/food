<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\ComandaModelo;
use sistema\Nucleo\Conexao;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use sistema\Modelo\HelpersModelo;

class ComandaControlador extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates\comanda\views');

        // $usuario = false;

        // if (!$usuario){
        //     $this->mensagem->erro("Faça login para ter acesso ao sistema!")->flash();

        //     Helpers::redirecionar('login');
        // }
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

        if (!empty($dados['controleAdicionar'])){
            $this->mensagem->sucesso('PEDIDO ADICIONADO COM SUCESSO!')->flash();
            Helpers::redirecionar('comanda');
        }else{
            $this->mensagem->sucesso('NOVO ITEM ADICIONADO COM SUCESSO!')->flash();
            Helpers::controleDestino($dados['controleDestino']);
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

        $this->mensagem->informa('ITEM EDITADO COM SUCESSO!')->flash();
        Helpers::controleDestino($controleDestino);
    }
    

    public function excluir(int $id, int $idApagarAdicional, int $id_mesa, ?string $controleDestino = null, ?string $controleAdicional = null)
    {   
        (new ComandaModelo())->apagarLanche($id, $idApagarAdicional, $id_mesa);

        if($controleAdicional){
            if ($controleAdicional === 'controlBebida') {
                (new ComandaModelo())->apagarBebida($id, $id_mesa);
            } elseif ($controleAdicional === 'controlAdicional') {
                (new ComandaModelo())->apagarAdicional($id, $id_mesa);
            }
        }

        $this->mensagem->alerta('ITEM EXCLUIDO COM SUCESSO!')->flash();
        Helpers::controleDestino($controleDestino);
    }

    public function excluir999(int $idApagarAdicional, int $id_mesa, ?string $controleDestino = null)
    {   
        (new ComandaModelo())->apagar999($idApagarAdicional, $id_mesa);

        $this->mensagem->alerta('ITEM EXCLUIDO COM SUCESSO!')->flash();
        Helpers::controleDestino($controleDestino);
    }

    public function excluirMesa(int $id_mesa, int $status)
    {
        (new ComandaModelo())->apagarMesa($id_mesa);

        if($status == 1 ){
            $this->mensagem->alerta('MESA EXCLUIDA COM SUCESSO!')->flash();
            Helpers::redirecionar('pedidosAbertos');
        }else{
            $this->mensagem->alerta('MESA EXCLUIDA COM SUCESSO!')->flash();
            Helpers::redirecionar('pedidosFechados');
        }
    }

    public function excluirEntrega(int $id_pedido, int $status)
    {
        (new ComandaModelo())->apagarEntrega($id_pedido);

        if($status == 1 ){
            $this->mensagem->alerta('ENTREGA EXCLUIDA COM SUCESSO!')->flash();
            Helpers::redirecionar('entregasAbertas');
        }elseif ($status == 2){
            $this->mensagem->alerta('ENTREGA EXCLUIDA COM SUCESSO!')->flash();
            Helpers::redirecionar('entregasFechadas');
        }

        if($status == 3){
            $this->mensagem->alerta('RETIRADA EXCLUIDA COM SUCESSO!')->flash();
            Helpers::redirecionar('retiradasAbertas');
        }elseif ($status == 4){
            $this->mensagem->alerta('RETIRADA EXCLUIDA COM SUCESSO!')->flash();
            Helpers::redirecionar('retiradasFechadas');
        }
    }

    public function editarMesa(int $id_mesa, int $novaMesa)
    {
        if ($novaMesa) {
            (new ComandaModelo())->atualizarMesa($id_mesa, $novaMesa);
        }

        $this->mensagem->informa('MESA EDITADA COM SUCESSO!')->flash();
        Helpers::redirecionar('pedidosAbertos');
    }

    public function abrirMesa(int $id_mesa, int $param): void
    {
        if ($id_mesa) {
            (new ComandaModelo())->abrirMesa($id_mesa);
        }

        if ($param == 1) {
            $this->mensagem->informa('MESA REABERTA COM SUCESSO!')->flash();
            Helpers::redirecionar('pedidosFechados');
        }else{
            $this->mensagem->informa('PEDIDO REABERTO COM SUCESSO!')->flash();
            Helpers::redirecionar('retiradasFechadas');
        } 
    }

    public function abrirEntrega(int $id_mesa): void
    {
        if ($id_mesa) {
            (new ComandaModelo())->abrirEntrega($id_mesa);
        }
          
        $this->mensagem->informa('ENTREGA REABERTA COM SUCESSO!')->flash();
        Helpers::redirecionar('entregasFechadas');
    }

    public function despachar(int $id_mesa): void
    {
        if ($id_mesa) {
            (new ComandaModelo())->despachar($id_mesa);
        }

        $this->mensagem->informa('ENTREGA DESPACHADA COM SUCESSO!')->flash();
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

        if(!empty($dados['controleDestino'])){
            $this->mensagem->informa('TRANSAÇÃO FEITA COM SUCESSO!')->flash();
            Helpers::redirecionar('retiradasAbertas');            
        }else{
            $this->mensagem->informa('TRANSAÇÃO FEITA COM SUCESSO!')->flash();
            Helpers::redirecionar('pedidosAbertos');
        }
    }

    public function em_preparo(int $id_mesa)
    {
        (new ComandaModelo())->atualizar_em_preparo($id_mesa);

        $obj = (new HelpersModelo());

        $buscaTipo = $obj->buscaTipo($id_mesa);
        $cliente = $buscaTipo->tipo ?? null;

        $this->mensagem->sucesso('PEDIDO IMPRESSO COM SUCESSO!')->flash();
        
        if ($cliente == "entrega"){
            Helpers::redirecionar('entregasAbertas');
        }elseif ($cliente == "retirada"){
            Helpers::redirecionar('retiradasAbertas');
        }else{
            Helpers::redirecionar("pedidosAbertos");
        }
    }
}
