<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');
App::uses('PlanoContas', 'Model');

/**
 * CakePHP CentroCustos
 * @author BersaN & StarK
 */
class CentroCustos extends AppModel {

    public $useTable = "centro_custos";
    public $primaryKey = "iddespesacusto";
    
    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM centro_custos c inner join plano_contas pl on (c.id_plano_contas = pl.idplanocontas) where c.ativo = 1 order by pl.descricao");
        return $dados;
    }
    
    public function retornarTodosReceitas(){
        $dados = $this->query("SELECT * FROM centro_custos c inner join plano_contas pl on (c.id_plano_contas = pl.idplanocontas) where c.ativo = 1 and pl.tipo = 'R' order by pl.descricao");
        return $dados;
    }
    
    public function retornarTodosDespesas(){
        $dados = $this->query("SELECT * FROM centro_custos c inner join plano_contas pl on (c.id_plano_contas = pl.idplanocontas) where c.ativo = 1 and pl.tipo = 'D' order by pl.descricao");
        return $dados;
    }

    public function listarJQuery($idplanocontas, $search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE c.id_plano_contas = $idplanocontas" : " WHERE c.id_plano_contas = $idplanocontas AND c.descricao LIKE '%{$search}%'";
        return $this->query("SELECT c.descricao, c.iddespesacusto, c.id_plano_contas, c.valor_planejado, c.ativo, c.observacao, p.descricao as plano_contas  FROM centro_custos c INNER JOIN plano_contas p ON(p.idplanocontas = c.id_plano_contas) {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($idplanocontas, $search = null) {
        $query = (is_null($search)) ? " WHERE c.id_plano_contas = $idplanocontas" : " WHERE c.id_plano_contas = $idplanocontas AND c.descricao LIKE '%{$search}%'";
        $dados = $this->query("SELECT count(c.iddespesacusto) as totalRegistro FROM centro_custos c {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(iddespesacusto) as totalRegistro FROM centro_custos");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function excluir($id) {
        return $this->query("UPDATE centro_custos SET Ativo = 0 WHERE iddespesacusto = $id");
    }

    public function listarPorId($id) {
        $dados = $this->query("SELECT * FROM centro_custos WHERE iddespesacusto = $id LIMIT 1");
        return $dados[0]["centro_custos"];
    }

}
