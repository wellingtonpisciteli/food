<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Helpers;

class HelpersModelo
{
    public function ler(string $tabela, string $nome, string $param): array | object
    {
        $query = "SELECT * FROM {$tabela} ORDER BY {$nome} {$param} ";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function buscaPorId(string $tabela, int $id): bool | object
    {
        $query = "SELECT * FROM {$tabela} WHERE id={$id}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();
        return $resultado;
    }

    public function buscaPorIdS(string $tabela, int $id): array
    {
        $query = "SELECT * FROM {$tabela} WHERE id={$id}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function buscaPorId_lanche(string $tabela, int $id): bool | object
    {
        $query = "SELECT * FROM {$tabela} WHERE id_lanche={$id}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();
        return $resultado;
    }

    public function buscaPorChave(string $tabela, int $id): bool | object
    {
        $query = "SELECT * FROM {$tabela} WHERE chave={$id}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();
        return $resultado;
    }

    public function buscaPorMesa(string $tabela, int $mesa): array
    {
        $query = "SELECT * FROM {$tabela} WHERE mesa={$mesa}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function buscaTotal(string $tabela, int $mesa): object
    {
        $query = "SELECT * FROM {$tabela} WHERE mesa={$mesa}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();
        return $resultado;
    }

    public function buscaId_mesa(string $tabela, int $id_mesa): object
    {
        $query = "SELECT * FROM {$tabela} WHERE id_mesa={$id_mesa}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();
        return $resultado;
    }

    public function buscaIds_mesa(string $tabela, int $id_mesa): array
    {
        $query = "SELECT * FROM {$tabela} WHERE id_mesa={$id_mesa}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function lerRelacao(string $tabela, string $parametro, int $id): array | object
    {
        $query = "SELECT * FROM {$tabela} WHERE {$parametro}={$id} ORDER BY id ASC";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();
        return $resultado;
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
}