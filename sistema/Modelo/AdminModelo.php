<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Helpers;
use sistema\Modelo\ComandaModelo;

class AdminModelo
{
   public function cadastrarLanche(array $dados)
   {
        $query = "INSERT INTO cardapio_lanche (id_ingredi, lanche, valor) VALUES (?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        $id_referencia = $dados["id_referencia"] ?? 0;
        $lanche = $dados['lanche'] ?? null;
        $valor = $dados['valor'] ?? 0;

        

        if (!empty($lanche) && is_numeric($valor)) {
            $stmt->execute([$id_referencia, $lanche, $valor]);
        }


        $queryIngrediente = "INSERT INTO lanche_ingredientes (lanche_id, ingredientes) VALUES (?, ?)";
        $stmtIngrediente = Conexao::getInstancia()->prepare($queryIngrediente);

        $ingredientes = $dados['ingredientes'] ?? null;

        if (!empty($ingredientes)) {
            $stmtIngrediente->execute([$id_referencia, $ingredientes]);
        }
    }
    
    
}

