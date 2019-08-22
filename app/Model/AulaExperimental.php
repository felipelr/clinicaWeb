<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP AulaExperimental
 * @author felip
 */
class AulaExperimental extends AppModel {
    
    public $useTable = "aula_experimental";
    public $primaryKey = "idaulaexperimental";
    
    
    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM aula_experimental a order by a.nome");
        return $dados;
    }
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM aula_experimental WHERE idaulaexperimental = $id LIMIT 1");
        return $dados[0]["aula_experimental"];
    }
    
    public function retornarTodosPaging($length, $start){
        $dados = $this->query("SELECT * FROM aula_experimental a order by a.nome LIMIT $start, $length");
        return $dados;
    }
    
    public function totalRegistros() {
        $dados = $this->query("SELECT count(*) as totalRegistro FROM aula_experimental");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }
    
    public function retornarPorListaIds($ids){
        $dados = $this->query("SELECT * FROM aula_experimental a WHERE idaulaexperimental IN ($ids) order by a.nome;");
        return $dados;
    }
    
    public function excluirPorListaIds($ids){
        $this->query("DELETE FROM aula_experimental WHERE idaulaexperimental IN ($ids);");
    }
}
