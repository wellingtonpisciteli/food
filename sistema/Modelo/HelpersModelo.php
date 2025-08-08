<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Mensagem;
use PDO;


class HelpersModelo
{
    public function ler(string $tabela, string $nome, string $param): array | object
    {
        $query = "SELECT * FROM {$tabela} ORDER BY {$nome} {$param} ";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function buscaFetch(string $tabela, string $param, $id): bool|object
    {
        $tabelasPermitidas = ['adicionais', 'bebidas', 'cardapio_bebida', 'cardapio_lanche', 'entrega_retirada', 'ingredientes', 'lanches', 'lanche_ingredientes', 'marcas_bebida', 'tamanho_bebida', 'total', 'usuarios']; 

        if (!in_array($tabela, $tabelasPermitidas)) {
            (new Mensagem())->erro("Tabela não permitida.");
        }

        $query = "SELECT * FROM {$tabela} WHERE {$param} = :id";

        try {
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_OBJ);
            return $resultado ?: (object)[];
        } catch (\PDOException $e) {
            (new Mensagem())->erro("Erro na consulta: " . $e->getMessage());
            return false;
        }
    }

    public function buscaFetchAll(string $tabela, string $param, $id): array
    {
        $tabelasPermitidas = ['adicionais', 'bebidas', 'cardapio_bebida', 'cardapio_lanche', 'entrega_retirada', 'ingredientes', 'lanches', 'lanche_ingredientes', 'marcas_bebida', 'tamanho_bebida', 'total', 'usuarios']; 

        if (!in_array($tabela, $tabelasPermitidas)) {
            (new Mensagem())->erro("Tabela não permitida.");
        }

        $query = "SELECT * FROM {$tabela} WHERE {$param} = :id";

        try {
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            (new Mensagem())->erro("Erro na consulta múltipla: " . $e->getMessage());
            return [];
        }
    }

    public function buscaCliente(int $id_mesa): object
    {
        $query = "SELECT cliente FROM entrega_retirada WHERE id_mesa = :id_mesa LIMIT 1";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_OBJ);

        return $resultado ?: (object)[];
    }

    public function buscaTipo(int $id_mesa): object
    {
        $query = "SELECT tipo FROM entrega_retirada WHERE id_mesa = :id_mesa LIMIT 1";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_OBJ);

        return $resultado ?: (object)[];
    }
    

    public function maisVendido(string $tabela, string $item, string $param, string $dataAtual)
    {
        $query = "SELECT {$item}, COUNT(*) AS total_vendas
                FROM {$tabela}
                WHERE DATE(data_hora) = :dataAtual
                GROUP BY {$item}
                ORDER BY total_vendas {$param}";

        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([
            'dataAtual' => $dataAtual
        ]);

        return $stmt->fetchAll();
    }

    public function buscaTodosControles()
    {
        $query = "SELECT * FROM tamanho_bebida";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function buscaPorEmail(string $email): object|null
    {
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }
}