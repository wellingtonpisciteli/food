<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Helpers;

class ComandaModelo
{
    public function ler(string $tabela, string $nome): array | object
    {
        $querry = "SELECT * FROM {$tabela} ORDER BY {$nome} DESC ";
        $stmt = Conexao::getInstancia()->query($querry);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function lerAdicional(string $tabela, string $nome): array | object
    {
        $querry = "SELECT * FROM {$tabela} ORDER BY {$nome} ASC ";
        $stmt = Conexao::getInstancia()->query($querry);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function buscaPorId(string $tabela, int $id): bool | object
    {
        $querry = "SELECT * FROM {$tabela} WHERE id={$id}";
        $stmt = Conexao::getInstancia()->query($querry);
        $resultado = $stmt->fetch();
        return $resultado;
    }

    public function buscaPorChave(string $tabela, int $id): bool | object
    {
        $querry = "SELECT * FROM {$tabela} WHERE chave={$id}";
        $stmt = Conexao::getInstancia()->query($querry);
        $resultado = $stmt->fetch();
        return $resultado;
    }


    public function buscaPorMesa(string $tabela, int $mesa): array
    {
        $querry = "SELECT * FROM {$tabela} WHERE mesa={$mesa}";
        $stmt = Conexao::getInstancia()->query($querry);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function lerRelacao(string $tabela, string $parametro, int $id): array | object
    {
        $querry = "SELECT * FROM {$tabela} WHERE {$parametro}={$id} ORDER BY id ASC";
        $stmt = Conexao::getInstancia()->query($querry);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }


    public function armazenarPedido(array $dados): void
    {
        $query = "INSERT INTO pedidos (mesa, id_lanche, nome_lanche, valor_lanche, detalhes_lanche, total) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        foreach ($dados['mesa'] as $index => $mesa) {
            $id_lanche = $dados['id_lanche'][$index] ?? null;
            $nome_lanche = $dados['nome_lanche'][$index] ?? null;
            $valor_lanche = $dados['valor_lanche'][$index] ?? null;
            $detalhes_lanche = $dados['detalhes_lanche'][$index] ?? null;

            $total = $dados['total'][$index] ?? null;

            if (!empty($nome_lanche)) {
                $stmt->execute([
                    $mesa,
                    $id_lanche,
                    $nome_lanche,
                    $valor_lanche,
                    $detalhes_lanche,
                    $total
                ]);
            }
        }
    }

    public function armazenarAdicional(array $adicional)
    {
        $query = "INSERT INTO adicionais (id, nome_adicional, valor_adicional) VALUES (?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        foreach ($adicional['id_ingredi'] as $index => $id_ingredi) {
            $add_ingredi = $adicional['add_ingredi'][$index] ?? null;
            $valor_adicional = $adicional['valor_ingredi'][$index] ?? null;

            $stmt->execute([
                $id_ingredi,
                $add_ingredi,
                $valor_adicional
            ]);
        }
    }

    public function armazenarBebida(array $bebida)
    {
        $query = "INSERT INTO bebidas (mesa, id, nome_bebida, tamanho_bebida, detalhes_bebida, valor_bebida) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        foreach ($bebida['nome_bebida'] as $index => $nome_bebida) {
            $mesa = $bebida['mesa'][$index] ?? null;
            $idBebida = $bebida['id_bebida'][$index] ?? null;
            $tamanho_bebida = $bebida['tamanho_bebida'][$index] ?? null;
            $detalhes_bebida = $bebida['detalhes_bebida'][$index] ?? null;
            $valor_bebida = $bebida['valor_bebida'][$index] ?? null;

            $stmt->execute([
                $mesa,
                $idBebida,
                $nome_bebida,
                $tamanho_bebida,
                $detalhes_bebida,
                $valor_bebida
            ]);
        }
    }

    public function atualizarMesa(array $dados, $mesa)
    {
        $query = "UPDATE pedidos SET mesa = :mesa WHERE mesa = {$mesa}";

        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute($dados);
    }


    public function atualizarPedido(array $dados, int $id)
    {
        $query = "UPDATE pedidos SET id_lanche = :id_lanche, 
        nome_lanche = :nome_lanche, 
        valor_lanche = :valor_lanche, 
        detalhes_lanche = :detalhes_lanche, 
        total = :total, 
        status = :status WHERE id = {$id}";

        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute($dados);
    }

    public function atualizarAdicional(array $dados, int $chave)
    {
        $query = "UPDATE adicionais SET nome_adicional = :nome_adicional, 
        valor_adicional = :valor_adicional 
        WHERE chave = {$chave}";

        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute($dados);
    }

    public function atualizarBebida(array $dados, int $chave)
    {
        $query = "UPDATE bebidas SET nome_bebida = :nome_bebida,
        tamanho_bebida = :tamanho_bebida, 
        valor_bebida = :valor_bebida 
        WHERE chave = {$chave}";

        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute($dados);
    }

    public function apagarPedido(int $id){
        $query = "DELETE FROM pedidos WHERE id = {$id}";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute();
    }

    public function apagarAdicional(int $chave){
        $query = "DELETE FROM adicionais WHERE chave = {$chave}";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute();
    }

    public function apagarBebida(int $chave){
        $query = "DELETE FROM bebidas WHERE chave = {$chave}";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute();
    }
}
