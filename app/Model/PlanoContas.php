<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP PlanoContas
 * @author BersaN & StarK
 */
class PlanoContas extends AppModel {

    public $useTable = "plano_contas";
    public $primaryKey = "idplanocontas";

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? "" : " WHERE p.descricao LIKE '%{$search}%'";
        return $this->query("SELECT p.idplanocontas, p.descricao,p.observacao FROM plano_contas p  {$query} {$order} {$sql};");
    }

    public function listarPorId($id) {
        $dados = $this->query("SELECT * FROM plano_contas WHERE idplanocontas = $id LIMIT 1");
        return $dados[0]["plano_contas"];
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? "" : " WHERE p.descricao LIKE '%{$search}%'";
        $dados = $this->query("SELECT count(p.idplanocontas) as totalRegistro FROM plano_contas p {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idplanocontas) as totalRegistro FROM plano_contas");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function excluir($id) {
        return $this->query("UPDATE plano_contas SET ATIVO = 0 WHERE idplanocontas = $id");
    }

    public function retornarTodos() {
        $dados = $this->query("SELECT * FROM plano_contas p order by descricao");
        return $dados;
    }
    
    public function retornarTodosReceitas() {
        $dados = $this->query("SELECT * FROM plano_contas p where p.tipo = 'R' order by descricao");
        return $dados;
    }
    
    public function retornarTodosDespesas() {
        $dados = $this->query("SELECT * FROM plano_contas p where p.tipo = 'D' order by descricao");
        return $dados;
    }

}
