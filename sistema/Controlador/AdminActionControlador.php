<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\AdminModelo;
use sistema\Nucleo\Conexao;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

/**
 * Controlador para a seção pública do site.
 * Responsável por manipular as requisições relacionadas às páginas do site acessíveis ao público.
 * 
 * @author Wellington Borges
 */
class AdminActionControlador extends Controlador
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

    public function novoItem(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(!empty($dados['lanche'])){
                (new AdminModelo())->cadastrarLanche($dados);
            }

            if(!empty($dados['adicional'])){
                (new AdminModelo())->cadastrarAdicional($dados);
            }

            if(!empty($dados['bebida'])){
                (new AdminModelo())->cadastrarBebida($dados);
            }
        
        }

        Helpers::redirecionar('cadastrarItem');
    }

    public function editarItem(int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (!empty($dados['lanche']) || !empty($dados['valor']) ) {
                $valorNumerico = floatval(
                    str_replace(',', '.', preg_replace('/[^\d,]/', '', $dados['valor']))
                );

                $dadosLanche = [
                    'lanche' => $dados['lanche'],
                    'valor' => $valorNumerico
                ];
    
                (new AdminModelo())->editarLanche($dadosLanche, $id);            
            }

            if (!empty($dados['ingredientes'])) {

                $ingredientes = $dados['ingredientes'];
    
                (new AdminModelo())->editarIngredientes($ingredientes, $id);
            }

            if (!empty($dados['bebida'])) {

                $bebida = $dados['bebida'];
    
                (new AdminModelo())->editarBebida($bebida, $id);
            }

            if (!empty($dados['tamanhoBebida']) || !empty($dados['valorBebida'])) {

                $tamanho = $dados['tamanhoBebida'];
                $valor = floatval(
                    str_replace(',', '.', preg_replace('/[^\d,]/', '', $dados['valorBebida']))
                );
                $controle = $dados['controleBebida'];
    
                (new AdminModelo())->editarTamanhoBebida($tamanho,  $valor, $controle);
            }

            if (!empty($dados['adicional']) || !empty($dados['valorAdicional'])) {

                $adicional = $dados['adicional'];
                $valorAdicional = floatval(
                    str_replace(',', '.', preg_replace('/[^\d,]/', '', $dados['valorAdicional']))
                );
    
                (new AdminModelo())->editarAdicional($adicional,  $valorAdicional, $id);
            }
                                
        }
        
        if($dados['lanche']){
            Helpers::redirecionar('editarLanches');
        }else if($dados['bebida']){
            Helpers::redirecionar('editarBebidas');
        }else{
            Helpers::redirecionar('editarAdicionais');
        }
    }

    public function excluirItem(int $id, int $controle)
    {
        if($controle == 1){
            (new AdminModelo())->excluirAdicional($id);
        }else if($controle == 2){
            (new AdminModelo())->excluirBebida($id);
        }else{
            (new AdminModelo())->excluirLanche($id);
        }

        if($controle == 1){
            Helpers::redirecionar('editarAdicionais');
        }else if($controle == 2){
            Helpers::redirecionar('editarBebidas');
        }else{
            Helpers::redirecionar('editarLanches');
        }
    }

}
