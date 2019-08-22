<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP PlanoSessao
 * @author Felipe
 */
class PlanoSessao extends AppModel {

    public $useTable = "plano_sessao";
    public $primaryKey = "idplanosessao";

    public function retornarTodos() {
        $dados = $this->query("SELECT * FROM plano_sessao p where p.ativo = 1 order by p.descricao");
        return $dados;
    }

    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM plano_sessao WHERE idplanosessao = $id and ativo = 1 LIMIT 1");
        return $dados[0]["plano_sessao"];
    }

    public function excluir($id) {
        return $this->query("UPDATE plano_sessao SET ativo = 0 WHERE idplanosessao = $id");
    }

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE p.ativo = 1 " : " WHERE p.ativo = 1 and p.descricao LIKE '%{$search}%' ";
        return $this->query("SELECT p.descricao, p.valor, p.quantidade_sessoes, p.quantidade_meses,p.idplanosessao FROM plano_sessao p {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE p.ativo = 1 " : " WHERE p.ativo = 1 and p.descricao LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(p.idplanosessao) as totalRegistro FROM plano_sessao p {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idplanosessao) as totalRegistro FROM plano_sessao WHERE ativo = 1");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function retornarValidosParaEventos($idpaciente) {
        $dados = $this->query("SELECT *
                FROM evento_disponibilidade ed 
                left join plano_sessao p on (p.idplanosessao = ed.id_plano_sessao)
                where ed.id_paciente = $idpaciente and ed.total > 0 order by p.descricao;");
        return $dados;
    }

}
