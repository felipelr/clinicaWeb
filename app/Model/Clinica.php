<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Clinica
 * @author Felipe
 */
class Clinica extends AppModel {
    
    public $useTable = "clinica";
    public $primaryKey = "idclinica";    
    
    public function retornarTodos($where = null){
        $sqlWhere = ($where == null ? "" : $where);
        $dados = $this->query("SELECT * FROM clinica c where c.ativo = 1 {$sqlWhere} order by c.idclinica");
        return $dados;
    }
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM clinica WHERE idclinica = $id LIMIT 1");
        return $dados[0]["clinica"];
    }
}
