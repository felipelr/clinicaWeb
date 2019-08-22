<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP TipoUsuario
 * @author Felipe
 */
class TipoUsuario extends AppModel {
    
    public $useTable = "tipo_usuario";
    public $primaryKey = "idtipousuario";
    
    
    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM tipo_usuario t WHERE t.ativo = 1 order by t.descricao");
        return $dados;
    }
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM tipo_usuario WHERE idtipousuario = $id LIMIT 1");
        return $dados[0]["tipo_usuario"];
    }
    
    public function excluir($id) {
        return $this->query("UPDATE tipo_usuario SET ativo = 0 WHERE idtipousuario = $id");
    }
    
    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE t.ativo = 1 " : " WHERE t.ativo = 1 and t.descricao LIKE '%{$search}%' ";
        return $this->query("SELECT t.* FROM tipo_usuario t {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE t.ativo = 1 " : " WHERE t.ativo = 1 and t.descricao LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(t.idtipousuario) as totalRegistro FROM tipo_usuario t {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idtipousuario) as totalRegistro FROM tipo_usuario WHERE ativo = 1");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }
    
}
