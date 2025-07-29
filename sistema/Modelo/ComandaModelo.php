<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Modelo\HelpersModelo;

class ComandaModelo
{

    public function armazenarLanche(array $dados): void
    {
        $query = "INSERT INTO lanches (id_mesa, mesa, id_lanche, nome_lanche, valor_lanche, detalhes_lanche, data_hora) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = Conexao::getInstancia()->prepare($query);

        if (!empty($dados['id_lanche']) && is_array($dados['id_lanche'])) {
            foreach ($dados['id_lanche'] as $index => $id_lanche) {
                $id_mesa = $dados['id_mesa'][$index] ?? null;
                $mesa = $dados['mesa'][$index] ?? null;
                $data_hora = $dados['data_hora'][$index] ?? date('Y-m-d H:i:s');
                $detalhes_lanche = $dados['detalhes_lanche'][$index] ?? null;
                $id = $dados['idCardapioLanche'][$index] ?? null;

                if (!empty($id_lanche)){
                    // 🔍 Buscar os dados do lanche no cardápio
                    $busca = (new HelpersModelo())->buscaPorId('cardapio_lanche', $id);
                }
                
                // Validar se encontrou
                if ($busca) {
                    $nome_lanche = $busca->lanche ?? null;
                    $valor_lanche = $busca->valor ?? null;

                    if (!empty($nome_lanche)) {
                        $stmt->execute([
                            $id_mesa,
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
        $query = "INSERT INTO adicionais (id, id_mesa, mesa, nome_adicional, valor_adicional, tipo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        if (!empty($adicional['id_ingredi']) && is_array($adicional['id_ingredi'])) {
            foreach ($adicional['id_ingredi'] as $index => $id_ingredi) {
                $id_mesa = $adicional['id_mesaAdicional'][$index] ?? null;
                $mesa = $adicional['mesa_adicional'][$index] ?? null;
                $tipo = $adicional['tipo'][$index] ?? null;
                $id = $adicional['idAdd'][$index] ?? null;

                // 🔍 Buscar os dados do adicional no cardápio
                $busca = (new HelpersModelo())->buscaPorId('ingredientes', $id);

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
                            $id_mesa,
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
        $query = "INSERT INTO bebidas (id_mesa, mesa, id, nome_bebida, tamanho_bebida, detalhes_bebida, valor_bebida) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = Conexao::getInstancia()->prepare($query);

        if (!empty($bebida['id_bebida']) && is_array($bebida['id_bebida'])) {
            foreach ($bebida['id_bebida'] as $index => $idBebida) {
                $id_mesa = $bebida['id_mesaBebida'][$index] ?? null;
                $mesa_bebida = $bebida['mesa_bebida'][$index] ?? null;
                $detalhes_bebida = $bebida['detalhes_bebida'][$index] ?? null;
                $idMarca = $bebida['idMarcaBebida'][$index] ?? null;
                $idTamanho = $bebida['idTamanhoValorBebida'][$index] ?? null;

                // 🔍 Buscar os dados da bebida no cardápio
                $buscaMarca = (new HelpersModelo())->buscaPorId('marcas_bebida', $idMarca);
                $buscaTamanho = (new HelpersModelo())->buscaPorId('tamanho_bebida', $idTamanho);

                if ($buscaMarca) {
                    $nomeBebida = $buscaMarca->marca ?? null;
                    $tamanhoBebida = $buscaTamanho->tamanho ?? null;
                    $valorBebida = $buscaTamanho->valor ?? null;

                    $stmt->execute([
                        $id_mesa,
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

    public function armazenarEntrega(array $dados): void
    {
        $query = "INSERT INTO entrega_retirada (id_mesa, tipo, cliente, bairro, endereco, taxa, tipo_pagamento, contato, tipo_contato, app) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = Conexao::getInstancia()->prepare($query);

        // Apenas um pedido
        if (!empty($dados['id_pedido'])) {
            $stmt->execute([
                $dados['id_pedido'],
                $dados['tipo_retirada'],
                $dados['cliente'],
                $dados['bairro'],
                $dados['endereco'],
                $dados['taxa'],
                $dados['tipo_pagamento'],
                $dados['contato'],
                $dados['tipo_contato'],
                $dados['app']

            ]);
        }
    }

    public function armazenarEatualizarTotal(array $dados): void
    {
        $mesa = $dados['mesa'][0] ?? $dados['mesa_bebida'][0] ?? $dados['mesa_adicional'][0];
        $id_mesaBebida = $dados['id_mesaBebida'][0] ?? null;
        $id_mesaAdicional = $dados['id_mesaAdicional'][0] ?? null;
        $id_mesa = $dados['id_mesa'][0] ?? $id_mesaBebida ?? $id_mesaAdicional;
        $valorTaxa = (float)$dados['valorTaxa'] ?? 0;

        $valorAtual = 0;

        if (!empty($dados['controleTotal']) && $dados['controleTotal'] === 'controleTotal') { 
            $queryAtualizar = "UPDATE total SET total = :novoTotal WHERE id_mesa = :id_mesa";
            $stmtAtualizar = Conexao::getInstancia()->prepare($queryAtualizar);

            // Recupera o valor atual
            $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
            $valorAtual = $buscaTotal->total ?? 0;
        }else{
            $query = "INSERT INTO total (id_mesa, mesa, total) VALUES (?, ?, ?)";
            $stmt = Conexao::getInstancia()->prepare($query);
        }   

        $total = 0;

        // Lanches
        if (!empty($dados['idCardapioLanche'])) {
            foreach ($dados['idCardapioLanche'] as $idLanche) {
                if (!empty($idLanche)) {
                    $lanche = (new HelpersModelo())->buscaPorId('cardapio_lanche', $idLanche);
                    $total += $lanche->valor ?? 0;
                }
            }
        }

        // Bebidas
        if (!empty($dados['idTamanhoValorBebida'])) {
            foreach ($dados['idTamanhoValorBebida'] as $idBebida) {
                if (!empty($idBebida)) {
                    $bebida = (new HelpersModelo())->buscaPorId('tamanho_bebida', $idBebida);
                    $total += $bebida->valor ?? 0;
                }
            }
        }

        // Adicionais
        if (!empty($dados['idAdd'])) {
            foreach ($dados['idAdd'] as $index => $idAdicional) {
                if (!empty($idAdicional)) {
                    $tipo = $dados['tipo'][$index] ?? '+';
                    $adicional = (new HelpersModelo())->buscaPorId('ingredientes', $idAdicional);
                    $valor = ($tipo === '-') ? 0 : ($adicional->valor ?? 0);
                    $total += $valor;
                }
            }
        }

        $totalFinal = ($total + $valorAtual + $valorTaxa);

        if (!empty($dados['controleTotal']) && $dados['controleTotal'] === 'controleTotal') { 
            $stmtAtualizar->execute([
            'novoTotal' => $totalFinal,
            'id_mesa' => $id_mesa
        ]);  
        }else{
            $stmt->execute([$id_mesa, $mesa, ($total + $valorTaxa)]);
        }
    }


    public function armazenarHora(array $dados){
        
        $query = "UPDATE lanches SET data_hora = :data_hora WHERE mesa = :mesa AND status = 1";

        $stmt = Conexao::getInstancia()->prepare($query);

        foreach ($dados['mesa'] as $index => $mesa) {
            
            $hora = $dados['data_hora'][$index] ?? null;

            if (!empty($hora)) {
                $stmt->execute([
                    'data_hora' => $hora,
                    'mesa' => $mesa
                ]);
            }
        }
    }

    public function atualizarMesa(int $id_mesa, int $novaMesa)
    {
        $tabelas = ['lanches', 'adicionais', 'bebidas', 'total'];

        foreach ($tabelas as $tabela) {
            $query = "UPDATE {$tabela} SET mesa = :mesaNova WHERE id_mesa = :idMesa";

            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                'mesaNova' => $novaMesa,
                'idMesa' => $id_mesa
            ]);
        }
    }

    public function atualizar_em_preparo(int $id_mesa)
    {
        $tabelas = ['lanches', 'entrega_retirada'];

        foreach ($tabelas as $tabela) {
            $query = "UPDATE {$tabela} SET em_preparo = :ativado WHERE id_mesa = :idMesa";

            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                'ativado' => 1,
                'idMesa' => $id_mesa
            ]);
        }
    }

    public function atualizarLanche(array $dados, int $id, int $idCardapio, int $id_mesa)
    {
        $dados['id'] = $id;

        // Buscar dados do lanche atual
        $lancheAtual = (new HelpersModelo())->buscaPorId('lanches', $id);
        $valorLancheAtual = $lancheAtual->valor_lanche ?? 0;

        // Buscar novo lanche no cardápio
        $novoLanche = (new HelpersModelo())->buscaPorId('cardapio_lanche', $idCardapio);

        // Atualizar dados do lanche
        $dados['nome_lanche'] = $novoLanche->lanche ?? 'Desconhecido';
        $dados['valor_lanche'] = $novoLanche->valor ?? 0;

        $queryLanche = "UPDATE lanches SET 
            id_lanche = :id_lanche, 
            nome_lanche = :nome_lanche, 
            valor_lanche = :valor_lanche, 
            detalhes_lanche = :detalhes_lanche, 
            status = :status 
        WHERE id = :id";

        $stmtLanche = Conexao::getInstancia()->prepare($queryLanche);
        $stmtLanche->execute($dados);

        // Atualizar total da mesa
        $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $totalAtual = $buscaTotal->total ?? 0;

        $totalFinal = ($totalAtual - $valorLancheAtual) + $dados['valor_lanche'];

        $queryTotal = "UPDATE total SET total = :novoTotal WHERE id_mesa = :id_mesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'novoTotal' => $totalFinal,
            'id_mesa' => $id_mesa
        ]);
    }

    public function atualizarAdicional(int $chave, int $idCardapio, string $tipo, $id_mesa)
    {
        // Buscar dados do adicional atual
        $adicionalAtual = (new HelpersModelo())->buscaPorChave('adicionais', $chave);
        $valorAdicionalAtual = $adicionalAtual->valor_adicional ?? 0;
        
        $nomeAdicional = null;
        $valorAdicional = null;

         // 🔍 Buscar os dados do adicional no cardápio
        $busca = (new HelpersModelo())->buscaPorId('ingredientes', $idCardapio);

        if ($busca) {
            $nomeAdicional = $busca->ingrediente ?? null;
            
            if (isset($tipo) && $tipo === '-') {
                $valorAdicional = 0;
            }else{
                $valorAdicional = $busca->valor ?? 0;
            }
        }

        $queryAdicional = "UPDATE adicionais SET nome_adicional = :nome_adicional, 
        valor_adicional = :valor_adicional, tipo = :tipo 
        WHERE chave = :chave";

        $stmtAdicional = Conexao::getInstancia()->prepare($queryAdicional);
        $stmtAdicional->execute([
            'chave' => $chave,
            'nome_adicional' => $nomeAdicional,
            'valor_adicional' => $valorAdicional,
            'tipo' => $tipo
        ]);

        // Busca o total atual da mesa
        $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $totalAtual = $buscaTotal->total ?? 0;

        $totalFinal = ($totalAtual - $valorAdicionalAtual) + $valorAdicional;

        $queryTotal = "UPDATE total SET total = :novoTotal WHERE id_mesa = :id_mesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'novoTotal' => $totalFinal,
            'id_mesa' => $id_mesa
        ]);
    }

    public function atualizarBebida(int $chave, int $idCardapio, int $idTamanho, $id_mesa)
    {  
        // Buscar dados da bebida atual
        $bebidaAtual = (new HelpersModelo())->buscaPorChave('bebidas', $chave);
        $valorBebidaAtual = $bebidaAtual->valor_bebida ?? 0;

        $nomeBebida = null;
        $tamanhoBebida = null;
        $valorBebida = null;

        // Buscar dados
        $buscaMarca = (new HelpersModelo())->buscaPorId('marcas_bebida', $idCardapio);
        $buscaTamanho = (new HelpersModelo())->buscaPorId('tamanho_bebida', $idTamanho);

        if ($buscaMarca) {
            $nomeBebida = $buscaMarca->marca ?? null;
        }

        if ($buscaTamanho) {
            $tamanhoBebida = $buscaTamanho->tamanho ?? null;
            $valorBebida = $buscaTamanho->valor ?? null;
        }

        $queryBebida = "UPDATE bebidas SET nome_bebida = :nome_bebida,
            tamanho_bebida = :tamanho_bebida, 
            valor_bebida = :valor_bebida 
        WHERE chave = :chave";

        $stmtBebida = Conexao::getInstancia()->prepare($queryBebida);
        $stmtBebida->execute([
            'nome_bebida' => $nomeBebida,
            'tamanho_bebida' => $tamanhoBebida,
            'valor_bebida' => $valorBebida,
            'chave' => $chave
        ]);

        // Atualizar total da mesa
        $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $totalAtual = $buscaTotal->total ?? 0;

        $totalFinal = ($totalAtual - $valorBebidaAtual) + $valorBebida;

        $queryTotal = "UPDATE total SET total = :novoTotal WHERE id_mesa = :id_mesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'novoTotal' => $totalFinal,
            'id_mesa' => $id_mesa
        ]);
    }


    public function apagarLanche(int $id, int $idAdicional, int $id_mesa){

        $lancheAtual = (new HelpersModelo())->buscaPorId('lanches', $id);
        $valorLancheAtual = $lancheAtual->valor_lanche ?? 0;

        $adicionalAtual = (new HelpersModelo())->buscaPorIdS('adicionais', $idAdicional);

        $valorAdicionalAtual = 0;
        if (is_array($adicionalAtual)) {
            foreach ($adicionalAtual as $adicional) {
                $valorAdicionalAtual += $adicional->valor_adicional ?? 0;
            }
        }

        $queryLanche = "DELETE FROM lanches WHERE id = :id";

        $stmtLanche = Conexao::getInstancia()->prepare($queryLanche);

        $stmtLanche->execute([
            'id' => $id
        ]);

        $queryAdicional = "DELETE FROM adicionais WHERE id = :idAdicional";

        $stmtAdcional = Conexao::getInstancia()->prepare($queryAdicional);

        $stmtAdcional->execute([
            'idAdicional' => $idAdicional
        ]);

        // Atualizar total da mesa
        $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $totalAtual = $buscaTotal->total ?? 0;

        $totalFinal = ($totalAtual - $valorLancheAtual - $valorAdicionalAtual);

        $queryTotal = "UPDATE total SET total = :novoTotal WHERE id_mesa = :id_mesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'novoTotal' => $totalFinal,
            'id_mesa' => $id_mesa
        ]);
    }

    public function apagar999(int $idAdicional, int $id_mesa){

        $adicionalAtual = (new HelpersModelo())->buscaPorIdS('adicionais', $idAdicional);

        $valorAdicionalAtual = 0;
        if (is_array($adicionalAtual)) {
            foreach ($adicionalAtual as $adicional) {
                $valorAdicionalAtual += $adicional->valor_adicional ?? 0;
            }
        }

        $queryAdicional = "DELETE FROM adicionais WHERE id = :idAdicional";

        $stmtAdcional = Conexao::getInstancia()->prepare($queryAdicional);

        $stmtAdcional->execute([
            'idAdicional' => $idAdicional
        ]);

        // Atualizar total da mesa
        $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $totalAtual = $buscaTotal->total ?? 0;

        $totalFinal = ($totalAtual - $valorAdicionalAtual);

        $queryTotal = "UPDATE total SET total = :novoTotal WHERE id_mesa = :id_mesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'novoTotal' => $totalFinal,
            'id_mesa' => $id_mesa
        ]);
    }

    public function apagarAdicional(int $chave, $id_mesa){

        $adicionalAtual = (new HelpersModelo())->buscaPorChave('adicionais', $chave);
        $valorAdicionalAtual = $adicionalAtual->valor_adicional ?? 0;

        $queryAdicional = "DELETE FROM adicionais WHERE chave = :chave";

        $stmtAdicional = Conexao::getInstancia()->prepare($queryAdicional);

        $stmtAdicional->execute([
            'chave' => $chave
        ]);

        // Atualizar total da mesa
        $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $totalAtual = $buscaTotal->total ?? 0;

        $totalFinal = ($totalAtual - $valorAdicionalAtual);

        $queryTotal = "UPDATE total SET total = :novoTotal WHERE id_mesa = :id_mesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'novoTotal' => $totalFinal,
            'id_mesa' => $id_mesa
        ]);
    }

    public function apagarBebida(int $chave, $id_mesa){

        // Buscar dados da bebida atual
        $bebidaAtual = (new HelpersModelo())->buscaPorChave('bebidas', $chave);
        $valorBebidaAtual = $bebidaAtual->valor_bebida ?? 0;

        $queryBebida = "DELETE FROM bebidas WHERE chave = :chave";

        $stmtBebida = Conexao::getInstancia()->prepare($queryBebida);

        $stmtBebida->execute([
            'chave' => $chave
        ]);

        // Atualizar total da mesa
        $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $totalAtual = $buscaTotal->total ?? 0;

        $totalFinal = ($totalAtual - $valorBebidaAtual);

        $queryTotal = "UPDATE total SET total = :novoTotal WHERE id_mesa = :id_mesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'novoTotal' => $totalFinal,
            'id_mesa' => $id_mesa
        ]);
    }

    public function apagarMesa(int $id_mesa)
    {
        $tabelas = ['lanches', 'adicionais', 'bebidas', 'total'];

        foreach ($tabelas as $tabela)
        {
            $query = "DELETE FROM {$tabela} WHERE id_mesa = :mesaApagar";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                'mesaApagar' => $id_mesa
            ]);
        } 
    }

    public function apagarEntrega(int $id_mesa)
    {
        $tabelas = ['lanches', 'adicionais', 'bebidas', 'total', 'entrega_retirada'];

        foreach ($tabelas as $tabela)
        {
            $query = "DELETE FROM {$tabela} WHERE id_mesa = :mesaApagar";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                'mesaApagar' => $id_mesa
            ]);
        } 
    }

    public function abrirMesa(int $id_mesa)
    {
        $tabelas = ['lanches', 'bebidas', 'total', 'entrega_retirada'];

        $buscaTotalSub = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $valorAtualSub = $buscaTotalSub->subTotal ?? 0;
        $subTotal = $valorAtualSub;

        foreach ($tabelas as $tabela)
        {
            $query = "UPDATE {$tabela} SET status = :ativado WHERE id_mesa = :idMesa";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                'ativado' => 1,
                'idMesa' => $id_mesa
            ]);
        } 

        $querySub = "UPDATE total SET subTotal = :sub WHERE id_mesa = :idMesa";
        $stmtSub = Conexao::getInstancia()->prepare($querySub);
        $stmtSub->execute([
            'sub' => 0,
            'idMesa' => $id_mesa
        ]);

        $queryTotal = "UPDATE total SET total = :tot WHERE id_mesa = :idMesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'tot' => $subTotal,
            'idMesa' => $id_mesa
        ]);
    }

    public function abrirEntrega(int $id_mesa)
    {
        $tabelas = ['lanches', 'bebidas', 'total', 'entrega_retirada'];

        foreach ($tabelas as $tabela)
        {
            $query = "UPDATE {$tabela} SET status = :ativado WHERE id_mesa = :idMesa";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                'ativado' => 1,
                'idMesa' => $id_mesa
            ]);
        } 
    }

    function caixaTotal(int $id_mesa):void
    {
        $tabelas = ['lanches', 'bebidas', 'total', 'entrega_retirada'];

        $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $valorAtual = $buscaTotal->total ?? 0;
        $total = $valorAtual;

        $buscaTotalSub = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $valorAtualSub = $buscaTotalSub->subTotal ?? 0;
        $subTotal = $valorAtualSub;

        $totalFinal = $total + $subTotal;

        foreach ($tabelas as $tabela)
        {
            $query = "UPDATE {$tabela} SET status = :desativado WHERE id_mesa = :idMesa";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                'desativado' => 0,
                'idMesa' => $id_mesa
            ]);
            
        } 

        $querySub = "UPDATE total SET subTotal = :sub WHERE id_mesa = :idMesa";
        $stmtSub = Conexao::getInstancia()->prepare($querySub);
        $stmtSub->execute([
            'sub' => $totalFinal,
            'idMesa' => $id_mesa
        ]);

        $queryTotal = "UPDATE total SET total = :tot WHERE id_mesa = :idMesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'tot' => 0,
            'idMesa' => $id_mesa
        ]);
    }

    function caixaSubTotal(array $dados, int $id_mesa):void
    {
        // Recupera o valor atual
        $buscaTotalSub = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $valorAtualSub = $buscaTotalSub->subTotal ?? 0;
        $subTotal = $valorAtualSub;

        $buscaTotal = (new HelpersModelo())->buscaId_mesa('total', $id_mesa);
        $valorAtual = $buscaTotal->total ?? 0;
        $total = $valorAtual;

        if (!empty($dados['id_lanche']) && is_array($dados['id_lanche'])) {
            foreach ($dados['id_lanche'] as $index => $id) {
                $id_lanche = trim($id);
                $id_cardapio = (int) ($dados['id_cardapio'][$index] ?? 0);
                $id_adicional = (int) ($dados['id_adicional'][$index] ?? 0);

                // Buscar dados do lanche atual pelo id_cardapio correspondente
                $lancheAtual = (new HelpersModelo())->buscaPorId('lanches', $id_cardapio);
                $valorLancheAtual = $lancheAtual->valor_lanche ?? 0;

                $adicionalAtual = (new HelpersModelo())->buscaPorId('adicionais', $id_adicional);
                $valorAdicionalAtual = $adicionalAtual->valor_adicional ?? 0;

                $subTotal += ($valorLancheAtual + $valorAdicionalAtual);
                $total -= ($valorLancheAtual + $valorAdicionalAtual);

                $queryCobrado = "UPDATE lanches SET status = :cobrado WHERE id_lanche = :idLanche";
                $stmtCobrado = Conexao::getInstancia()->prepare($queryCobrado);
                $stmtCobrado->execute([
                    'cobrado' => 2,
                    'idLanche' => $id_lanche
                ]);
            }
        }

        if (!empty($dados['id_bebida']) && is_array($dados['id_bebida'])) {
            foreach ($dados['id_bebida'] as $index => $idBebida) {
                $id_bebida = trim($idBebida); 
                $chave = (int) ($dados['chave'][$index] ?? 0);

                $bebidaAtual = (new HelpersModelo())->buscaPorChave('bebidas', $chave);
                $valorBebidaAtual = $bebidaAtual->valor_bebida ?? 0;

                $subTotal += $valorBebidaAtual;
                $total -= $valorBebidaAtual;

                $queryBebida = "UPDATE bebidas SET status = :cobrado WHERE id = :idBebida";
                $stmtBebida = Conexao::getInstancia()->prepare($queryBebida);
                $stmtBebida->execute([
                    'cobrado' => 2,
                    'idBebida' => $id_bebida
                ]);
            }
        }

        $querySub = "UPDATE total SET subTotal = :sub WHERE id_mesa = :idMesa";
        $stmtSub = Conexao::getInstancia()->prepare($querySub);
        $stmtSub->execute([
            'sub' => $subTotal,
            'idMesa' => $id_mesa
        ]);

        $queryTotal = "UPDATE total SET total = :tot WHERE id_mesa = :idMesa";
        $stmtTotal = Conexao::getInstancia()->prepare($queryTotal);
        $stmtTotal->execute([
            'tot' => $total,
            'idMesa' => $id_mesa
        ]);
   
    }

    public function despachar(int $id_mesa)
    {
        $tabelas = ['lanches', 'bebidas', 'total', 'entrega_retirada'];

        foreach ($tabelas as $tabela)
        {
            $query = "UPDATE {$tabela} SET status = :desativado WHERE id_mesa = :idMesa";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute([
                'desativado' => 0,
                'idMesa' => $id_mesa
            ]);
        } 
    }


}


// echo '<pre>';
        // print_r($stmt);
        // echo '</pre>';
        // die(); // Para interromper e ver o resultado

