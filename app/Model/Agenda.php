<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Agenda
 * @author Felipe
 */
class Agenda extends AppModel {

    public $useTable = "agenda";
    public $primaryKey = "idagenda";

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE a.ativo = 1 " : " WHERE a.ativo = 1 and (CONCAT(p.nome, ' ', p.sobrenome) LIKE '%{$search}%' OR p.cpf LIKE '%{$search}%' OR a.descricao LIKE '%{$search}%') ";
        return $this->query("SELECT a.descricao,a.idagenda, p.idprofissional ,p.nome, p.sobrenome, p.cpf FROM agenda a inner join profissional p on (a.id_profissional = p.idprofissional) {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE a.ativo = 1 " : " WHERE a.ativo = 1 and (CONCAT(p.nome, ' ', p.sobrenome) LIKE '%{$search}%' OR p.cpf LIKE '%{$search}%' OR a.descricao LIKE '%{$search}%') ";
        $dados = $this->query("SELECT count(a.idagenda) as totalRegistro FROM agenda a inner join profissional p on (a.id_profissional = p.idprofissional) {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idagenda) as totalRegistro FROM agenda WHERE ativo = 1");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM agenda WHERE idagenda = $id and ativo = 1 LIMIT 1");
        return $dados[0]["agenda"];
    }

    public function excluir($id) {
        return $this->query("UPDATE agenda SET ativo = 0 WHERE idagenda = $id");
    }

    public function retornarPorIdProfissional($id) {
        $dados = $this->query("SELECT * FROM agenda WHERE id_profissional = $id and ativo = 1 LIMIT 1");
        return $dados;
    }
}
