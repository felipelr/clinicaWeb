<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Cargo
 * @author Felipe
 */
class Cargo extends AppModel {
    
    public $useTable = "cargo";
    public $primaryKey = "idcargo";
    
    
    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM cargo c where c.ativo = 1 order by c.descricao");
        return $dados;
    }
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM cargo WHERE idcargo = $id LIMIT 1");
        return $dados[0]["cargo"];
    }
    
    public function excluir($id) {
        return $this->query("UPDATE cargo SET ativo = 0 WHERE idcargo = $id");
    }
    
    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE c.ativo = 1 " : " WHERE c.ativo = 1 and c.descricao LIKE '%{$search}%' ";
        return $this->query("SELECT c.* FROM cargo c {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE c.ativo = 1 " : " WHERE c.ativo = 1 and c.descricao LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(c.idcargo) as totalRegistro FROM cargo c {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idcargo) as totalRegistro FROM cargo WHERE ativo = 1");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }
}
