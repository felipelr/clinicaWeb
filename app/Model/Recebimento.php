<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');
App::uses('Financeiro', 'Model');
App::uses('EventoDisponibilidade', 'Model');
App::uses('PlanoSessao', 'Model');

/**
 * CakePHP Recebimento
 * @author Felipe
 */
class Recebimento extends AppModel {

    public $useTable = "recebimento";
    public $primaryKey = "idrecebimento";

    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM recebimento WHERE idrecebimento = $id and ativo = 1 LIMIT 1");
        return $dados[0]["recebimento"];
    }
    
    public function retornarDetalhadoPorId($id) {
        $dados = $this->query("SELECT r.*, ca.descricao, cc.descricao, p.nome, p.sobrenome
            FROM recebimento as r
            inner join categoria_aula as ca on (r.id_categoria_aula = ca.idcategoriaaula)
            inner join centro_custos as cc on (r.id_centro_custo = cc.iddespesacusto)
            inner join profissional_recebimento as pr on (r.idrecebimento = pr.id_recebimento)
            inner join profissional as p on (pr.id_profissional = p.idprofissional)
            WHERE r.idrecebimento = $id and r.ativo = 1 LIMIT 1;");
        return $dados[0];
    }

    public function excluir($id, $iduser) {
        $motivo = "Exclusão de recebimento";
        return $this->query("UPDATE recebimento SET ativo = 0 WHERE idrecebimento = $id; 
                DELETE FROM financeiro where id_recebimento = $id; 
                DELETE FROM caixa_movimentacao where id_recebimento = $id;
                INSERT INTO recebimento_log (id_recebimento, id_usuario, motivo, valor_anterior, valor_atual) VALUES ($id, $iduser, '$motivo', (SELECT valor FROM recebimento WHERE idrecebimento = $id), (SELECT valor FROM recebimento WHERE idrecebimento = $id));");
    }

    public function atualizarRecebimento($idrecebimento) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        try {
            $this->query("update recebimento set valor = (select sum(valor) from financeiro where id_recebimento = $idrecebimento), 
                quantidade_parcela = (select count(*) from financeiro where id_recebimento = $idrecebimento) where idrecebimento = $idrecebimento;
                update financeiro set total_parcela = (select quantidade_parcela from recebimento where idrecebimento = $idrecebimento) where id_recebimento = $idrecebimento;");
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
        $query = (is_null($search)) ? " WHERE r.ativo = 1 and r.id_clinica = {$idClinica} " : " WHERE r.ativo = 1 and r.id_clinica = {$idClinica} and (CONCAT(TRIM(p.nome), ' ', TRIM(p.sobrenome)) LIKE '%{$search}%' OR r.descricao LIKE '%{$search}%') ";
        return $this->query("SELECT r.idrecebimento, r.descricao, r.valor, p.nome, p.sobrenome, x.data_vencimento AS data_vencimento, x.pago, 
            CONCAT_WS('/', (select COUNT(parcela) from financeiro where id_recebimento = r.idrecebimento and pago = 1 AND ativo = 1), 
            (select total_parcela from financeiro where id_recebimento = r.idrecebimento AND ativo = 1 GROUP BY id_recebimento)) as parcelas_pagas
            FROM recebimento r 
            INNER JOIN paciente p on p.idpaciente = r.id_paciente 
            INNER JOIN (SELECT CASE WHEN pago = 0 THEN MIN(data_vencimento) ELSE MAX(data_vencimento) END as data_vencimento, pago, id_recebimento from financeiro GROUP BY id_recebimento ORDER BY data_vencimento asc) AS x ON(x.id_recebimento = r.idrecebimento) {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null, $idClinica) {
        $query = (is_null($search)) ? " WHERE r.ativo = 1 and r.id_clinica = {$idClinica} " : " WHERE r.ativo = 1 and r.id_clinica = {$idClinica} and (CONCAT(p.nome, ' ', p.sobrenome) LIKE '%{$search}%' OR r.descricao LIKE '%{$search}%') ";
        $dados = $this->query("SELECT count(r.idrecebimento) as totalRegistro FROM recebimento r 
            INNER JOIN paciente p ON(r.id_paciente = p.idpaciente) 
            INNER JOIN (SELECT CASE WHEN pago = 0 THEN MIN(data_vencimento) ELSE MAX(data_vencimento) END as data_vencimento, pago, id_recebimento from financeiro GROUP BY id_recebimento ORDER BY data_vencimento asc) AS x ON(x.id_recebimento = r.idrecebimento) 
            {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro($idClinica) {
        $dados = $this->query("SELECT count(r.idrecebimento) as totalRegistro 
            FROM recebimento r 
            INNER JOIN paciente p ON(r.id_paciente = p.idpaciente) 
            INNER JOIN (SELECT CASE WHEN pago = 0 THEN MIN(data_vencimento) ELSE MAX(data_vencimento) END as data_vencimento, pago, id_recebimento from financeiro GROUP BY id_recebimento ORDER BY data_vencimento asc) AS x ON(x.id_recebimento = r.idrecebimento) 
            WHERE r.ativo = 1 and r.id_clinica = {$idClinica} ");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function cadastrar($data, $id_sessao, &$idrecebimento) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();

        try {
            $data_competencia = DateTime::createFromFormat('d/m/Y', $data["Recebimento"]['data_competencia']);
            $array_lojas = $data['Recebimento']['id_clinica'];
            $recebimento = new Recebimento();
            $eventoDisponibilidade = new EventoDisponibilidade();
            $planoSessao = new PlanoSessao();

            if ($data["Recebimento"]["tipo"] == "COMUM") {
                unset($data['Recebimento']['id_plano_sessao']);
                if ($data['Recebimento']['valor_referente'] == "PARCELA") {
                    $data['Recebimento']['quantidade_sessoes'] = $data['Recebimento']['quantidade_sessoes'] * $data['Recebimento']['quantidade_parcela'];
                }
            } else if (isset($data['Recebimento']['id_plano_sessao'])) {
                $dadosPlanoSessao = $planoSessao->retornarPorId($data['Recebimento']['id_plano_sessao']);
                $data['Recebimento']['quantidade_sessoes'] = $dadosPlanoSessao['quantidade_sessoes'];
            }

            unset($data['Recebimento']['tipo']);
            unset($data['Recebimento']['valor']);
            unset($data['Recebimento']['quantidade_parcela']);
            unset($data['Recebimento']['valor_referente']);

            foreach ($array_lojas as $loja) {
                $valorRecebimento = 0;
                $size = count($data['Financeiro']);
                for ($i = 0; $i < $size; $i++) {
                    $valorRecebimento += $data['Financeiro'][$i]['valor'];
                }
                $data['Recebimento']['valor'] = $valorRecebimento;
                $data['Recebimento']['quantidade_parcela'] = $size;
                $data['Recebimento']['id_clinica'] = (int) $loja;
                $data["Recebimento"]['data_competencia'] = $data_competencia->format('Y-m-d H:i:s');
                $recebimento->create();
                $recebimento->save($data["Recebimento"]);
                $idrecebimento = $recebimento->id;

                //gerando registro na tabela evento_disponibilidade
                if (isset($data['Recebimento']['id_plano_sessao']) && isset($data['Recebimento']['id_paciente'])) {
                    $dadosPlanoSessao = $planoSessao->retornarPorId($data['Recebimento']['id_plano_sessao']);
                    $eventoDisponibilidade->create();
                    $eventoDisponibilidade->save(array(
                        'id_paciente' => $data['Recebimento']['id_paciente'],
                        'id_plano_sessao' => $data['Recebimento']['id_plano_sessao'],
                        'total' => $dadosPlanoSessao['quantidade_sessoes'],
                        'total_sessoes' => $dadosPlanoSessao['quantidade_sessoes'],
                        'id_recebimento' => $recebimento->id));
                } else if (isset($data['Recebimento']['id_paciente'])) {
                    $eventoDisponibilidade->create();
                    $eventoDisponibilidade->save(array(
                        'id_paciente' => $data['Recebimento']['id_paciente'],
                        'total' => $data['Recebimento']['quantidade_sessoes'],
                        'total_sessoes' => $data['Recebimento']['quantidade_sessoes'],
                        'id_recebimento' => $recebimento->id));
                }

                $financeiro = new Financeiro();
                $financeiro->gerarFinanceiroRecebimento($recebimento->id, $data['Recebimento']['id_caixa_loja'], $id_sessao, $data['Financeiro']);
                $financeiro->inserir_recebimento_profissional($recebimento->id, $data['Recebimento']['id_profissional'], $data['Recebimento']['id_clinica'], $id_sessao);
            }

            $dataSource->commit();
            return true;
        } catch (Exception $e) {
            $dataSource->rollback();
            CakeLog::debug($e->getMessage());
            return false;
        }
    }
    
    public function alterar($data){
        $dataSource = $this->getDataSource();
        $dataSource->begin();

        try {
            $this->query("update recebimento set descricao = '{$data['descricao']}', id_categoria_aula = {$data['id_categoria_aula']}, id_centro_custo = {$data['id_centro_custo']} where idrecebimento = {$data['idrecebimento']};
                    update profissional_recebimento set id_profissional = {$data['id_profissional']} where id_recebimento = {$data['idrecebimento']};");

            $dataSource->commit();
            return true;
        } catch (Exception $e) {
            $dataSource->rollback();
            CakeLog::debug($e->getMessage());
            return false;
        }
    }

    public function relatorio_recebimentos($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $dataPagamentoDE, $dataPagamentoATE, $tipoFinanceiro_, $planoContas_, $paciente_, $cheque_, $clinica_, $nome_recebimento_, $pago_) {
        $query = "";
        if ((int) $tipoFinanceiro_ != 0) {
            $query = $query . " and financeiro.id_financeiro_tipo = $tipoFinanceiro_ ";
        }
        if ((int) $planoContas_ != 0) {
            $query = $query . " and p.idplanocontas = $planoContas_ ";
        }
        if ((int) $paciente_ != 0) {
            $query = $query . " and pc.idpaciente =  $paciente_ ";
        }
        if ((int) $cheque_ == 0) {
            $query = $query . " and financeiro.cheque =  0 ";
        }
        if ((int) $pago_ == 0) {
            $query = $query . " and financeiro.pago = 0";
        }
        if ((int) $clinica_ != 0) {
            $query = $query . " and clinica.idclinica =  $clinica_ ";
        }
        if ($dataPagamentoDE != '' && $dataPagamentoATE != '') {
            $query = $query . " and CAST(financeiro.data_pagamento as DATE) between CAST('$dataPagamentoDE' as DATE) and CAST('$dataPagamentoATE' as DATE) ";
        }
        if ($dataVencimentoDE != '' && $dataVencimentoATE != '') {
            $query = $query . " and CAST(financeiro.data_vencimento as DATE) between CAST('$dataVencimentoDE' as DATE) and CAST('$dataVencimentoATE' as DATE) ";
        }
        if ($dataCadastroDE != '' && $dataCadastroATE != '') {
            $query = $query . " and CAST(financeiro.created as DATE) between CAST('$dataCadastroDE' as DATE) and CAST('$dataCadastroATE' as DATE) ";
        }

        $sql = "select clinica.fantasia, clinica.idclinica, r.descricao, p.descricao, p.idplanocontas, c.descricao, pc.nome, pc.sobrenome,
                        financeiro.data_pagamento, financeiro.data_vencimento, tipo_financeiro.tipo,
			SUM(financeiro.valor) as valor_total,  
			SUM(CASE WHEN financeiro.pago = 1 THEN financeiro.valor ELSE 0 END) AS total_pago, 
			SUM(CASE WHEN financeiro.pago = 0 THEN financeiro.valor ELSE 0 END) AS total_restante
                from recebimento r inner join financeiro ON(r.idrecebimento = financeiro.id_recebimento)
                inner join centro_custos c on(r.id_centro_custo = c.iddespesacusto)
                inner join plano_contas p on(p.idplanocontas = c.id_plano_contas)
                inner join paciente pc on(pc.idpaciente = r.id_paciente)
                inner join clinica on(clinica.idclinica = r.id_clinica)               
                INNER JOIN tipo_financeiro ON(tipo_financeiro.idfinanceirotipo = financeiro.id_financeiro_tipo)
                where r.descricao like '%$nome_recebimento_%'                 
                and r.ativo = 1 $query 
                GROUP BY clinica.idclinica, p.idplanocontas, c.descricao, r.descricao, r.idrecebimento
                ORDER BY clinica.idclinica, p.idplanocontas";

        return $this->query("$sql");
    }

    public function quantidadeRecebimentosPorPlanoSessao($idClinica) {
//        $sql = "SELECT  IFNULL(p.descricao,'PLANO COMUM') AS plano ,SUM(f.valor) AS valor
//            FROM recebimento r
//            INNER JOIN financeiro f ON(f.id_recebimento = r.idrecebimento)
//            LEFT JOIN plano_sessao p ON(r.id_plano_sessao = p.idplanosessao)
//            WHERE CAST(f.data_pagamento as DATE) between (DATE_FORMAT(NOW() ,'%Y-%m-01')) and LAST_DAY(now())
//            GROUP BY ifnull(r.id_plano_sessao,0)";

        $sql = "SELECT  IFNULL(c.descricao,'CATEGORIA NÃO INFORMADA') AS plano ,SUM(f.valor) AS valor
            FROM recebimento r
            INNER JOIN financeiro f ON(f.id_recebimento = r.idrecebimento)
            LEFT JOIN categoria_aula c ON(r.id_categoria_aula = c.idcategoriaaula)
            WHERE CAST(f.data_pagamento as DATE) between (DATE_FORMAT(NOW() ,'%Y-%m-01')) and LAST_DAY(now())
            and r.id_clinica = {$idClinica}
            GROUP BY ifnull(r.id_categoria_aula,0);";

        CakeLog::debug($sql);

        $dados = $this->query($sql);

        return $dados;
    }

    public function retornarPorIdPaciente($idpaciente) {
        $dados = $this->query("select r.idrecebimento, r.descricao, ed.total, ed.ideventodisponibilidade
                 from recebimento r
                 inner join evento_disponibilidade ed on (r.idrecebimento = ed.id_recebimento) 
                 where r.id_paciente = {$idpaciente} and r.ativo = 1
                 order by r.descricao");
        return $dados;
    }

    public function recebimentosPorPaciente($idpaciente) {
        $dados = $this->query("select r.idrecebimento, r.descricao, r.valor, f.parcela, f.total_parcela, f.valor, f.data_vencimento, f.pago
            from recebimento as r 
            inner join financeiro as f on (r.idrecebimento = f.id_recebimento)
            where r.id_paciente = $idpaciente and r.ativo = 1 and f.ativo = 1 
            order by r.idrecebimento, f.pago, f.data_vencimento");
        return $dados;
    }

    public function listarRenovacaoContratoJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null, $vencimentoDe = null, $vencimentoAte = null) {
        $order = (is_null($ordenacao)) ? "" : " ORDER BY pago asc, data_vencimento ";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : " LIMIT $inicio, $totalRegistros ";
        $query = (is_null($search)) ? " " : " and (CONCAT(TRIM(p.nome), ' ', TRIM(p.sobrenome)) LIKE '%{$search}%' OR r.descricao LIKE '%{$search}%') ";

        if ($vencimentoDe != null && $vencimentoAte != null) {
            $query .= " and cast(x.data_vencimento as date) between cast('$vencimentoDe' as date) and cast('$vencimentoAte' as date) ";
        } else if ($vencimentoDe != null) {
            $query .= " and cast(x.data_vencimento as date) >= cast('$vencimentoDe' as date) ";
        } else if ($vencimentoAte != null) {
            $query .= " and cast(x.data_vencimento as date) <= cast('$vencimentoAte' as date) ";
        }

        return $this->query("SELECT r.idrecebimento, r.descricao, r.valor, p.nome, p.sobrenome, x.data_vencimento AS data_vencimento, x.pago, 
            CONCAT_WS('/', (select COUNT(parcela) from financeiro where id_recebimento = r.idrecebimento and pago = 1 AND ativo = 1), 
            (select total_parcela from financeiro where id_recebimento = r.idrecebimento AND ativo = 1 GROUP BY id_recebimento)) as parcelas_pagas
            FROM recebimento r 
            INNER JOIN paciente p on p.idpaciente = r.id_paciente 
            INNER JOIN (SELECT MAX(data_vencimento) as data_vencimento, pago, id_recebimento 
                from financeiro GROUP BY id_recebimento ORDER BY data_vencimento asc) AS x ON(x.id_recebimento = r.idrecebimento)
            WHERE r.ativo = 1 
            and year(x.data_vencimento) = year(now()) 
            and (month(x.data_vencimento) = month(now()) or month(x.data_vencimento) = month(DATE_ADD(now(), INTERVAL 1 MONTH))) {$query} {$order} {$sql};");
    }

    public function totalRegistroRenovacaoContratoFiltrado($search = null) {
        $query = (is_null($search)) ? " " : " and (CONCAT(p.nome, ' ', p.sobrenome) LIKE '%{$search}%' OR r.descricao LIKE '%{$search}%') ";
        $dados = $this->query("SELECT count(r.idrecebimento) as totalRegistro 
            FROM recebimento r INNER JOIN paciente p ON(r.id_paciente = p.idpaciente)
            INNER JOIN (SELECT MAX(data_vencimento) as data_vencimento, pago, id_recebimento 
                from financeiro GROUP BY id_recebimento ORDER BY data_vencimento asc) AS x ON(x.id_recebimento = r.idrecebimento)
            WHERE r.ativo = 1 
            and year(x.data_vencimento) = year(now()) 
            and (month(x.data_vencimento) = month(now()) or month(x.data_vencimento) = month(DATE_ADD(now(), INTERVAL 1 MONTH))) {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistroRenovacaoContrato() {
        $dados = $this->query("SELECT count(r.idrecebimento) as totalRegistro 
            FROM recebimento r
            INNER JOIN (SELECT MAX(data_vencimento) as data_vencimento, pago, id_recebimento 
                from financeiro GROUP BY id_recebimento ORDER BY data_vencimento asc) AS x ON(x.id_recebimento = r.idrecebimento)
            WHERE ativo = 1 
            and year(x.data_vencimento) = year(now()) 
            and (month(x.data_vencimento) = month(now()) or month(x.data_vencimento) = month(DATE_ADD(now(), INTERVAL 1 MONTH))) ");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function retornarDetalhesRecebimento($idrecebimento) {
        return $this->query("select recebimento.*, financeiro.id_financeiro_tipo, financeiro.data_vencimento, 
            DATE_ADD(financeiro.data_vencimento, INTERVAL 1 MONTH) as data_competencia_nova
            from recebimento 
            inner join financeiro on(recebimento.idrecebimento = financeiro.id_recebimento)
            inner join tipo_financeiro on(tipo_financeiro.idfinanceirotipo = IFNULL(financeiro.id_financeiro_tipo_pagamento,financeiro.id_financeiro_tipo))                
            where recebimento.idrecebimento = $idrecebimento 
            and recebimento.ativo = 1 
            order by financeiro.data_vencimento desc 
            limit 1;");
    }

    public function atualizarSessoesRecebimento($idrecebimento, $quantidadeSessoes) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        try {
            $this->query("update recebimento set quantidade_sessoes = quantidade_sessoes + $quantidadeSessoes where idrecebimento = $idrecebimento;");
            $dataSource->commit();
            return true;
        } catch (Exception $e) {
            $dataSource->rollback();
            return false;
        }
    }

}
