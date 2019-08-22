<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Banco
 * @author Felipe
 */
class Banco extends AppModel {
    
    public $useTable = "banco";
    public $primaryKey = "idbanco";
    
    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM banco b order by b.descricao");
        return $dados;
    }
}
