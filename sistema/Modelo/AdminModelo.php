<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Modelo\HelpersModelo;


class AdminModelo
{

    public function cadastrarLanche(array $dados)
    {
        $id_referencia = $dados["id_referencia"] ?? 0;
        $lanche = $dados['lanche'] ?? null;
        $valor = $_POST['valor'] ?? 'R$ 0,00';

        $valorNumerico = floatval(
            str_replace(',', '.', preg_replace('/[^\d,]/', '', $valor))
        );

        if (!empty($lanche)){  

            $query = "INSERT INTO cardapio_lanche (id_ingredi, lanche, valor) VALUES (?, ?, ?)";
            $stmt = Conexao::getInstancia()->prepare($query);

            if (!empty($lanche) && is_numeric($valorNumerico)) {
                $stmt->execute([$id_referencia, $lanche, $valorNumerico]);
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
        $valor = $dados['valorAdicional'] ?? 'R$ 0,00';

        $valorNumerico = floatval(
            str_replace(',', '.', preg_replace('/[^\d,]/', '', $valor))
        );

        if (!empty($adicional)){  

            $query = "INSERT INTO ingredientes (ingrediente, valor) VALUES (?, ?)";
            $stmt = Conexao::getInstancia()->prepare($query);

            if (!empty($adicional) && is_numeric($valorNumerico)) {
                $stmt->execute([$adicional, $valorNumerico]);
            }
        }
        
    }

    public function cadastrarBebida(array $dados)
    {

        $tamanhos = (new HelpersModelo())->buscaTodosControles();
        $novoControle = 0;

        if (!empty($tamanhos)) {
            $ultimo = end($tamanhos);
            $ultimoControle = $ultimo->controle;
            $novoControle = $ultimoControle + 1;

            echo "Último controle: $ultimoControle<br>";
            echo "Novo controle: $novoControle";
        } else {
            // Nenhum controle ainda, pode começar do 1
            $novoControle = 1;
            echo "Nenhum controle encontrado. Começando pelo controle: $novoControle";
        }

        $id_referencia = $dados["id_bebida"] ?? 0;
        $bebida = $dados['bebida'] ?? null;
        $valor = $dados['valorBebida'] ?? 'R$ 0,00';

        $valorNumerico = floatval(
            str_replace(',', '.', preg_replace('/[^\d,]/', '', $valor))
        );

        if (!empty($bebida)){  

            $query = "INSERT INTO marcas_bebida (bebida_id, marca) VALUES (?, ?)";
            $stmt = Conexao::getInstancia()->prepare($query);

            if (!empty($bebida) && is_numeric($valorNumerico)) {
                $stmt->execute([$id_referencia, $bebida]);
            }

            $queryTamanho = "INSERT INTO tamanho_bebida (marca_bebida_id, tamanho, valor, controle) VALUES (?, ?, ?, ?)";
            $stmtTamanho = Conexao::getInstancia()->prepare($queryTamanho);

            $tamanho = $dados['tamanho'] ?? null;

            if (!empty($tamanho)) {
                $stmtTamanho->execute([$id_referencia, $tamanho, $valorNumerico, $novoControle]);
            }
        }
        
    }

    public function editarLanche(array $dados, int $id)
    {
        $dados['id'] = $id;

        $queryLanche = "UPDATE cardapio_lanche SET 
            lanche = :lanche, 
            valor = :valor 
        WHERE id_ingredi = :id";

        $stmtLanche = Conexao::getInstancia()->prepare($queryLanche);
        $stmtLanche->execute($dados);
    }

    public function editarIngredientes(string $ingredientes, int $id)
    {
        $query = "UPDATE lanche_ingredientes SET 
            ingredientes = :ingredientes
        WHERE lanche_id = :id";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute([
            'ingredientes' => $ingredientes,
            'id' => $id
        ]);
    }


    public function editarBebida(string $bebida, int $id)
    {
        $query = "UPDATE marcas_bebida SET 
            marca = :bebida
        WHERE bebida_id = :id";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute([
            'bebida' => $bebida,
            'id' => $id
        ]);
    }

    public function editarTamanhoBebida(string $tamanho, int $valor, int $controle)
    {
        $query = "UPDATE tamanho_bebida SET 
            tamanho = :tamanho,
            valor = :valor
        WHERE controle = :controle";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute([
            'tamanho' => $tamanho,
            'valor' => $valor,
            'controle' => $controle
        ]);
    }

    public function editarAdicional(string $adicional, int $valor, int $id)
    {
        $query = "UPDATE ingredientes SET 
            ingrediente = :adicional,
            valor = :valor
        WHERE id = :id";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute([
            'adicional' => $adicional,
            'valor' => $valor,
            'id' => $id
        ]);
    }

    public function excluirAdicional(int $id)
    {   
        $query = "DELETE FROM ingredientes WHERE id = :id";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
            
    }

    public function excluirLanche(int $id)
    {   
        $query = "DELETE FROM cardapio_lanche WHERE id_ingredi = :id";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);

        $queryIngrediente = "DELETE FROM lanche_ingredientes WHERE lanche_id = :id";
        $stmtIngrediente = Conexao::getInstancia()->prepare($queryIngrediente);
        $stmtIngrediente->execute([
            'id' => $id
        ]);       
    }

    public function excluirBebida(int $id)
    {   
        $query = "DELETE FROM marcas_bebida WHERE controle = :id";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);

        $queryBebida = "DELETE FROM tamanho_bebida WHERE controle = :id";
        $stmtBebida = Conexao::getInstancia()->prepare($queryBebida);
        $stmtBebida->execute([
            'id' => $id
        ]);       
    }

}

