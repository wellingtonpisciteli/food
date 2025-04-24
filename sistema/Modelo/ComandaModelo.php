<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Helpers;

class ComandaModelo
{
    public function ler(string $tabela, string $nome, string $param): array | object
    {
        $query = "SELECT * FROM {$tabela} ORDER BY {$nome} {$param} ";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }

    public function lerAdicional(string $tabela, string $nome): array | object
    {
        $query = "SELECT * FROM {$tabela} ORDER BY {$nome} ASC ";
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

    public function lerRelacao(string $tabela, string $parametro, int $id): array | object
    {
        $querry = "SELECT * FROM {$tabela} WHERE {$parametro}={$id} ORDER BY id ASC";
        $stmt = Conexao::getInstancia()->query($querry);
        $resultado = $stmt->fetchAll();
        return $resultado;
    }


    public function armazenarLanche(array $dados): void
    {
        $query = "INSERT INTO lanches (mesa, id_lanche, nome_lanche, valor_lanche, detalhes_lanche, data_hora) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = Conexao::getInstancia()->prepare($query);

        if (!empty($dados['nome_lanche']) && is_array($dados['nome_lanche'])) {
            foreach ($dados['mesa'] as $index => $mesa) {
                $id_lanche = $dados['id_lanche'][$index] ?? null;
                $nome_lanche = $dados['nome_lanche'][$index] ?? null;
                $valor_lanche = $dados['valor_lanche'][$index] ?? null;
                $detalhes_lanche = $dados['detalhes_lanche'][$index] ?? null;
                $data_hora = $dados['data_hora'][$index] ?? null;

                if (!empty($nome_lanche)) {
                    $stmt->execute([
                        $mesa,
                        $id_lanche,
                        $nome_lanche,
                        $valor_lanche,
                        $detalhes_lanche,
                        $data_hora
                    ]);
                }
            }
        }
    }

    public function armazenarAdicional(array $adicional)
    {
        $query = "INSERT INTO adicionais (id, mesa, nome_adicional, valor_adicional) VALUES (?, ?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        if (!empty($adicional['id_ingredi']) && is_array($adicional['id_ingredi'])) {
            foreach ($adicional['id_ingredi'] as $index => $id_ingredi) {
                $mesa = $adicional['mesa'][$index] ?? null;
                $add_ingredi = $adicional['add_ingredi'][$index] ?? null;
                $valor_adicional = $adicional['valor_ingredi'][$index] ?? null;

                if (!empty($id_ingredi)) {
                    $stmt->execute([
                        $id_ingredi,
                        $mesa,
                        $add_ingredi,
                        $valor_adicional
                    ]);
                }
            }
        }
    }

    public function armazenarBebida(array $bebida)
    {
        $query = "INSERT INTO bebidas (mesa, id, nome_bebida, tamanho_bebida, detalhes_bebida, valor_bebida) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        if (!empty($bebida['nome_bebida']) && is_array($bebida['nome_bebida'])) {
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
    }

    public function armazenarTotal(array $dados): void
    {
        $query = "INSERT INTO total (mesa, total) VALUES (?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        foreach ($dados['mesa'] as $index => $mesa) {
            
            $total = $dados['total'][$index] ?? null;

            if (!empty($total)) {
                $stmt->execute([
                    $mesa,
                    $total
                ]);
            }
        }
    }

    public function atualizarMesa(array $dados, $mesaAntiga)
    {
        $tabelas = ['lanches', 'adicionais', 'bebidas', 'total'];

        foreach ($tabelas as $tabela) {
            $query = "UPDATE {$tabela} SET mesa = :mesaNova WHERE mesa = :mesaAntiga";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                ':mesaNova' => $dados['mesa'],
                ':mesaAntiga' => $mesaAntiga
            ]);
        }
    }

    public function atualizarLanche(array $dados, int $id)
    {
        $query = "UPDATE lanches SET 
            id_lanche = :id_lanche, 
            nome_lanche = :nome_lanche, 
            valor_lanche = :valor_lanche, 
            detalhes_lanche = :detalhes_lanche, 
            status = :status 
            WHERE id = :id";

        $stmt = Conexao::getInstancia()->prepare($query);

        $dados['id'] = $id;

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

    public function atualizarTotal(int $novoTotal, int $mesa)
    {
        $query = "UPDATE total SET total = :novoTotal WHERE mesa = :mesa";

        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([
            ':novoTotal' => $novoTotal,
            ':mesa' => $mesa
        ]);
    }

    public function atualizarNovoTotal(array $dados)
    {
        $query = "UPDATE total SET total = :novoTotal WHERE mesa = :mesa";

        $stmt = Conexao::getInstancia()->prepare($query);
        
        foreach ($dados['mesa'] as $index => $mesa) {
            
            $total = $dados['novoTotal'][$index] ?? null;

            if (!empty($total)) {
                $stmt->execute([
                    ':novoTotal' => $total,
                    ':mesa' => $mesa
                ]);
            }
        }
    }

    public function atualizarHora(array $dados){
        $query = "UPDATE lanches SET data_hora = :data_hora WHERE mesa = :mesa";

        $stmt = Conexao::getInstancia()->prepare($query);

        foreach ($dados['mesa'] as $index => $mesa) {
            
            $hora = $dados['data_hora'][$index] ?? null;

            if (!empty($hora)) {
                $stmt->execute([
                    ':data_hora' => $hora,
                    ':mesa' => $mesa
                ]);
            }
        }
    }

    public function apagarLanche(int $id){
        $query = "DELETE FROM lanches WHERE id = {$id}";

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
