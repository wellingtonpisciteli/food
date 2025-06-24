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

}
