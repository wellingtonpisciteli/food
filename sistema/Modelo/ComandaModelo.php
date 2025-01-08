<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Helpers;

class ComandaModelo{
    public function ler(string $tabela, string $nome):array | object
    {
        $querry="SELECT * FROM {$tabela} ORDER BY {$nome} ASC";
        $stmt=Conexao::getInstancia()->query($querry);
        $resultado=$stmt->fetchAll();
        return $resultado;
    }

    public function buscaPorId(string $tabela, int $id):bool | object
    {
        $querry="SELECT * FROM {$tabela} WHERE id={$id}";
        $stmt=Conexao::getInstancia()->query($querry);
        $resultado=$stmt->fetch();
        return $resultado;
    }

    public function lerRelacao(string $tabela, string $parametro, int $id):array | object
    {
        $querry="SELECT * FROM {$tabela} WHERE {$parametro}={$id} ORDER BY id ASC";
        $stmt=Conexao::getInstancia()->query($querry);
        $resultado=$stmt->fetchAll();
        return $resultado;
    }

    /**
     * Insere múltiplos pedidos no banco de dados.
     *
     * @param array $dados Um array associativo contendo os dados dos pedidos.
     *                     Estrutura esperada:
     *                     [
     *                         'nome_lanche' => [string, string, ...], // Nomes dos lanches
     *                         'mesa'        => [int, int, ...],       // Mesas correspondentes
     *                         'valor'       => [float, float, ...]    // Valores dos lanches
     *                     ]
     *
     * @return void
     */
    public function armazenarPedido(array $dados):void{
        $querry="INSERT INTO pedidos (mesa, nome_lanche, valor_lanche, detalhes_lanche, nome_bebida, tamanho_bebida, valor_bebida, detalhes_bebida, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt=Conexao::getInstancia()->prepare($querry);

        foreach($dados['mesa'] as $index=>$mesa){
            $nome_lanche=$dados['nome_lanche'][$index] ?? null;
            $valor_lanche=$dados['valor_lanche'][$index] ?? null;
            $detalhes_lanche=$dados['detalhes_lanche'][$index] ?? null;

            $nome_bebida=$dados['nome_bebida'][$index] ?? null;
            $tamanho_bebida=$dados['tamanho_bebida'][$index] ?? null;
            $valor_bebida=$dados['valor_bebida'][$index] ?? null;
            $detalhes_bebida=$dados['detalhes_bebida'][$index] ?? null;

            $total=$dados['total'][$index] ?? null;

            if (!empty($nome_lanche) || !empty($nome_bebida)) {
                $stmt->execute([$mesa, $nome_lanche, $valor_lanche, $detalhes_lanche,  $nome_bebida, $tamanho_bebida, $valor_bebida, $detalhes_bebida, $total]);
            }
        }

    }
    
}