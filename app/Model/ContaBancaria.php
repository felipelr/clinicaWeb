<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP ContaBancaria
 * @author Felipe
 */
class ContaBancaria extends AppModel {
    
    public $useTable = "conta_bancaria";
    public $primaryKey = "idcontabancaria";
    
    public function excluir($id) {
        return $this->query("UPDATE conta_bancaria SET ativo = 0 WHERE idcontabancaria = $id");
    }
    
    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM conta_bancaria cb INNER JOIN banco b ON cb.id_banco = b.idbanco where cb.ativo = 1 order by b.descricao");
        return $dados;
    }
    
    public function retornarPorIdPaciente($idpaciente){
        $dados = $this->query("SELECT * FROM conta_bancaria cb INNER JOIN banco b ON cb.id_banco = b.idbanco where cb.ativo = 1 and cb.id_paciente = $idpaciente order by b.descricao");
        return $dados;
    }
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM conta_bancaria WHERE idcontabancaria = $id and ativo = 1 LIMIT 1");
        return $dados[0]["conta_bancaria"];
    }
    
    public function retornarPorIdComBanco($id) {
        $dados = $this->query("SELECT * FROM conta_bancaria cb INNER JOIN banco b ON cb.id_banco = b.idbanco WHERE cb.idcontabancaria = $id and cb.ativo = 1 LIMIT 1");
        return $dados[0];
    }
    
    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE c.ativo = 1  " : " WHERE c.agencia LIKE '%{$search}%' AND c.conta LIKE '%{$search}%' AND c.titular LIKE '%{$search}%' ";
        return $this->query("SELECT c.* FROM conta_bancaria c {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE c.ativo = 1  " : " WHERE c.agencia LIKE '%{$search}%' AND c.conta LIKE '%{$search}%' AND c.titular LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(c.idcontabancaria) as totalRegistro FROM conta_bancaria c {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(c.idcontabancaria) as totalRegistro FROM conta_bancaria c");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }
}
