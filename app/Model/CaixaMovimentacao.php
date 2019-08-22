<?php

App::uses('AppModel', 'Model');
App::uses('TipoFinanceiro', 'Model');
App::uses('Despesa', 'Model');
App::uses('TipoFinanceiro', 'Model');

class CaixaMovimentacao extends AppModel {

    public $useTable = "caixa_movimentacao";
    public $primaryKey = "idcaixa";

    public function GeraEntradaCaixa($id_despesa, $id_caixa_loja, $id_usuario, $id_financeiro, $id_tipo_financeiro, $descricao, $valor) {
        if ($id_despesa != 0) {
            $this->query("INSERT INTO caixa_movimentacao
                        (      id_caixa_loja,  id_clinica,   id_despesa,     id_usuario,    id_tipo_financeiro,   descricao,  valor,    parcela,   total_parcelas, id_caixa_fechamento)
                        SELECT $id_caixa_loja, d.id_clinica, d.iddespesa,    $id_usuario , $id_tipo_financeiro , $descricao, (f.valor) , f.parcela, f.total_parcela, (SELECT MAX(idcaixafechamento) FROM caixa_fechamento WHERE id_clinica = d.id_clinica and fechado = 0)
                        FROM despesa d INNER JOIN financeiro f on(d.iddespesa = f.id_despesa AND f.idfinanceiro = $id_financeiro) WHERE iddespesa = $id_despesa  ");
        } else {
            $this->query("INSERT INTO caixa_movimentacao
                        (      id_caixa_loja,  id_clinica,     id_usuario,  data_pagamento, created,    id_tipo_financeiro,   descricao,  valor, id_caixa_fechamento)
                        SELECT $id_caixa_loja, caixa_loja.id_clinica,   $id_usuario ,     now(),      now() ,    $id_tipo_financeiro , '$descricao', $valor, (SELECT MAX(idcaixafechamento) FROM caixa_fechamento WHERE id_clinica = caixa_loja.id_clinica and fechado = 0)
                        FROM caixa_loja WHERE idcaixaloja = $id_caixa_loja ");
        }
    }

    public function GeraEntradaRecebimento($id_recebimento, $id_usuario, $id_caixa_loja, $id_financeiro, $id_tipo_financeiro, $descricao) {

        $dataSource = $this->getDataSource();
        $dataSource->begin();
        try {
            $this->query("INSERT INTO caixa_movimentacao
                (       id_caixa_loja,  id_clinica,   id_recebimento,     id_usuario,    id_tipo_financeiro,   descricao,  valor,    parcela,   total_parcelas, data_pagamento, id_caixa_fechamento)
                        SELECT $id_caixa_loja, r.id_clinica, r.idrecebimento,   $id_usuario , $id_tipo_financeiro , '$descricao', (f.valor) , f.parcela, f.total_parcela, NOW(), (SELECT MAX(idcaixafechamento) FROM caixa_fechamento WHERE id_clinica = r.id_clinica and fechado = 0)
                        FROM recebimento r INNER JOIN financeiro f on(r.idrecebimento = f.id_recebimento AND f.idfinanceiro = $id_financeiro) WHERE r.idrecebimento = $id_recebimento ");

            $dataSource->commit();
            return true;
        } catch (Exception $e) {
            $dataSource->rollback();
            return false;
        }
    }

    public function GeraSaidaRecebimento($id_recebimento, $id_usuario, $id_caixa_loja, $id_financeiro, $id_tipo_financeiro, $descricao) {

        $dataSource = $this->getDataSource();
        $dataSource->begin();
        try {
            $this->query("INSERT INTO caixa_movimentacao
                (       id_caixa_loja,  id_clinica,   id_recebimento,     id_usuario,    id_tipo_financeiro,   descricao,  valor,    parcela,   total_parcelas, data_pagamento, id_caixa_fechamento)
                        SELECT $id_caixa_loja, r.id_clinica, r.idrecebimento,   $id_usuario , $id_tipo_financeiro , '$descricao', (f.valor) * -1 , f.parcela, f.total_parcela, NOW(), (SELECT MAX(idcaixafechamento) FROM caixa_fechamento WHERE id_clinica = r.id_clinica and fechado = 0)
                        FROM recebimento r INNER JOIN financeiro f on(r.idrecebimento = f.id_recebimento AND f.idfinanceiro = $id_financeiro) WHERE r.idrecebimento = $id_recebimento ");

            $dataSource->commit();
            return true;
        } catch (Exception $e) {
            $dataSource->rollback();
            return false;
        }
    }

    public function Transferencia($id_caixa_loja, $id_caixa_loja_destino, $id_usuario, $id_tipo_financeiro, $descricao, $valor) {

        //Gera saida no caixa selecionado
        $this->query("INSERT INTO caixa_movimentacao
                        (      id_caixa_loja,  id_clinica,     id_usuario,  data_pagamento, created,    id_tipo_financeiro,   descricao,  valor, id_caixa_destino, id_caixa_fechamento)
                        SELECT $id_caixa_loja, caixa_loja.id_clinica,   $id_usuario ,     now(),      now() ,    $id_tipo_financeiro , '$descricao', ($valor * -1), $id_caixa_loja_destino, (SELECT MAX(idcaixafechamento) FROM caixa_fechamento WHERE id_clinica = caixa_loja.id_clinica and fechado = 0)
                        FROM caixa_loja WHERE idcaixaloja = $id_caixa_loja ");

        //Gera entrada no caixa selecionado de Destino
        $this->query("INSERT INTO caixa_movimentacao
                        (      id_caixa_loja,  id_clinica,     id_usuario,  data_pagamento, created,    id_tipo_financeiro,   descricao,  valor, id_caixa_origem, id_caixa_fechamento)
                        SELECT $id_caixa_loja_destino, caixa_loja.id_clinica,   $id_usuario ,     now(),      now() ,    $id_tipo_financeiro , '$descricao',$valor, $id_caixa_loja, (SELECT MAX(idcaixafechamento) FROM caixa_fechamento WHERE id_clinica = caixa_loja.id_clinica and fechado = 0)
                        FROM caixa_loja WHERE idcaixaloja = $id_caixa_loja_destino ");
    }

    //Para inserir uma entrada simples no caixa defina a variavel $id_despesa como 0
    public function GeraSaidaCaixa($id_despesa, $id_caixa_loja, $id_usuario, $id_financeiro, $id_tipo_financeiro, $descricao, $valor) {
        if ($id_despesa != 0) {
            $this->query("INSERT INTO caixa_movimentacao
                        (      id_caixa_loja,  id_clinica,   id_despesa,     id_usuario,  data_pagamento, created,    id_tipo_financeiro,   descricao,  valor,    parcela,   total_parcelas, id_caixa_fechamento)
                        SELECT $id_caixa_loja, d.id_clinica, d.iddespesa,    $id_usuario ,now(),           now() ,    $id_tipo_financeiro , '$descricao', (f.valor * -1) , f.parcela, f.total_parcela, (SELECT MAX(idcaixafechamento) FROM caixa_fechamento WHERE id_clinica = d.id_clinica and fechado = 0)
                        FROM despesa d INNER JOIN financeiro f on(d.iddespesa = f.id_despesa AND f.idfinanceiro = $id_financeiro) WHERE iddespesa = $id_despesa  ");
        } else {
            $this->query("INSERT INTO caixa_movimentacao
                        (      id_caixa_loja,  id_clinica,     id_usuario,  data_pagamento, created,    id_tipo_financeiro,   descricao,  valor, id_caixa_fechamento)
                        SELECT $id_caixa_loja, caixa_loja.id_clinica,   $id_usuario ,     now(),      now() ,    $id_tipo_financeiro , '$descricao', ($valor * -1), (SELECT MAX(idcaixafechamento) FROM caixa_fechamento WHERE id_clinica = caixa_loja.id_clinica and fechado = 0)
                        FROM caixa_loja WHERE idcaixaloja = $id_caixa_loja ");
        }
    }

    public function relatorioCaixa($dataMovimentacaoDE, $dataMovimentacaoATE, $tipoFinanceiro_, $clinica_, $caixa_) {
        $query = "";
        if ((int) $tipoFinanceiro_ != 0) {
            $query = $query . " and c.id_tipo_financeiro = $tipoFinanceiro_ ";
        }
        if ((int) $clinica_ != 0) {
            $query = $query . " and c.id_clinica =  $clinica_ ";
        }

        if ((int) $caixa_ != 0) {
            $query = $query . " and c.id_caixa_loja =  $caixa_ ";
        }

        return $this->query("
                    SELECT cli.fantasia,t.tipo,CASE WHEN id_despesa > 0 THEN concat('Despesa', ': ' , d.descricao) ELSE concat('Movimentação de', ': ' , CASE WHEN (c.id_caixa_destino IS NULL) AND (c.id_caixa_origem IS NULL) THEN CASE WHEN c.valor > 0 THEN 'Entrada' ELSE 'Saída' END ELSE 'Transferência' END) END AS Tipo_movimentacao, cl.nome_caixa,  c.data_pagamento, c.id_despesa, c.idcaixa, 
                                             c.descricao, c.parcela, c.total_parcelas, c.valor, c.id_usuario, u.nome, c.id_clinica, c.id_tipo_financeiro, c.id_caixa_loja
                    FROM caixa_movimentacao c
                    INNER JOIN usuario u ON(c.id_usuario = idusuario)
                    INNER JOIN clinica cli ON(c.id_clinica = cli.idclinica)
                    INNER JOIN tipo_financeiro t ON(t.idfinanceirotipo = c.id_tipo_financeiro)
                    INNER JOIN caixa_loja cl ON(cl.idcaixaloja = c.id_caixa_loja)
                    LEFT JOIN despesa d ON(c.id_despesa = d.iddespesa)
                    WHERE CAST(data_pagamento as DATE) between CAST('$dataMovimentacaoDE' as DATE) and CAST('$dataMovimentacaoATE' as DATE) $query
                    ORDER BY c.id_clinica, c.id_tipo_financeiro, data_pagamento ASC, c.parcela");
    }

    public function retornarFinanceiros($id_caixa) {
        return $this->query("
                    SELECT t.tipo as Tipo_movimentacao, SUM(cm.valor) as Valor FROM caixa_movimentacao cm
                    INNER JOIN caixa_loja c ON(c.idcaixaloja = cm.id_caixa_loja) 
                    INNER JOIN tipo_financeiro t ON(t.idfinanceirotipo = cm.id_tipo_financeiro)
                    WHERE CAST(data_pagamento as DATE) = CAST(NOW() as DATE) AND cm.id_caixa_loja = $id_caixa
                    GROUP BY t.idfinanceirotipo
                    ");
    }

}
