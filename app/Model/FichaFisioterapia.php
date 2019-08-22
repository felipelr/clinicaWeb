<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP FichaFisioterapia
 * @author felipe
 */
class FichaFisioterapia extends AppModel {
    
    public $useTable = "ficha_fisioterapia";
    public $primaryKey = "idfichafisioterapia";
    
    public function retornarPorId($id){
        $dados = $this->query("SELECT * FROM ficha_fisioterapia WHERE idfichafisioterapia = {$id} LIMIT 1");
        return $dados[0]["ficha_fisioterapia"];
    }
    
    public function retornarPorIdPaciente($id){
        $dados = $this->query("SELECT idfichafisioterapia, id_paciente, descricao FROM ficha_fisioterapia WHERE id_paciente = {$id} and ativo = 1; ");
        return $dados;
    }
    
    public function listarJQuery($idpaciente, $search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE f.ativo = 1 and f.id_paciente = $idpaciente " : " WHERE f.ativo = 1 and f.id_paciente = $idpaciente and (CONCAT(pac.nome, ' ', pac.sobrenome) LIKE '%{$search}%' OR CONCAT(pro.nome, ' ', pro.sobrenome) LIKE '%{$search}%') ";
        return $this->query("SELECT f.*, pac.nome, pac.sobrenome, pro.nome, pro.sobrenome FROM ficha_fisioterapia f 
                INNER JOIN paciente pac ON (f.id_paciente = pac.idpaciente) 
                INNER JOIN profissional pro ON (f.id_profissional = pro.idprofissional)
                {$query} {$order} {$sql}; ");
    }

    public function totalRegistroFiltrado($idpaciente, $search = null) {
        $query = (is_null($search)) ? " WHERE f.ativo = 1 and f.id_paciente = $idpaciente " : " WHERE f.ativo = 1 and f.id_paciente = $idpaciente and (CONCAT(pac.nome, ' ', pac.sobrenome) LIKE '%{$search}%' OR CONCAT(pro.nome, ' ', pro.sobrenome) LIKE '%{$search}%') ";
        $dados = $this->query("SELECT count(*) as totalRegistro FROM ficha_fisioterapia f 
                INNER JOIN paciente pac ON (f.id_paciente = pac.idpaciente) 
                INNER JOIN profissional pro ON (f.id_profissional = pro.idprofissional) {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro($idpaciente) {
        $dados = $this->query("SELECT count(*) as totalRegistro FROM ficha_fisioterapia f 
                INNER JOIN paciente pac ON (f.id_paciente = pac.idpaciente) 
                INNER JOIN profissional pro ON (f.id_profissional = pro.idprofissional) WHERE f.ativo = 1 and f.id_paciente = $idpaciente; ");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }
    
    public function excluir($id) {
        return $this->query("UPDATE ficha_fisioterapia SET ativo = 0 WHERE idfichafisioterapia = $id");
    }
    
}
