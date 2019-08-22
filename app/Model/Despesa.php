<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Despesa
 * @author BersaN & StarK
 */
class Despesa extends AppModel {

    public $useTable = "despesa";
    public $primaryKey = "iddespesa";

    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM despesa WHERE iddespesa = $id and ativo = 1 LIMIT 1");
        return $dados[0]["despesa"];
    }

    public function excluir($id, $iduser) {
        $motivo = "Exclusão de despesa";
        return $this->query("UPDATE despesa SET ativo = 0 WHERE iddespesa = $id;  
                DELETE FROM financeiro where id_despesa = $id; 
                DELETE FROM caixa_movimentacao where id_despesa = $id;
                INSERT INTO despesa_log (id_despesa, id_usuario, motivo, valor_anterior, valor_atual, created) 
                VALUES ($id, $iduser, '$motivo', (SELECT valor FROM despesa WHERE iddespesa = $id), (SELECT valor FROM despesa WHERE iddespesa = $id), NOW());  ");
    }
    
    public function atualizarDespesa($iddespesa) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        try {
            $this->query("update despesa set valor = (select sum(valor) from financeiro where id_despesa = $iddespesa), 
                quantidade_parcela = (select count(*) from financeiro where id_despesa = $iddespesa) where iddespesa = $iddespesa;
                update financeiro set total_parcela = (select quantidade_parcela from despesa where iddespesa = $iddespesa) where id_despesa = $iddespesa;");
            $dataSource->commit();
            return true;
        } catch (Exception $e) {
            $dataSource->rollback();
            return false;
        }
    }

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null, $idClinica) {
        $order = (is_null($ordenacao)) ? "" : " ORDER BY pago asc, data_vencimento ";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : " LIMIT $inicio, $totalRegistros ";
        $query = (is_null($search)) ? " WHERE d.ativo = 1 and d.id_clinica = {$idClinica} " : " WHERE d.ativo = 1 and d.id_clinica = {$idClinica} and d.descricao LIKE '%{$search}%' ";
        return $this->query("SELECT d.descricao,d.iddespesa, f.nome, d.valor, x.data_vencimento AS data_vencimento, x.pago, CONCAT_WS('/', (select COUNT(parcela) from financeiro where id_despesa = d.iddespesa and pago = 1 AND ativo = 1), d.quantidade_parcela ) as parcelas_pagas
            FROM despesa d inner join favorecido f on f.idfavorecido = d.id_favorecido 
            INNER JOIN (SELECT CASE WHEN pago = 0 THEN MIN(data_vencimento) ELSE MAX(data_vencimento) END as data_vencimento, pago, id_despesa from financeiro GROUP BY id_despesa ORDER BY data_vencimento asc) AS x ON(x.id_despesa = d.iddespesa)  {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null, $idClinica) {
        $query = (is_null($search)) ? " WHERE d.ativo = 1 and d.id_clinica = {$idClinica} " : " WHERE d.ativo = 1 and d.id_clinica = {$idClinica} and d.descricao LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(d.iddespesa) as totalRegistro FROM despesa d 
            INNER JOIN (SELECT CASE WHEN pago = 0 THEN MIN(data_vencimento) ELSE MAX(data_vencimento) END as data_vencimento, pago, id_despesa from financeiro GROUP BY id_despesa ORDER BY data_vencimento asc) AS x ON(x.id_despesa = d.iddespesa) {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro($idClinica) {
        $dados = $this->query("SELECT count(d.iddespesa) as totalRegistro FROM despesa d 
            INNER JOIN (SELECT CASE WHEN pago = 0 THEN MIN(data_vencimento) ELSE MAX(data_vencimento) END as data_vencimento, pago, id_despesa from financeiro GROUP BY id_despesa ORDER BY data_vencimento asc) AS x ON(x.id_despesa = d.iddespesa) 
            WHERE d.ativo = 1 and d.id_clinica = {$idClinica} ");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function ultimaDespesa() {
        $dados = $this->query("select * from despesa order by 1 desc limit 1");
        return $dados[0]["despesa"];
    }

    public function cadastrar($data, $id_sessao) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();

        try {

            $data['Despesa']['id_usuario'] = $id_sessao;
            $data_competencia = DateTime::createFromFormat('d/m/Y', $data["Despesa"]['data_competencia']);

            if (isset($data['Despesa']['valor'])) {
                $str_explode = explode(',', $data['Despesa']['valor']);
                $data['Despesa']['valor'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];
                if ($data["Despesa"]['valor_referente'] == "PARCELA") {
                    $data['Despesa']['valor'] = $data['Despesa']['valor'] * $data['Despesa']['quantidade_parcela'];
                }
            }
            $array_lojas = $data['Despesa']['id_clinica'];
            $despesa = new Despesa();

            foreach ($array_lojas as $loja) {
                $data['Despesa']['id_clinica'] = (int) $loja;
                if ((int) $data['repetir'] == 1) {
                    $prazo = (int) $data['Despesa']['prazo'];
                    if ($prazo < 1) {
                        $prazo = 1;
                    }

                    $DespesaRepeticao = (int) $data['DespesaRepeticao'];
                    if ($DespesaRepeticao < 1) {
                        $DespesaRepeticao = 1;
                    }

                    $count_repeticao = 0;
                    while ($count_repeticao < $DespesaRepeticao) {
                        $data["Despesa"]['data_competencia'] = $data_competencia->format('Y-m-d H:i:s');
                        $despesa->create();
                        $despesa->save($data);
                        $financeiro = new Financeiro();
                        $financeiro->GerarFinanceiroDespesa($despesa->id);
                        $data_competencia->modify("+" . $data['Despesa']['prazo'] . " " . $data['tipo_intervalo']);
                        $data["Despesa"]['data_competencia'] = $data_competencia->format('Y-m-d H:i:s');

                        $count_repeticao++;
                    }
                } else {
                    $data["Despesa"]['data_competencia'] = $data_competencia->format('Y-m-d H:i:s');
                    $despesa->create();
                    $despesa->save($data);
                    $financeiro = new Financeiro();
                    $financeiro->GerarFinanceiroDespesa($despesa->id);
                }
            }

            $dataSource->commit();
            return true;
        } catch (Exception $e) {
            $dataSource->rollback();
            return false;
        }
    }

    public function relatorioDespesa($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $dataPagamentoDE, $dataPagamentoATE, $tipoFinanceiro_, $planoContas_, $favorecido_, $contas_pagas_, $clinica_, $nome_despesa_) {
        $query = "";
        $data = "";
        if ((int) $tipoFinanceiro_ != 0) {
            $query = $query . " and financeiro.id_financeiro_tipo = $tipoFinanceiro_ ";
        }
        if ((int) $planoContas_ != 0) {
            $query = $query . " and plano_contas.idplanocontas = $planoContas_ ";
        }
        if ((int) $favorecido_ != 0) {
            $query = $query . " and favorecido.idfavorecido =  $favorecido_ ";
        }
        if ((int) $contas_pagas_ == 0) {
            $query = $query . " and financeiro.pago =  0 ";
            $data = "";
        } else if ($dataPagamentoDE != '' && $dataPagamentoATE != '') { 
            $data = "and CAST(financeiro.data_pagamento as DATE) between CAST('$dataPagamentoDE' as DATE) and CAST('$dataPagamentoATE' as DATE)";
        }
        if ($dataVencimentoDE != '' && $dataVencimentoATE != '') {
            $query = $query . " and CAST(financeiro.data_vencimento as DATE) between CAST('$dataVencimentoDE' as DATE) and CAST('$dataVencimentoATE' as DATE) ";
        }
        if ($dataCadastroDE != '' && $dataCadastroATE != '') {
            $query = $query . " and CAST(financeiro.created as DATE) between CAST('$dataCadastroDE' as DATE) and CAST('$dataCadastroATE' as DATE) ";
        }

        if ((int) $clinica_ != 0) {
            $query = $query . " and clinica.idclinica =  $clinica_ ";
        }

        $sql = "SELECT clinica.idclinica, clinica.fantasia, plano_contas.idplanocontas, plano_contas.descricao, 
                centro_custos.descricao, despesa.descricao, favorecido.nome, tipo_financeiro.tipo,
                financeiro.data_pagamento, financeiro.data_vencimento,
                SUM(financeiro.valor) as valor_total, 
                SUM(CASE WHEN financeiro.pago = 1 THEN financeiro.valor ELSE 0 END) AS total_pago, 
                SUM(CASE WHEN financeiro.pago = 0 THEN financeiro.valor ELSE 0 END) AS total_restante
                FROM despesa
                INNER JOIN financeiro ON(despesa.iddespesa = financeiro.id_despesa)
                INNER JOIN centro_custos ON(centro_custos.iddespesacusto = despesa.id_despesa_custo)
                INNER JOIN plano_contas ON(centro_custos.id_plano_contas = plano_contas.idplanocontas)
                INNER JOIN clinica ON(clinica.idclinica = despesa.id_clinica)
                INNER JOIN favorecido ON(favorecido.idfavorecido = despesa.id_favorecido)                
                INNER JOIN tipo_financeiro ON(tipo_financeiro.idfinanceirotipo = financeiro.id_financeiro_tipo)
                where despesa.descricao like '%$nome_despesa_%' 
                $data
                and despesa.ativo = 1 $query 
                GROUP BY clinica.idclinica, plano_contas.idplanocontas, centro_custos.descricao, despesa.descricao, despesa.iddespesa
                ORDER BY clinica.idclinica, plano_contas.idplanocontas ";
        
        //CakeLog::debug("sql relatorio depesa: $sql");

        return $this->query("$sql");
    }

}
