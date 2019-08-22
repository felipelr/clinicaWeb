<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Favorecido
 * @author BersaN & StarK
 */
class Favorecido extends AppModel {

    public $useTable = "favorecido";
    public $primaryKey = "idfavorecido";

    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM favorecido f where f.ativo = 1 order by f.nome");
        return $dados;
    }
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM favorecido WHERE idfavorecido = $id LIMIT 1");
        return $dados[0]["favorecido"];
    }
    
    public function excluir($id) {
        return $this->query("UPDATE favorecido SET ativo = 0 WHERE idfavorecido = $id");
    }
    
    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE f.ativo = 1 " : " WHERE f.ativo = 1 and f.nome LIKE '%{$search}%' ";
        return $this->query("SELECT f.* FROM favorecido f {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE f.ativo = 1 " : " WHERE f.ativo = 1 and f.nome LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(f.idfavorecido) as totalRegistro FROM favorecido f {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idfavorecido) as totalRegistro FROM favorecido WHERE ativo = 1");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }
}
