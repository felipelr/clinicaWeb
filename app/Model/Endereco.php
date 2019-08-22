<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Endereco
 * @author Felipe
 */
class Endereco extends AppModel {
    public $useTable = "endereco"; 
    public $primaryKey = "idendereco";
    
    public function retornarPorPaciente($id){
        $dados = $this->query("SELECT * FROM endereco WHERE id_paciente = $id LIMIT 1");
        return $dados[0]["endereco"];
    }
    
    public function retornarPorProfissional($id){
        $dados = $this->query("SELECT * FROM endereco WHERE id_profissional = $id LIMIT 1");
        return $dados[0]["endereco"];
    }
    
    public function retornarPorFavorecido($id){
        $dados = $this->query("SELECT * FROM endereco WHERE id_favorecido = $id LIMIT 1");
        return $dados[0]["endereco"];
    }
    
    public function retornarGeoLocalizacoes(){
        $dados = $this->query("SELECT e.longitude, e.latitude, p.nome, p.sobrenome FROM endereco e INNER JOIN paciente p ON (e.id_paciente = p.idpaciente) WHERE p.ativo = 1");
        return $dados;
    }
}
