<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Helpers;
use sistema\Modelo\ComandaModelo;

class AdminModelo
{
   public function cadastrarLanche(array $dados)
   {

        $id_referencia = $dados["id_referencia"] ?? 0;
        $lanche = $dados['lanche'] ?? null;
        $valor = $dados['valor'] ?? 0;

        if (!empty($lanche)){  

            $query = "INSERT INTO cardapio_lanche (id_ingredi, lanche, valor) VALUES (?, ?, ?)";
            $stmt = Conexao::getInstancia()->prepare($query);

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

    public function cadastrarAdicional(array $dados)
   {

        $adicional = $dados['adicional'] ?? null;
        $valor = $dados['valorAdicional'] ?? 0;

        if (!empty($adicional)){  

            $query = "INSERT INTO ingredientes (ingrediente, valor) VALUES (?, ?)";
            $stmt = Conexao::getInstancia()->prepare($query);

            if (!empty($adicional) && is_numeric($valor)) {
                $stmt->execute([$adicional, $valor]);
            }
        }
        
    }

    public function cadastrarBebida(array $dados)
   {

        $id_referencia = $dados["id_bebida"] ?? 0;
        $bebida = $dados['bebida'] ?? null;
        $valor = $dados['valorBebida'] ?? 0;

        if (!empty($bebida)){  

            $query = "INSERT INTO marcas_bebida (bebida_id, marca) VALUES (?, ?)";
            $stmt = Conexao::getInstancia()->prepare($query);

            if (!empty($bebida) && is_numeric($valor)) {
                $stmt->execute([$id_referencia, $bebida]);
            }

            $queryTamanho = "INSERT INTO tamanho_bebida (marca_bebida_id, tamanho, valor) VALUES (?, ?, ?)";
            $stmtTamanho = Conexao::getInstancia()->prepare($queryTamanho);

            $tamanho = $dados['tamanho'] ?? null;

            if (!empty($tamanho)) {
                $stmtTamanho->execute([$id_referencia, $tamanho, $valor]);
            }
        }
        
    }
    
    
}

