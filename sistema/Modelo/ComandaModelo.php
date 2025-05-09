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

        if (!empty($dados['id_lanche']) && is_array($dados['id_lanche'])) {
            foreach ($dados['mesa'] as $index => $mesa) {
                $id_lanche = $dados['id_lanche'][$index] ?? null;
                $data_hora = $dados['data_hora'][$index] ?? date('Y-m-d H:i:s');
                $detalhes_lanche = $dados['detalhes_lanche'][$index] ?? null;
                $id = $dados['idCardapioLanche'][$index] ?? null;

                if (!empty($id_lanche)){
                    // 🔍 Buscar os dados do lanche no cardápio
                    $busca = $this->buscaPorId('cardapio_lanche', $id);
                }
                
                // Validar se encontrou
                if ($busca) {
                    $nome_lanche = $busca->lanche ?? null;
                    $valor_lanche = $busca->valor ?? null;

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
    }

    public function armazenarAdicional(array $adicional)
    {
        $query = "INSERT INTO adicionais (id, mesa, nome_adicional, valor_adicional, tipo) VALUES (?, ?, ?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        if (!empty($adicional['id_ingredi']) && is_array($adicional['id_ingredi'])) {
            foreach ($adicional['id_ingredi'] as $index => $id_ingredi) {
                $mesa = $adicional['mesa_adicional'][$index] ?? null;
                $tipo = $adicional['tipo'][$index] ?? null;
                $id = $adicional['idAdd'][$index] ?? null;

                // 🔍 Buscar os dados do adicional no cardápio
                $busca = $this->buscaPorId('ingredientes', $id);

                if ($busca) {
                    $nomeAdicional = $busca->ingrediente ?? null;
                    
                    if (isset($tipo) && $tipo === '-') {
                        $valorAdicional = 0;
                    }else{
                        $valorAdicional = $busca->valor ?? 0;
                    }

                    if (!empty($id_ingredi)) {
                        $stmt->execute([
                            $id_ingredi,
                            $mesa,
                            $nomeAdicional,
                            $valorAdicional,
                            $tipo
                        ]);
                    }
                }
            }
        }
    }

    public function armazenarBebida(array $bebida)
    {
        $query = "INSERT INTO bebidas (mesa, id, nome_bebida, tamanho_bebida, detalhes_bebida, valor_bebida) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        if (!empty($bebida['mesa_bebida']) && is_array($bebida['mesa_bebida'])) {
            foreach ($bebida['mesa_bebida'] as $index => $mesa_bebida) {
                $idBebida = $bebida['id_bebida'][$index] ?? null;
                $detalhes_bebida = $bebida['detalhes_bebida'][$index] ?? null;
                $idMarca = $bebida['idMarcaBebida'][$index] ?? null;
                $idTamanho = $bebida['idTamanhoValorBebida'][$index] ?? null;

                // 🔍 Buscar os dados da bebida no cardápio
                $buscaMarca = $this->buscaPorId('marcas_bebida', $idMarca);
                $buscaTamanho = $this->buscaPorId('tamanho_bebida', $idTamanho);

                if ($buscaMarca) {
                    $nomeBebida = $buscaMarca->marca ?? null;
                    $tamanhoBebida = $buscaTamanho->tamanho ?? null;
                    $valorBebida = $buscaTamanho->valor ?? null;

                    $stmt->execute([
                        $mesa_bebida,
                        $idBebida,
                        $nomeBebida,
                        $tamanhoBebida,
                        $detalhes_bebida,
                        $valorBebida
                    ]);
                }
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

    public function armazenarNovoTotal(array $dados)
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

    public function armazenarHora(array $dados){
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

    public function atualizarMesa(int $mesaAtual, int $novaMesa)
    {
        $tabelas = ['lanches', 'adicionais', 'bebidas', 'total'];

        foreach ($tabelas as $tabela) {
            $query = "UPDATE {$tabela} SET mesa = :mesaNova WHERE mesa = :mesaAtual";

            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                ':mesaNova' => $novaMesa,
                ':mesaAtual' => $mesaAtual
            ]);
        }
    }

    public function atualizarLanche(array $dados, int $id, int $idCardapio)
    {
        $dados['id'] = $id;

        $dados['nome_lanche'] = null;
        $dados['valor_lanche'] = null;

        // 🔍 Buscar os dados do lanche no cardápio
        $busca = $this->buscaPorId('cardapio_lanche', $idCardapio);

        if ($busca) {
            $dados['nome_lanche'] = $busca->lanche ?? null;
            $dados['valor_lanche'] = $busca->valor ?? null;
        }

        $query = "UPDATE lanches SET 
            id_lanche = :id_lanche, 
            nome_lanche = :nome_lanche, 
            valor_lanche = :valor_lanche, 
            detalhes_lanche = :detalhes_lanche, 
            status = :status 
        WHERE id = :id";

        $stmt = Conexao::getInstancia()->prepare($query);

        // echo '<pre>';
        // print_r($stmt);
        // echo '</pre>';
        // die(); // Para interromper e ver o resultado

        $stmt->execute($dados);
    }

    public function atualizarAdicional(int $chave, int $idCardapio, string $tipo)
    {
        $query = "UPDATE adicionais SET nome_adicional = :nome_adicional, 
        valor_adicional = :valor_adicional, tipo = :tipo 
        WHERE chave = :chave";

        $nomeAdicional = null;
        $valorAdicional = null;

         // 🔍 Buscar os dados do adicional no cardápio
        $busca = $this->buscaPorId('ingredientes', $idCardapio);

        if ($busca) {
            $nomeAdicional = $busca->ingrediente ?? null;
            
            if (isset($tipo) && $tipo === '-') {
                $valorAdicional = 0;
            }else{
                $valorAdicional = $busca->valor ?? 0;
            }
        }

        $stmt = Conexao::getInstancia()->prepare($query);

        // echo '<pre>';
        // print_r($stmt);
        // echo '</pre>';
        // die(); // Para interromper e ver o resultado

        $stmt->execute([
            ':chave' => $chave,
            ':nome_adicional' => $nomeAdicional,
            ':valor_adicional' => $valorAdicional,
            ':tipo' => $tipo
        ]);
    }

    public function atualizarBebida(int $chave, int $idCardapio, int $idTamanho)
    {  
        $query = "UPDATE bebidas SET nome_bebida = :nome_bebida,
            tamanho_bebida = :tamanho_bebida, 
            valor_bebida = :valor_bebida 
        WHERE chave = :chave";

        $nomeBebida = null;
        $tamanhoBebida = null;
        $valorBebida = null;

        // Buscar dados
        $buscaMarca = $this->buscaPorId('marcas_bebida', $idCardapio);
        $buscaTamanho = $this->buscaPorId('tamanho_bebida', $idTamanho);

        if ($buscaMarca) {
            $nomeBebida = $buscaMarca->marca ?? null;
        }

        if ($buscaTamanho) {
            $tamanhoBebida = $buscaTamanho->tamanho ?? null;
            $valorBebida = $buscaTamanho->valor ?? null;
        }

        $stmt = Conexao::getInstancia()->prepare($query);

        // echo '<pre>';
        // print_r($stmt);
        // echo '</pre>';
        // die(); // Para interromper e ver o resultado

        $stmt->execute([
            ':nome_bebida' => $nomeBebida,
            ':tamanho_bebida' => $tamanhoBebida,
            ':valor_bebida' => $valorBebida,
            ':chave' => $chave
        ]);
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

    public function apagarLanche(int $id, $idApagarAdicional){
        $query = "DELETE FROM lanches WHERE id = {$id}";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute();

        $query2 = "DELETE FROM adicionais WHERE id = {$idApagarAdicional}";

        $stmt2 = Conexao::getInstancia()->prepare($query2);

        $stmt2->execute();
    }

    public function apagarAdicional(int $chave){
        $query = "DELETE FROM adicionais WHERE chave = {$chave}";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute();
    }

    public function apagarBebida(int $chave, int $idApagar){
        $query = "DELETE FROM bebidas WHERE chave = {$chave}";

        $stmt = Conexao::getInstancia()->prepare($query);

        $stmt->execute();

        $query2 = "DELETE FROM lanches WHERE id_lanche = {$idApagar}";

        $stmt2 = Conexao::getInstancia()->prepare($query2);

        $stmt2->execute();
    }

    public function apagarMesa(int $mesa)
    {
        $tabelas = ['lanches', 'adicionais', 'bebidas', 'total'];

        foreach ($tabelas as $tabela)
        {
            $query = "DELETE FROM {$tabela} WHERE mesa = :mesaApagar";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                ':mesaApagar' => $mesa
            ]);
        } 
    }

}
